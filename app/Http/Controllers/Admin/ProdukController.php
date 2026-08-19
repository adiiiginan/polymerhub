<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\ProdukKategori;
use App\Models\ProdukEnvi;
use App\Models\ProdukJenis;
use App\Models\ProdukCategory;
use App\Models\Ukuran;
use Illuminate\Http\Request;
use App\Models\ProdukStok;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    // =========================================================
    // Helper: tentukan tipe produk berdasarkan produk_category
    // Return: 'rulon' | 'tygon' | 'sg-25' | null
    // =========================================================
    private function getCategoryType(?ProdukCategory $cat): ?string
    {
        if (!$cat) return null;

        $name = strtolower($cat->category);

        if (str_contains($name, 'rulon'))                                                           return 'rulon';
        if (str_contains($name, 'tygon') || str_contains($name, 'tubing') || str_contains($name, 'tube')) return 'tygon';
        if (str_contains($name, 'top tape') || str_contains($name, 'sg-25') || str_contains($name, 'tape')) return 'sg-25';

        return null;
    }


    private function getFieldConfig(?ProdukCategory $cat): array
    {
        $type = $this->getCategoryType($cat);
        return [
            'categoryType'   => $type,
            'showRulon'      => $type === 'rulon',
            'showTubing'     => $type === 'tygon',
            'showSg25'       => $type === 'sg-25',
            'showMechanical' => $type === 'rulon',
        ];
    }

    // =========================================================
    // INDEX
    // =========================================================
    public function index(Request $request)
    {
        $query = Produk::with(['variants', 'variants.jenis', 'kategori']);

        if ($request->has('search')) {
            $query->where('nama_produk', 'like', '%' . $request->input('search') . '%');
        }

        $produk = $query->latest()->get();

        if ($request->ajax()) {
            return view('admin.produk.partials.produk_table', compact('produk'))->render();
        }

        return view('admin.produk.index', compact('produk'));
    }

    // =========================================================
    // CREATE
    // =========================================================
    public function create(Request $request)
    {
        $kode           = $this->generateKodeProduk();
        $kategori       = ProdukKategori::all();
        $environment    = ProdukEnvi::all();
        $shapes         = ProdukJenis::where('type', 'shape')->get();
        $jenis          = ProdukJenis::where('type', 'tygon')->get();
        $produkCategory = ProdukCategory::all();
        $ukuranSg25     = Ukuran::where('id_produk_jenis', 11)->get();

        $ukurans = $request->filled('shape_id')
            ? Ukuran::where('id_produk_jenis', $request->shape_id)->get()
            : collect();

        $selectedCat = $request->filled('id_cat')
            ? ProdukCategory::find($request->id_cat)
            : null;

        $fieldConfig = $this->getFieldConfig($selectedCat);

        return view('admin.produk.create', array_merge(
            compact('kode', 'kategori', 'environment', 'shapes', 'jenis', 'ukurans', 'ukuranSg25', 'produkCategory', 'selectedCat'),
            $fieldConfig
        ));
    }

    // =========================================================
    // STORE
    // =========================================================
    public function store(Request $request)
    {
        // ── 1. Validasi umum ────────────────────────────────────────
        $request->validate([
            'id_cat'      => 'required',
            'sku'         => 'required|string|max:100|unique:produk,sku',
            'nama_produk' => 'required|string|max:255',
            'gambar'      => 'nullable|image|max:2048',
        ]);

        // ── Tentukan tipe produk ─────────────────────────────────────
        $cat    = ProdukCategory::find($request->id_cat);
        $type   = $this->getCategoryType($cat);
        $isTape = $type === 'sg-25';

        // ── Validasi tambahan khusus SG-25 ──────────────────────────
        if ($isTape) {
            $request->validate([
                'sg25_ukuran_id'   => 'required|array|min:1',
                'sg25_ukuran_id.*' => 'required|exists:produk_ukuran,id',
                'sg25_stok'        => 'required|array|min:1',
                'sg25_stok.*'      => 'required|integer|min:0',
                'sg25_harga'       => 'required|array|min:1',
                'sg25_harga.*'     => 'required|integer|min:0',
            ]);
        }

        // ── 2. Upload gambar ─────────────────────────────────────────
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file     = $request->file('gambar');
            $namaFile = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('backend/assets/media/produk'), $namaFile);
            $gambarPath = $namaFile; // hanya simpan nama file saja di DB
        }

        // ── 3. Bungkus dalam transaksi ───────────────────────────────
        DB::transaction(function () use ($request, $gambarPath, $isTape, $type) {

            // -- Simpan data produk utama --
            // Catatan: kolom weight/length/width/height TIDAK ada di tabel produk,
            // kolom tersebut ada di produk_stok. Kolom eu1935/fda/usp di DB bertipe
            // enum('Ya','Tidak') sehingga perlu ucfirst().
            $produk = Produk::create([
                'kode_produk'  => $request->kode_produk,
                'sku'          => $request->sku,
                'nama_produk'  => $request->nama_produk,
                'id_cat'       => $request->id_cat,
                'id_kat'       => $request->id_kat,
                'merk'         => $request->merk,
                'deskripsi'    => $request->deskripsi,
                'status_aktif' => $request->status_aktif ?? 1,
                'gambar'       => $gambarPath,

                // Spesifikasi teknis umum
                'tensile'    => $request->tensile,
                'elongation' => $request->elongation,
                'spesific'   => $request->spesific,
                'friction'   => $request->friction,

                // Spesifikasi khusus Rulon
                'id_environmant' => $request->id_environmant,
                'pressure'       => $request->pressure,
                'mating'         => $request->mating,
                'max_pv'         => $request->max_pv,
                'maximum_p'      => $request->maximum_p,
                'max_v'          => $request->max_v,
                'deformation'    => $request->deformation,

                // enum di DB adalah 'Ya'/'Tidak' (kapital), form kirim 'ya'/'tidak'
                'eu1935' => $request->eu1935 ? ucfirst($request->eu1935) : null,
                'fda'    => $request->fda    ? ucfirst($request->fda)    : null,
                'usp'    => $request->usp    ? ucfirst($request->usp)    : null,

                // Spesifikasi khusus Tygon
                'tygon_size_category'        => $request->tygon_size_category,
                'inner_diameter'             => $request->inner_diameter,
                'outer_diameter'             => $request->outer_diameter,
                'wall_thickness'             => $request->wall_thickness,
                'tygon_length'               => $request->tygon_length,
                'min_bend_radius'            => $request->min_bend_radius,
                'tygon_working_pressure_73'  => $request->tygon_working_pressure_73,
                'tygon_working_pressure_320' => $request->tygon_working_pressure_320,
                'tygon_vacuum_73'            => $request->tygon_vacuum_73,
                'tygon_vacuum_320'           => $request->tygon_vacuum_320,
            ]);

            // ── Simpan stok Rulon ke produk_stok ────────────────────────
            if ($type === 'rulon' && $request->filled('id_ukuran')) {
                $ukuranArr = (array) $request->input('id_ukuran');
                $stokArr   = (array) $request->input('stok_variant');
                $hargaArr  = (array) $request->input('harga_variant');

                foreach ($ukuranArr as $i => $ukuranId) {
                    if (!$ukuranId) continue;

                    DB::table('produk_stok')->insert([
                        'id_produk'  => $produk->id,
                        'id_jenis'   => $request->shape_id,
                        'id_ukuran'  => $ukuranId,
                        'stok'       => $stokArr[$i]  ?? 0,
                        'harga'      => $hargaArr[$i] ?? 0,
                        'weight'     => $request->weight,
                        'length'     => $request->length,
                        'width'      => $request->width,
                        'height'     => $request->height,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // ── Tygon → produk_stok ─────────────────────────────────────
            if ($type === 'tygon') {
                DB::table('produk_stok')->insert([
                    'id_produk'  => $produk->id,
                    'id_jenis'   => $request->tygon_size_category,
                    'id_ukuran'  => null,
                    'stok'       => $request->tygon_stok  ?? 0,
                    'harga'      => $request->tygon_harga ?? 0,
                    'weight'     => $request->weight,
                    'length'     => $request->length,
                    'width'      => $request->width,
                    'height'     => $request->height,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ── SG-25 → produk_stok ─────────────────────────────────────
            if ($isTape) {
                $ukuranIds = $request->input('sg25_ukuran_id', []);
                $stoks     = $request->input('sg25_stok', []);
                $hargas    = $request->input('sg25_harga', []);

                foreach ($ukuranIds as $i => $ukuranId) {
                    if (!$ukuranId) continue;

                    DB::table('produk_stok')->insert([
                        'id_produk'  => $produk->id,
                        'id_jenis'   => 11,
                        'id_ukuran'  => $ukuranId,
                        'stok'       => $stoks[$i]  ?? 0,
                        'harga'      => $hargas[$i] ?? 0,
                        'weight'     => $request->weight,
                        'length'     => $request->length,
                        'width'      => $request->width,
                        'height'     => $request->height,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    // =========================================================
    // EDIT
    // =========================================================
    public function edit(Request $request, Produk $produk)
    {
        $kategori       = ProdukKategori::all();
        $environment    = ProdukEnvi::all();
        $shapes         = ProdukJenis::where('type', 'shape')->get();
        $jenis          = ProdukJenis::where('type', 'tygon')->get();
        $produkCategory = ProdukCategory::all();
        $ukuranSg25     = Ukuran::where('id_produk_jenis', 11)->get();

        // ✅ Query langsung bypass relasi 'stok' yang konflik dengan kolom DB
        $variantList = ProdukStok::where('id_produk', $produk->id)->get();

        // ✅ Set relasi manual agar blade bisa pakai $produk->variants
        $produk->setRelation('variants', $variantList);

        // Ambil stok pertama untuk data dimensi dan shape
        $firstStock = $variantList->first();

        // Ambil id_jenis dari stok pertama jika ada
        $shape_id_for_ukurans = $firstStock ? $firstStock->id_jenis : null;

        $ukurans = $shape_id_for_ukurans
            ? Ukuran::where('id_produk_jenis', $shape_id_for_ukurans)->get()
            : collect();

        $selectedCat = $produk->id_cat
            ? ProdukCategory::find($produk->id_cat)
            : null;

        $fieldConfig = $this->getFieldConfig($selectedCat);

        return view('admin.produk.edit', array_merge(
            compact(
                'produk',
                'kategori',
                'environment',
                'shapes',
                'jenis',
                'ukurans',
                'ukuranSg25',
                'produkCategory',
                'selectedCat',
                'firstStock'
            ),
            $fieldConfig
        ));
    }

    // =========================================================
    // SHOW
    // =========================================================
    public function show($id)
    {
        $produk = Produk::with(['kategori', 'envi', 'variants', 'variants.jenis', 'variants.ukuran', 'category'])
            ->findOrFail($id);

        // Set relasi variants secara manual (bypass kolom 'stok')
        $variantList = ProdukStok::where('id_produk', $id)->with(['jenis', 'ukuran'])->get();
        $produk->setRelation('variants', $variantList);

        return view('admin.produk.detail', compact('produk'));
    }

    // =========================================================
    // UPDATE
    // =========================================================
    public function update(Request $request, Produk $produk)
    {
        // Dapatkan kategori dan tipe produk
        $selectedCat = $request->filled('id_cat')
            ? ProdukCategory::find($request->id_cat)
            : ($produk->id_cat ? ProdukCategory::find($produk->id_cat) : null);

        $fieldConfig = $this->getFieldConfig($selectedCat);
        $type        = $fieldConfig['categoryType'];

        // Aturan validasi dasar
        $rules = [
            'nama_produk'  => 'required|string|max:255',
            'sku'          => 'required|string|max:255|unique:produk,sku,' . $produk->id,
            'status_aktif' => 'nullable|boolean',
            'id_kat'       => 'nullable|exists:produk_kategori,id',
            'deskripsi'    => 'nullable|string',
            'merk'         => 'nullable|string|max:255',
            'eu1935'       => 'nullable|in:ya,tidak',
            'fda'          => 'nullable|in:ya,tidak',
            'usp'          => 'nullable|in:ya,tidak',
            'weight'       => 'nullable|numeric',
            'length'       => 'nullable|numeric',
            'width'        => 'nullable|numeric',
            'height'       => 'nullable|numeric',
        ];

        // Aturan validasi spesifik per tipe produk
        if ($type === 'rulon') {
            $rules['shape_id']       = 'required|exists:produk_jenis,id';
            $rules['id_environmant'] = 'nullable|exists:produk_envi,id';
            $rules['pressure']       = 'nullable|string|max:255';
            $rules['mating']         = 'nullable|string|max:255';
            $rules['max_pv']         = 'nullable|numeric|min:0';
            $rules['maximum_p']      = 'nullable|string|max:255';
            $rules['max_v']          = 'nullable|numeric|min:0';
            $rules['friction']       = 'nullable|string|max:255';
            $rules['elongation']     = 'nullable|string|max:255';
            $rules['deformation']    = 'nullable|numeric';
            $rules['tensile']        = 'nullable|numeric';
            $rules['spesific']       = 'nullable|numeric';
        }

        if ($type === 'tygon') {
            $rules['tygon_size_category'] = 'required|exists:produk_jenis,id';
            $rules['outer_diameter']      = 'nullable|regex:/^[\d\.\/\s]+$/';
            $rules['inner_diameter']      = 'nullable|regex:/^[\d\.\/\s]+$/';
            $rules['wall_thickness']      = 'nullable|regex:/^[\d\.\/\s]+$/';
            $rules['min_bend_radius']     = 'nullable|regex:/^[\d\.\/\s]+$/';
            $rules['tygon_length']        = 'nullable|numeric|min:0';
            $rules['tygon_stok']          = 'nullable|integer|min:0';
            $rules['tygon_harga']         = 'nullable|numeric|min:0';
        }

        if ($type === 'sg-25') {
            $rules['sg25_ukuran_id']   = 'nullable|array';
            $rules['sg25_ukuran_id.*'] = 'nullable|exists:produk_ukuran,id';
            $rules['sg25_stok']        = 'nullable|array';
            $rules['sg25_stok.*']      = 'nullable|integer|min:0';
            $rules['sg25_harga']       = 'nullable|array';
            $rules['sg25_harga.*']     = 'nullable|integer|min:0';
        }

        if ($request->hasFile('gambar')) {
            $rules['gambar'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        $request->validate($rules);

        // Mulai transaksi database
        DB::transaction(function () use ($request, $produk, $type) {
            // Handle upload gambar baru
            if ($request->hasFile('gambar')) {
                $gambar   = $request->file('gambar');
                $namaFile = time() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move(public_path('backend/assets/media/produk'), $namaFile);

                // Hapus gambar lama jika ada
                if ($produk->gambar && file_exists(public_path('backend/assets/media/produk/' . $produk->gambar))) {
                    unlink(public_path('backend/assets/media/produk/' . $produk->gambar));
                }
                $produk->gambar = $namaFile;
            }

            // Update data produk utama
            $produk->fill([
                'nama_produk'  => $request->nama_produk,
                'sku'          => $request->sku,
                'deskripsi'    => $request->deskripsi,
                'id_kat'       => $request->id_kat ?? $request->id_kategori,
                'merk'         => $request->merk,
                'status_aktif' => $request->status_aktif ?? 1,
                'eu1935'       => $request->eu1935 ? ucfirst($request->eu1935) : null,
                'fda'          => $request->fda    ? ucfirst($request->fda)    : null,
                'usp'          => $request->usp    ? ucfirst($request->usp)    : null,
            ]);

            // Update data spesifik per tipe
            if ($type === 'rulon') {
                $produk->fill($request->only([
                    'id_environmant',
                    'pressure',
                    'mating',
                    'max_pv',
                    'maximum_p',
                    'max_v',
                    'deformation',
                    'tensile',
                    'elongation',
                    'spesific',
                    'friction'
                ]));
            } elseif ($type === 'tygon') {
                $produk->fill($request->only([
                    'tygon_size_category',
                    'inner_diameter',
                    'outer_diameter',
                    'wall_thickness',
                    'tygon_length',
                    'min_bend_radius',
                    'tygon_working_pressure_73',
                    'tygon_working_pressure_320',
                    'tygon_vacuum_73',
                    'tygon_vacuum_320'
                ]));
            } elseif ($type === 'sg-25') {
                $produk->fill($request->only(['tensile', 'elongation', 'spesific', 'friction']));
            }

            $produk->save();

            // Update/Create/Delete Stok
            $commonStokData = $request->only(['weight', 'length', 'width', 'height']);

            if ($type === 'rulon') {
                $submittedUkuranIds = $request->input('id_ukuran', []);
                // Hapus stok yang ukurannya tidak ada lagi di request
                ProdukStok::where('id_produk', $produk->id)
                    ->whereNotIn('id_ukuran', array_filter($submittedUkuranIds))
                    ->delete();

                foreach ($submittedUkuranIds as $i => $ukuranId) {
                    if (!$ukuranId) continue;
                    ProdukStok::updateOrCreate(
                        [
                            'id_produk' => $produk->id,
                            'id_ukuran' => $ukuranId,
                        ],
                        array_merge($commonStokData, [
                            'id_jenis' => $request->shape_id,
                            'stok'     => $request->stok_variant[$i] ?? 0,
                            'harga'    => $request->harga_variant[$i] ?? 0,
                        ])
                    );
                }
            } elseif ($type === 'tygon') {
                ProdukStok::updateOrCreate(
                    ['id_produk' => $produk->id],
                    array_merge($commonStokData, [
                        'id_jenis'  => $request->tygon_size_category,
                        'id_ukuran' => null,
                        'stok'      => $request->tygon_stok ?? 0,
                        'harga'     => $request->tygon_harga ?? 0,
                    ])
                );
            } elseif ($type === 'sg-25') {
                $submittedUkuranIds = $request->input('sg25_ukuran_id', []);
                // Hapus stok yang ukurannya tidak ada lagi di request
                ProdukStok::where('id_produk', $produk->id)
                    ->whereNotIn('id_ukuran', array_filter($submittedUkuranIds))
                    ->delete();

                foreach ($submittedUkuranIds as $i => $ukuranId) {
                    if (!$ukuranId) continue;
                    ProdukStok::updateOrCreate(
                        [
                            'id_produk' => $produk->id,
                            'id_ukuran' => $ukuranId,
                        ],
                        array_merge($commonStokData, [
                            'id_jenis' => 11, // Hardcoded for SG-25
                            'stok'     => $request->sg25_stok[$i] ?? 0,
                            'harga'    => $request->sg25_harga[$i] ?? 0,
                        ])
                    );
                }
            }
        });

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }


    // =========================================================
    // DESTROY
    // =========================================================
    public function destroy($id)
    {
        Log::info('Attempting to delete product ID: ' . $id . ' by User ID: ' . Auth::id());

        $produk = Produk::findOrFail($id);

        if ($produk->gambar && file_exists(public_path('backend/assets/media/produk/' . $produk->gambar))) {
            unlink(public_path('backend/assets/media/produk/' . $produk->gambar));
        }

        // produk_stok akan otomatis terhapus karena ada ON DELETE CASCADE
        $produk->delete();

        Log::info('Product ID: ' . $id . ' deleted successfully.');

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus');
    }

    // =========================================================
    // STOK
    // =========================================================
    public function getStock(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'id_jenis'  => 'required|exists:produk_jenis,id',
            'id_ukuran' => 'required|exists:produk_ukuran,id',
        ]);

        $stok = ProdukStok::where('id_produk', $request->id_produk)
            ->where('id_jenis', $request->id_jenis)
            ->where('id_ukuran', $request->id_ukuran)
            ->first();

        return response()->json(['stok' => $stok ? $stok->stok : 0]);
    }

    public function stokIndex()
    {
        $produk = Produk::paginate(10);
        return view('admin.Stok.index', compact('produk'));
    }

    public function stokEdit(Produk $produk)
    {
        return view('admin.Stok.edit', compact('produk'));
    }

    public function stokUpdate(Request $request, Produk $produk)
    {
        $request->validate([
            'id_ukuran' => 'required|exists:produk_ukuran,id',
            'jumlah'    => 'required|integer|min:1',
        ]);

        $stok = ProdukStok::where('id_produk', $produk->id)
            ->where('id_ukuran', $request->id_ukuran)
            ->first();

        if ($stok) {
            $stok->stok += $request->jumlah;
            $stok->save();
        } else {
            ProdukStok::create([
                'id_produk' => $produk->id,
                'id_jenis'  => $produk->id_jenis,
                'id_ukuran' => $request->id_ukuran,
                'stok'      => $request->jumlah,
                'harga'     => 0,
            ]);
        }

        return redirect()->route('admin.stok.index')
            ->with('success', 'Stok berhasil ditambahkan.');
    }

    // =========================================================
    // HELPERS
    // =========================================================
    private function generateKodeProduk(): string
    {
        $lastProduk = Produk::where('kode_produk', 'like', 'JNS%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastProduk) return 'JNS001';

        $lastNumber = (int) substr($lastProduk->kode_produk, 3);
        return 'JNS' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    public function getUkuranByJenis(Request $request)
    {
        $ukuran = Ukuran::where('id_produk_jenis', $request->id_jenis)
            ->get(['id', 'nama_ukuran as ukuran']);
        return response()->json($ukuran);
    }

    public function getFieldsByCategory(ProdukCategory $category)
    {
        return response()->json($this->getFieldConfig($category));
    }

    // =========================================================
    // API: Get Ukurans by Shape ID
    // =========================================================
    public function getUkuransByShape($shapeId)
    {
        $ukurans = Ukuran::where('id_produk_jenis', $shapeId)->get();
        return response()->json($ukurans);
    }
}
