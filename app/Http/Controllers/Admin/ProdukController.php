<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\ProdukKategori;
use App\Models\ProdukEnvi;
use App\Models\ProdukJenis;
use App\Models\Ukuran;
use Illuminate\Http\Request;
use App\Models\ProdukStok;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = produk::with('variants.jenis', 'variants.ukuran');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_produk', 'like', '%' . $search . '%');
        }

        $produk = $query->latest()->get();

        if ($request->ajax()) {
            return view('admin.produk.partials.produk_table', compact('produk'))->render();
        }

        return view('admin.Produk.index', compact('produk'));
    }

    /**
     * Tampilkan form create
     */
    public function create(Request $request)
    {
        // kirim data untuk select (jenis, kategori, environment, dsb)
        $kode = $this->generateKodeProduk();
        // ...
        $kategori = ProdukKategori::all();
        $environment = ProdukEnvi::all();
        $shapes = ProdukJenis::all();
        $ukurans = collect(); // Default ke koleksi kosong

        if ($request->has('shape_id')) {
            $ukurans = Ukuran::where('id_produk_jenis', $request->shape_id)->get();
        }

        return view('admin.Produk.create', compact('kategori', 'environment', 'kode', 'shapes', 'ukurans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'id_kat'      => 'required|exists:produk_kategori,id',
            'merk'        => 'nullable|string|max:255',
            'status_aktif' => 'required|boolean',
            'shape_id'    => 'required|exists:produk_jenis,id',
            'id_ukuran'   => 'required|array',
            'id_ukuran.*' => 'exists:produk_ukuran,id',
            'stok_variant' => 'required|array',
            'stok_variant.*' => 'integer|min:0',
            'harga_variant' => 'required|array',
            'harga_variant.*' => 'numeric|min:0',
            'weight' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
        ]);

        // Generate kode produk
        $kode = $this->generateKodeProduk();

        // Upload gambar
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('backend/assets/media/produk'), $filename);
            $gambarPath = $filename;
        }

        // Simpan produk
        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'sku' => $request->sku,
            'deskripsi'   => $request->deskripsi,
            'id_kat'      => $request->id_kat,
            'merk'        => $request->merk,
            'tempratur'   => $request->tempratur,
            'eu1935'      => $request->eu1935,
            'fda'         => $request->fda,
            'usp'         => $request->usp,
            'id_environmant' => $request->id_environmant,
            'pressure'    => $request->pressure,
            'mating'      => $request->mating,
            'max_pv'      => $request->max_pv,
            'maximum_p'   => $request->maximum_p,
            'max_v'       => $request->max_v,
            'friction'    => $request->friction,
            'elongation'  => $request->elongation,
            'deformation' => $request->deformation,
            'tensile'     => $request->tensile,
            'spesific'    => $request->spesific,
            'status_aktif' => $request->status_aktif,
            'kode_produk' => $kode,
            'gambar'      => $gambarPath,
            'id_jenis'    => $request->shape_id,
            'weight'      => $request->weight,
            'length'      => $request->length,
            'width'       => $request->width,
            'height'      => $request->height,
        ]);

        // Simpan stok varian
        foreach ($request->id_ukuran as $i => $idUkuran) {
            ProdukStok::create([
                'id_produk' => $produk->id,
                'id_jenis'  => $request->shape_id,
                'id_ukuran' => $idUkuran,
                'stok'      => $request->stok_variant[$i],
                'harga'     => $request->harga_variant[$i],
            ]);
        }

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function getStock(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'id_jenis' => 'required|exists:produk_jenis,id',
            'id_ukuran' => 'required|exists:produk_ukuran,id',
        ]);

        $stok = ProdukStok::where('id_produk', $request->id_produk)
            ->where('id_jenis', $request->id_jenis)
            ->where('id_ukuran', $request->id_ukuran)
            ->first();

        if ($stok) {
            return response()->json(['stok' => $stok->stok]);
        }

        return response()->json(['stok' => 0]);
    }

    /**
     * Edit produk
     */
    public function edit(Request $request, Produk $produk)
    {
        $kategori = ProdukKategori::all();
        $environment = ProdukEnvi::all();
        $shapes = ProdukJenis::all();
        $produk->load('variants.jenis', 'variants.ukuran');

        $shape_id_for_ukurans = old('shape_id', $produk->id_jenis);
        if ($produk->variants->isNotEmpty()) {
            $shape_id_for_ukurans = $produk->variants->first()->id_jenis;
        }

        $ukurans = Ukuran::where('id_produk_jenis', $shape_id_for_ukurans)->get();

        return view('admin.Produk.edit', compact('produk', 'kategori', 'environment', 'shapes', 'ukurans'));
    }

    public function show($id)
    {
        $produk = Produk::with(['kategori', 'envi'])->findOrFail($id);
        return view('Frontend.produk_detail', compact('produk'));
    }

    /**
     * Update produk
     */
    public function update(Request $request, Produk $produk)
    {
        // Restructure variants data
        $variants = [];
        if ($request->has('id_ukuran')) {
            foreach ($request->id_ukuran as $key => $id_ukuran) {
                $variants[$key]['id_ukuran'] = $id_ukuran;
                $variants[$key]['sku'] = $request->sku_variant[$key] ?? null;
                $variants[$key]['stok'] = $request->stok_variant[$key] ?? null;
                $variants[$key]['harga'] = $request->harga_variant[$key] ?? null;
                $variants[$key]['length'] = $request->length_variant[$key] ?? null;
                $variants[$key]['width'] = $request->width_variant[$key] ?? null;
                $variants[$key]['height'] = $request->height_variant[$key] ?? null;
                $variants[$key]['weight'] = $request->weight_variant[$key] ?? null;
            }
        }
        $request->merge(['variants' => $variants]);

        $rules = [
            'nama_produk' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'id_kategori' => 'required|exists:produk_kategori,id',
            'id_shape' => 'required|exists:produk_jenis,id',
            'variants' => 'nullable|array',
            'variants.*.id_ukuran' => 'required|exists:produk_ukuran,id',
            'variants.*.sku' => 'nullable|string|max:255',
            'variants.*.stok' => 'nullable|integer|min:0',
            'variants.*.harga' => 'nullable|numeric|min:0',
            'variants.*.length' => 'nullable|numeric',
            'variants.*.width' => 'nullable|numeric',
            'variants.*.height' => 'nullable|numeric',
            'variants.*.weight' => 'nullable|numeric',
        ];

        if ($request->hasFile('gambar')) {
            $rules['gambar'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        $request->validate($rules);

        $produk->fill($request->except(['_token', '_method', 'gambar', 'variants', 'id_ukuran', 'sku_variant', 'stok_variant', 'harga_variant', 'length_variant', 'width_variant', 'height_variant', 'weight_variant', 'id_kategori', 'id_shape']));

        // Handle file upload
        $gambarPath = $produk->gambar; // default gambar lama

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaFile = time() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move(public_path('backend/assets/media/produk'), $namaFile);

            // hapus gambar lama kalau ada
            if ($produk->gambar && file_exists(public_path('backend/assets/media/produk/' . $produk->gambar))) {
                unlink(public_path('backend/assets/media/produk/' . $produk->gambar));
            }

            $gambarPath = $namaFile;
        }

        $produk->gambar = $gambarPath;
        $produk->id_kat = $request->id_kategori;
        $produk->id_jenis = $request->id_shape;

        $produk->save();

        $produk->variants()->delete();

        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (isset($variantData['id_ukuran']) && (isset($variantData['stok']) || isset($variantData['harga']))) {
                    $produk->variants()->create([
                        'id_jenis'  => $request->id_shape,
                        'id_ukuran' => $variantData['id_ukuran'],
                        'sku'       => $variantData['sku'],
                        'stok'      => $variantData['stok'],
                        'harga'     => $variantData['harga'],
                        'length'    => $variantData['length'],
                        'width'     => $variantData['width'],
                        'height'    => $variantData['height'],
                        'weight'    => $variantData['weight'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // hapus file gambar juga kalau ada
        if ($produk->gambar && file_exists(public_path('backend/assets/media/produk/' . $produk->gambar))) {
            unlink(public_path('backend/assets/media/produk/' . $produk->gambar));
        }

        $produk->delete();

        return redirect()->route('admin.Produk.index')->with('success', 'Produk berhasil dihapus');
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
            'jumlah' => 'required|integer|min:1',
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

    private function generateKodeProduk()
    {
        // Ambil produk terakhir dengan kode dimulai JNS
        $lastProduk = Produk::where('kode_produk', 'like', 'JNS%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastProduk) {
            return 'JNS001';
        }

        $lastNumber = (int) substr($lastProduk->kode_produk, 3);
        $newNumber = $lastNumber + 1;
        $newKode = 'JNS' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return $newKode;
    }

    public function getUkuranByJenis(Request $request)
    {
        $ukuran = Ukuran::where('id_produk_jenis', $request->id_jenis)
            ->get(['id', 'nama_ukuran as ukuran']);
        return response()->json($ukuran);
    }
}
