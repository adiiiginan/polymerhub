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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    // =========================================================
    // Helper: tentukan tipe produk berdasarkan produk_category
    // Return: 'rulon' | 'tygon' | 'toptape' | null
    // =========================================================
    private function getCategoryType(?ProdukCategory $cat): ?string
    {
        if (!$cat) return null;

        $name = strtolower($cat->category);

        if (str_contains($name, 'rulon')) {
            return 'rulon';
        }

        if (str_contains($name, 'tygon') || str_contains($name, 'tubing') || str_contains($name, 'tube')) {
            return 'tygon';
        }

        if (str_contains($name, 'top tape') || str_contains($name, 'toptape') || str_contains($name, 'tape')) {
            return 'toptape';
        }

        return null;
    }

    // Helper lama (backward compat) — sekarang berbasis getCategoryType
    private function getFieldConfig(?ProdukCategory $cat): array
    {
        $type = $this->getCategoryType($cat);
        return [
            'categoryType'   => $type,
            'showRulon'      => $type === 'rulon',
            'showTubing'     => $type === 'tygon',
            'showTopTape'    => $type === 'toptape',
            'showMechanical' => $type === 'rulon',
        ];
    }

    // =========================================================
    // INDEX
    // =========================================================
    public function index(Request $request)
    {
        $query = Produk::with('variants.jenis', 'variants.ukuran');

        if ($request->has('search')) {
            $query->where('nama_produk', 'like', '%' . $request->input('search') . '%');
        }

        $produk = $query->latest()->get();

        if ($request->ajax()) {
            return view('admin.produk.partials.produk_table', compact('produk'))->render();
        }

        return view('admin.Produk.index', compact('produk'));
    }

    // =========================================================
    // CREATE
    // =========================================================
    public function create(Request $request)
    {
        $kode           = $this->generateKodeProduk();
        $kategori       = ProdukKategori::all();
        $environment    = ProdukEnvi::all();
        $shapes         = ProdukJenis::all();
        $produkCategory = ProdukCategory::all();

        // Ukuran hanya di-load kalau sudah pilih shape (khusus Rulon)
        $ukurans = $request->filled('shape_id')
            ? Ukuran::where('id_produk_jenis', $request->shape_id)->get()
            : collect();

        // Tentukan kategori yang dipilih & field config-nya
        $selectedCat = $request->filled('id_cat')
            ? ProdukCategory::find($request->id_cat)
            : null;

        $fieldConfig = $this->getFieldConfig($selectedCat);

        return view('admin.Produk.create', array_merge(
            compact('kode', 'kategori', 'environment', 'shapes', 'ukurans', 'produkCategory', 'selectedCat'),
            $fieldConfig
        ));
    }

    // =========================================================
    // STORE
    // =========================================================
    public function store(Request $request)
    {
        // Tentukan tipe kategori
        $selectedCat = $request->filled('id_cat')
            ? ProdukCategory::find($request->id_cat)
            : null;
        $fieldConfig = $this->getFieldConfig($selectedCat);
        $type        = $fieldConfig['categoryType'];

        // -------------------------------------------------------
        // Validation rules dasar (semua kategori)
        // -------------------------------------------------------
        $rules = [
            'nama_produk'  => 'required|string|max:255',
            'sku'          => 'required|string|max:255',
            'id_cat'       => 'required|exists:produk_category,id',
            'id_kat'       => 'nullable|exists:produk_kategori,id',
            'deskripsi'    => 'nullable|string',
            'merk'         => 'nullable|string|max:255',
            'tempratur'    => 'nullable|string|max:255',
            'status_aktif' => 'required|boolean',
            'eu1935'       => 'nullable|in:ya,tidak',
            'fda'          => 'nullable|in:ya,tidak',
            'usp'          => 'nullable|in:ya,tidak',
            'weight'       => 'nullable|numeric',
            'length'       => 'nullable|numeric',
            'width'        => 'nullable|numeric',
            'height'       => 'nullable|numeric',
        ];

        // -------------------------------------------------------
        // Rules khusus RULON
        // -------------------------------------------------------
        if ($type === 'rulon') {
            $rules['shape_id']        = 'required|exists:produk_jenis,id';
            $rules['id_ukuran']       = 'required|array|min:1';
            $rules['id_ukuran.*']     = 'exists:produk_ukuran,id';
            $rules['stok_variant']    = 'required|array|min:1';
            $rules['stok_variant.*']  = 'integer|min:0';
            $rules['harga_variant']   = 'required|array|min:1';
            $rules['harga_variant.*'] = 'numeric|min:0';
            // Spesifikasi teknis rulon
            $rules['id_environmant']  = 'nullable|exists:produk_envi,id';
            $rules['pressure']        = 'nullable|string|max:255';
            $rules['mating']          = 'nullable|string|max:255';
            $rules['max_pv']          = 'nullable|numeric|min:0';
            $rules['maximum_p']       = 'nullable|string|max:255';
            $rules['max_v']           = 'nullable|numeric|min:0';
            $rules['friction']        = 'nullable|string|max:255';
            $rules['elongation']      = 'nullable|string|max:255';
            $rules['deformation']     = 'nullable|numeric';
            $rules['tensile']         = 'nullable|numeric';
            $rules['spesific']        = 'nullable|numeric';
        }

        // -------------------------------------------------------
        // Rules khusus TYGON 3350
        // -------------------------------------------------------
        if ($type === 'tygon') {
            // Kategori ukuran
            $rules['tygon_size_category']       = 'required|in:tubing_inventory,metric';
            // Dimensi tube
            $rules['outer_diameter']            = 'nullable|numeric|min:0';
            $rules['inner_diameter']            = 'nullable|numeric|min:0';
            $rules['wall_thickness']            = 'nullable|numeric|min:0';
            $rules['min_bend_radius']           = 'nullable|numeric|min:0';
            $rules['tygon_length']              = 'nullable|numeric|min:0';
            // Stok & harga
            $rules['tygon_stok']                = 'nullable|integer|min:0';
            $rules['tygon_harga']               = 'nullable|numeric|min:0';
            // Working pressure — 2 suhu
            $rules['tygon_working_pressure_73'] = 'nullable|string|max:20';
            $rules['tygon_working_pressure_320'] = 'nullable|string|max:20';
            // Vacuum rating — 2 suhu
            $rules['tygon_vacuum_73']           = 'nullable|string|max:20';
            $rules['tygon_vacuum_320']          = 'nullable|string|max:20';
            // Spesifikasi teknis tygon
            $rules['tensile']                   = 'nullable|numeric';
            $rules['elongation']                = 'nullable|string|max:255';
            $rules['spesific']                  = 'nullable|numeric';
            $rules['id_environmant']            = 'nullable|exists:produk_envi,id';
        }

        // -------------------------------------------------------
        // Rules khusus TOP TAPE
        // -------------------------------------------------------
        if ($type === 'toptape') {
            $rules['tape_width']     = 'nullable|numeric|min:0';
            $rules['tape_thickness'] = 'nullable|numeric|min:0';
            $rules['tape_length']    = 'nullable|numeric|min:0';
            $rules['tape_color']     = 'nullable|string|max:100';
            $rules['tape_adhesive']  = 'nullable|string|max:100';
            $rules['tape_stok']      = 'nullable|integer|min:0';
            $rules['tape_harga']     = 'nullable|numeric|min:0';
            // Spesifikasi teknis tape
            $rules['tensile']        = 'nullable|numeric';
            $rules['elongation']     = 'nullable|string|max:255';
            $rules['spesific']       = 'nullable|numeric';
            $rules['friction']       = 'nullable|string|max:255';
        }

        $request->validate($rules);

        // -------------------------------------------------------
        // Upload gambar
        // -------------------------------------------------------
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file     = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('backend/assets/media/produk'), $filename);
            $gambarPath = $filename;
        }

        // -------------------------------------------------------
        // Data dasar (semua kategori)
        // -------------------------------------------------------
        $dataProduk = [
            'kode_produk'  => $this->generateKodeProduk(),
            'nama_produk'  => $request->nama_produk,
            'sku'          => $request->sku,
            'deskripsi'    => $request->deskripsi,
            'id_kat'       => $request->id_kat,
            'merk'         => $request->merk,
            'tempratur'    => $request->tempratur,
            'eu1935'       => $request->eu1935,
            'fda'          => $request->fda,
            'usp'          => $request->usp,
            'status_aktif' => $request->status_aktif,
            'gambar'       => $gambarPath,

        ];

        // -------------------------------------------------------
        // Data khusus RULON
        // -------------------------------------------------------
        if ($type === 'rulon') {
            $dataProduk['id_jenis']       = $request->shape_id;
            $dataProduk['id_environmant'] = $request->id_environmant;
            $dataProduk['pressure']       = $request->pressure;
            $dataProduk['mating']         = $request->mating;
            $dataProduk['max_pv']         = $request->max_pv;
            $dataProduk['maximum_p']      = $request->maximum_p;
            $dataProduk['max_v']          = $request->max_v;
            $dataProduk['friction']       = $request->friction;
            $dataProduk['elongation']     = $request->elongation;
            $dataProduk['deformation']    = $request->deformation;
            $dataProduk['tensile']        = $request->tensile;
            $dataProduk['spesific']       = $request->spesific;
        }

        // -------------------------------------------------------
        // Data khusus TYGON 3350
        // -------------------------------------------------------
        if ($type === 'tygon') {
            // Kategori ukuran (inches / mm)
            $dataProduk['tygon_size_category']        = $request->tygon_size_category;
            // Dimensi tube
            $dataProduk['outer_diameter']             = $request->outer_diameter;
            $dataProduk['inner_diameter']             = $request->inner_diameter;
            $dataProduk['wall_thickness']             = $request->wall_thickness;
            $dataProduk['min_bend_radius']            = $request->min_bend_radius;
            // Working pressure — 2 suhu
            $dataProduk['tygon_working_pressure_73']  = $request->tygon_working_pressure_73;
            $dataProduk['tygon_working_pressure_320'] = $request->tygon_working_pressure_320;
            // Vacuum rating — 2 suhu
            $dataProduk['tygon_vacuum_73']            = $request->tygon_vacuum_73;
            $dataProduk['tygon_vacuum_320']           = $request->tygon_vacuum_320;
            // Spesifikasi teknis
            $dataProduk['tensile']                    = $request->tensile;
            $dataProduk['elongation']                 = $request->elongation;
            $dataProduk['spesific']                   = $request->spesific;
            $dataProduk['id_environmant']             = $request->id_environmant;
        }

        // -------------------------------------------------------
        // Data khusus TOP TAPE
        // -------------------------------------------------------
        if ($type === 'toptape') {
            $dataProduk['friction']   = $request->friction;
            $dataProduk['elongation'] = $request->elongation;
            $dataProduk['tensile']    = $request->tensile;
            $dataProduk['spesific']   = $request->spesific;
            // TODO: tambah kolom tape_* ke tabel produk lalu uncomment:
            // $dataProduk['tape_width']     = $request->tape_width;
            // $dataProduk['tape_thickness'] = $request->tape_thickness;
            // $dataProduk['tape_length']    = $request->tape_length;
            // $dataProduk['tape_color']     = $request->tape_color;
            // $dataProduk['tape_adhesive']  = $request->tape_adhesive;
        }

        $produk = Produk::create($dataProduk);

        // -------------------------------------------------------
        // Simpan stok & harga — RULON (via produk_stok)
        // -------------------------------------------------------
        if ($type === 'rulon' && $request->has('id_ukuran')) {
            foreach ($request->id_ukuran as $i => $idUkuran) {
                if ($idUkuran) {
                    ProdukStok::create([
                        'id_produk' => $produk->id,
                        'id_jenis'  => $request->shape_id,
                        'id_ukuran' => $idUkuran,
                        'stok'      => $request->stok_variant[$i] ?? 0,
                        'harga'     => $request->harga_variant[$i] ?? 0,
                    ]);
                }
            }
        }

        // -------------------------------------------------------
        // Simpan stok & harga — TYGON (langsung ke kolom produk)
        // -------------------------------------------------------
        if ($type === 'tygon') {
            $produk->update([
                'stok'  => $request->tygon_stok  ?? 0,
                'harga' => $request->tygon_harga ?? 0,
            ]);
        }

        // -------------------------------------------------------
        // Simpan stok & harga — TOP TAPE (langsung ke kolom produk)
        // -------------------------------------------------------
        if ($type === 'toptape') {
            $produk->update([
                'stok'  => $request->tape_stok  ?? 0,
                'harga' => $request->tape_harga ?? 0,
            ]);
        }

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    // =========================================================
    // EDIT
    // =========================================================
    public function edit(Request $request, Produk $produk)
    {
        $kategori       = ProdukKategori::all();
        $environment    = ProdukEnvi::all();
        $shapes         = ProdukJenis::all();
        $produkCategory = ProdukCategory::all();

        $produk->load('variants.jenis', 'variants.ukuran');

        $shape_id_for_ukurans = $produk->id_jenis;
        if ($produk->variants->isNotEmpty()) {
            $shape_id_for_ukurans = $produk->variants->first()->id_jenis;
        }

        $ukurans = Ukuran::where('id_produk_jenis', $shape_id_for_ukurans)->get();

        // Tentukan field config berdasarkan category produk saat ini
        $selectedCat = $produk->id_cat
            ? ProdukCategory::find($produk->id_cat)
            : null;

        $fieldConfig = $this->getFieldConfig($selectedCat);

        return view('admin.Produk.edit', array_merge(
            compact('produk', 'kategori', 'environment', 'shapes', 'ukurans', 'produkCategory', 'selectedCat'),
            $fieldConfig
        ));
    }

    // =========================================================
    // SHOW (frontend)
    // =========================================================
    public function show($id)
    {
        $produk = Produk::with(['kategori', 'envi'])->findOrFail($id);
        return view('Frontend.produk_detail', compact('produk'));
    }

    // =========================================================
    // UPDATE
    // =========================================================
    public function update(Request $request, Produk $produk)
    {
        // Tentukan tipe kategori produk yang sedang diedit
        $selectedCat = $request->filled('id_cat')
            ? ProdukCategory::find($request->id_cat)
            : ($produk->id_cat ? ProdukCategory::find($produk->id_cat) : null);
        $fieldConfig = $this->getFieldConfig($selectedCat);
        $type        = $fieldConfig['categoryType'];

        // -------------------------------------------------------
        // Susun array variants dari request (khusus Rulon)
        // -------------------------------------------------------
        $variants = [];
        if ($request->has('id_ukuran')) {
            foreach ($request->id_ukuran as $key => $id_ukuran) {
                $variants[$key] = [
                    'id_ukuran' => $id_ukuran,
                    'sku'       => $request->sku_variant[$key]    ?? null,
                    'stok'      => $request->stok_variant[$key]   ?? null,
                    'harga'     => $request->harga_variant[$key]  ?? null,
                    'length'    => $request->length_variant[$key] ?? null,
                    'width'     => $request->width_variant[$key]  ?? null,
                    'height'    => $request->height_variant[$key] ?? null,
                    'weight'    => $request->weight_variant[$key] ?? null,
                ];
            }
        }
        $request->merge(['variants' => $variants]);

        // -------------------------------------------------------
        // Validation rules dasar
        // -------------------------------------------------------
        $rules = [
            'nama_produk'  => 'required|string|max:255',
            'sku'          => 'required|string|max:255',
            'status_aktif' => 'nullable|boolean',
            'id_kat'       => 'nullable|exists:produk_kategori,id',
            'deskripsi'    => 'nullable|string',
            'merk'         => 'nullable|string|max:255',
            'tempratur'    => 'nullable|string|max:255',
            'eu1935'       => 'nullable|in:ya,tidak',
            'fda'          => 'nullable|in:ya,tidak',
            'usp'          => 'nullable|in:ya,tidak',
            'weight'       => 'nullable|numeric',
            'length'       => 'nullable|numeric',
            'width'        => 'nullable|numeric',
            'height'       => 'nullable|numeric',
        ];

        // Rules khusus RULON
        if ($type === 'rulon') {
            $rules['id_kategori']            = 'required|exists:produk_kategori,id';
            $rules['id_shape']               = 'required|exists:produk_jenis,id';
            $rules['variants']               = 'nullable|array';
            $rules['variants.*.id_ukuran']   = 'required|exists:produk_ukuran,id';
            $rules['variants.*.sku']         = 'nullable|string|max:255';
            $rules['variants.*.stok']        = 'nullable|integer|min:0';
            $rules['variants.*.harga']       = 'nullable|numeric|min:0';
            $rules['variants.*.length']      = 'nullable|numeric';
            $rules['variants.*.width']       = 'nullable|numeric';
            $rules['variants.*.height']      = 'nullable|numeric';
            $rules['variants.*.weight']      = 'nullable|numeric';
            // Spesifikasi teknis rulon
            $rules['id_environmant']         = 'nullable|exists:produk_envi,id';
            $rules['pressure']               = 'nullable|string|max:255';
            $rules['mating']                 = 'nullable|string|max:255';
            $rules['max_pv']                 = 'nullable|numeric|min:0';
            $rules['maximum_p']              = 'nullable|string|max:255';
            $rules['max_v']                  = 'nullable|numeric|min:0';
            $rules['friction']               = 'nullable|string|max:255';
            $rules['elongation']             = 'nullable|string|max:255';
            $rules['deformation']            = 'nullable|numeric';
            $rules['tensile']                = 'nullable|numeric';
            $rules['spesific']               = 'nullable|numeric';
        }

        // Rules khusus TYGON 3350
        if ($type === 'tygon') {
            $rules['tygon_size_category']        = 'required|in:tubing_inventory,metric';
            $rules['outer_diameter']             = 'nullable|numeric|min:0';
            $rules['inner_diameter']             = 'nullable|numeric|min:0';
            $rules['wall_thickness']             = 'nullable|numeric|min:0';
            $rules['min_bend_radius']            = 'nullable|numeric|min:0';
            $rules['tygon_length']               = 'nullable|numeric|min:0';
            $rules['tygon_stok']                 = 'nullable|integer|min:0';
            $rules['tygon_harga']                = 'nullable|numeric|min:0';
            $rules['tygon_working_pressure_73']  = 'nullable|string|max:20';
            $rules['tygon_working_pressure_320'] = 'nullable|string|max:20';
            $rules['tygon_vacuum_73']            = 'nullable|string|max:20';
            $rules['tygon_vacuum_320']           = 'nullable|string|max:20';
            $rules['tensile']                    = 'nullable|numeric';
            $rules['elongation']                 = 'nullable|string|max:255';
            $rules['spesific']                   = 'nullable|numeric';
            $rules['id_environmant']             = 'nullable|exists:produk_envi,id';
        }

        // Rules khusus TOP TAPE
        if ($type === 'toptape') {
            $rules['tape_width']     = 'nullable|numeric|min:0';
            $rules['tape_thickness'] = 'nullable|numeric|min:0';
            $rules['tape_length']    = 'nullable|numeric|min:0';
            $rules['tape_color']     = 'nullable|string|max:100';
            $rules['tape_adhesive']  = 'nullable|string|max:100';
            $rules['tape_stok']      = 'nullable|integer|min:0';
            $rules['tape_harga']     = 'nullable|numeric|min:0';
            $rules['tensile']        = 'nullable|numeric';
            $rules['elongation']     = 'nullable|string|max:255';
            $rules['spesific']       = 'nullable|numeric';
            $rules['friction']       = 'nullable|string|max:255';
        }

        if ($request->hasFile('gambar')) {
            $rules['gambar'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        $request->validate($rules);

        // -------------------------------------------------------
        // Handle upload gambar baru
        // -------------------------------------------------------
        if ($request->hasFile('gambar')) {
            $gambar   = $request->file('gambar');
            $namaFile = time() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move(public_path('backend/assets/media/produk'), $namaFile);

            if ($produk->gambar && file_exists(public_path('backend/assets/media/produk/' . $produk->gambar))) {
                unlink(public_path('backend/assets/media/produk/' . $produk->gambar));
            }

            $produk->gambar = $namaFile;
        }

        // -------------------------------------------------------
        // Data dasar (semua kategori)
        // -------------------------------------------------------
        $produk->nama_produk  = $request->nama_produk;
        $produk->sku          = $request->sku;
        $produk->deskripsi    = $request->deskripsi;
        $produk->id_kat       = $request->id_kat       ?? $request->id_kategori;
        $produk->merk         = $request->merk;
        $produk->tempratur    = $request->tempratur;
        $produk->eu1935       = $request->eu1935;
        $produk->fda          = $request->fda;
        $produk->usp          = $request->usp;
        $produk->status_aktif = $request->status_aktif;
        $produk->weight       = $request->weight;
        $produk->length       = $request->length;
        $produk->width        = $request->width;
        $produk->height       = $request->height;

        // -------------------------------------------------------
        // Data khusus RULON
        // -------------------------------------------------------
        if ($type === 'rulon') {
            $produk->id_jenis       = $request->id_shape;
            $produk->id_environmant = $request->id_environmant;
            $produk->pressure       = $request->pressure;
            $produk->mating         = $request->mating;
            $produk->max_pv         = $request->max_pv;
            $produk->maximum_p      = $request->maximum_p;
            $produk->max_v          = $request->max_v;
            $produk->friction       = $request->friction;
            $produk->elongation     = $request->elongation;
            $produk->deformation    = $request->deformation;
            $produk->tensile        = $request->tensile;
            $produk->spesific       = $request->spesific;
        }

        // -------------------------------------------------------
        // Data khusus TYGON 3350
        // -------------------------------------------------------
        if ($type === 'tygon') {
            $produk->tygon_size_category        = $request->tygon_size_category;
            $produk->outer_diameter             = $request->outer_diameter;
            $produk->inner_diameter             = $request->inner_diameter;
            $produk->wall_thickness             = $request->wall_thickness;
            $produk->min_bend_radius            = $request->min_bend_radius;
            $produk->tygon_working_pressure_73  = $request->tygon_working_pressure_73;
            $produk->tygon_working_pressure_320 = $request->tygon_working_pressure_320;
            $produk->tygon_vacuum_73            = $request->tygon_vacuum_73;
            $produk->tygon_vacuum_320           = $request->tygon_vacuum_320;
            $produk->tensile                    = $request->tensile;
            $produk->elongation                 = $request->elongation;
            $produk->spesific                   = $request->spesific;
            $produk->id_environmant             = $request->id_environmant;
            $produk->stok                       = $request->tygon_stok  ?? $produk->stok;
            $produk->harga                      = $request->tygon_harga ?? $produk->harga;
        }

        // -------------------------------------------------------
        // Data khusus TOP TAPE
        // -------------------------------------------------------
        if ($type === 'toptape') {
            $produk->friction   = $request->friction;
            $produk->elongation = $request->elongation;
            $produk->tensile    = $request->tensile;
            $produk->spesific   = $request->spesific;
            $produk->stok       = $request->tape_stok  ?? $produk->stok;
            $produk->harga      = $request->tape_harga ?? $produk->harga;
            // TODO: uncomment setelah kolom tape_* ditambahkan ke tabel produk
            // $produk->tape_width     = $request->tape_width;
            // $produk->tape_thickness = $request->tape_thickness;
            // $produk->tape_length    = $request->tape_length;
            // $produk->tape_color     = $request->tape_color;
            // $produk->tape_adhesive  = $request->tape_adhesive;
        }

        $produk->save();

        // -------------------------------------------------------
        // Update variants produk_stok (hanya RULON)
        // -------------------------------------------------------
        if ($type === 'rulon') {
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
        }

        return redirect()->route('admin.produk.index')
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

        $produk->delete();

        Log::info('Product ID: ' . $id . ' deleted successfully.');

        Log::info('Auth check before redirect: ' . (Auth::check() ? 'Authenticated' : 'Not Authenticated'));

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
}
