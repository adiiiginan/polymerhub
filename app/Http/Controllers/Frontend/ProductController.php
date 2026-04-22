<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProdukCategory;
use App\Models\ProdukKategori;
use App\Models\ProdukEnvi;
use App\Models\ProdukJenis;
use App\Models\ProdukStok;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->segment(1);
        $query = Produk::with('kategori');
        $activeCategory = null;

        // Filter by Category
        if ($request->has('category')) {
            $categoryId = $request->category;
            $query->where('id_kat', $categoryId);
            $activeCategory = ProdukKategori::find($categoryId);
        }

        // Filter by Mating Surface Hardness
        if ($request->has('mating_surface_hardness')) {
            $query->whereIn('mating', $request->mating_surface_hardness);
        }

        // Filter by Pressure
        if ($request->has('pressure')) {
            $query->whereIn('maximum_p', $request->pressure);
        }

        $produk = $query->groupBy('nama_produk')->paginate(12);

        // Get filter options
        $categories = ProdukKategori::all();
        $matings    = Produk::select('mating')->distinct()->whereNotNull('mating')->get();
        $pressures  = Produk::select('maximum_p')->distinct()->whereNotNull('maximum_p')->get();

        $view_data = compact('produk', 'categories', 'matings', 'pressures', 'activeCategory');

        // Cek view locale-specific dulu, fallback ke generic
        if (view()->exists($locale . '.frontend.produk')) {
            return view($locale . '.frontend.produk', $view_data);
        }

        return view('frontend.produk', $view_data);
    }

    public function show(Request $request, $id)
    {
        $locale = $request->segment(1);
        $produk = Produk::findOrFail($id);
        $productIds = Produk::where('nama_produk', $produk->nama_produk)->pluck('id');

        // Get all variants for the product, ensuring they have valid dimensions
        $all_variants = ProdukStok::whereIn('id_produk', $productIds)
            ->with(['jenis', 'ukuran'])
            ->whereHas('jenis')
            ->whereHas('ukuran', function ($q) {
                $q->whereNotNull('nama_ukuran')->where('nama_ukuran', '!=', '');
            })
            ->get();

        // Get unique shapes that have at least one valid variant
        $jenis_unik = ProdukJenis::whereHas('stoks', function ($query) use ($productIds) {
            $query->whereIn('id_produk', $productIds)
                ->whereHas('ukuran', function ($q) {
                    $q->whereNotNull('nama_ukuran')->where('nama_ukuran', '!=', '');
                });
        })->get();

        // Create variations for JavaScript
        $variants_data = $all_variants->map(function ($variant) {
            if (!$variant->jenis || !$variant->ukuran || !$variant->ukuran->nama_ukuran) {
                return null;
            }
            return [
                'id'       => $variant->id,
                'jenis_id' => $variant->id_jenis,
                'ukuran_id' => $variant->id_ukuran,
                'dimensi'  => $variant->ukuran->nama_ukuran,
                'stok'     => $variant->stok,
                'hargi'    => $variant->hargi,
                'harga'    => $variant->harga,
                'gambar'   => $variant->gambar ?? optional($variant->produk)->gambar,
            ];
        })->filter()->values();

        $view_data = compact('produk', 'jenis_unik', 'variants_data');

        if (view()->exists($locale . '.frontend.show')) {
            return view($locale . '.frontend.show', $view_data);
        }

        return view('frontend.show', $view_data);
    }

    public function getUkurans(Request $request)
    {
        $id_jenis  = $request->query('id_jenis');
        $id_produk = $request->query('id_produk');

        if (!$id_jenis || !$id_produk) {
            return response()->json([], 400);
        }

        $jenis = ProdukJenis::find($id_jenis);
        if (!$jenis) return response()->json([], 404);

        $produk = Produk::find($id_produk);
        if (!$produk) return response()->json([], 404);

        $productIds = Produk::where('nama_produk', $produk->nama_produk)->pluck('id');

        $variants = ProdukStok::whereIn('id_produk', $productIds)
            ->where('id_jenis', $id_jenis)
            ->where('stok', '>', 0)
            ->with(['ukuran'])
            ->get()
            ->map(fn($v) => [
                'id'      => $v->id,
                'dimensi' => $v->ukuran->nama_ukuran,
                'stok'    => $v->stok,
                'hargi'   => $v->hargi,
                'harga'   => $v->harga,
                'gambar'  => $v->gambar,
            ]);

        return response()->json($variants);
    }

    public function getStock(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|integer',
            'id_jenis'  => 'required|integer',
            'id_ukuran' => 'required|integer',
        ]);

        $produk = Produk::find($request->id_produk);
        if (!$produk) return response()->json(['stok' => 0]);

        $productIds = Produk::where('nama_produk', $produk->nama_produk)->pluck('id');
        if ($productIds->isEmpty()) return response()->json(['stok' => 0]);

        $totalStock = ProdukStok::whereIn('id_produk', $productIds)
            ->where('id_jenis', $request->id_jenis)
            ->where('id_ukuran', $request->id_ukuran)
            ->sum('stok');

        return response()->json(['stok' => $totalStock ?? 0]);
    }

    public function showTygon(Request $request)
    {
        $locale = $request->segment(1);

        $activeCategory = ProdukCategory::where('category', 'LIKE', '%tygon%')->firstOrFail();

        // ✅ Query pakai id_cat, bukan id_kat
        $query = Produk::where('id_cat', $activeCategory->id);

        if ($request->filled('mating_surface_hardness')) {
            $query->whereIn('mating', $request->mating_surface_hardness);
        }

        if ($request->filled('pressure')) {
            $query->whereIn('maximum_p', $request->pressure);
        }

        $produk = $query->paginate(12);

        $matings = Produk::where('id_cat', $activeCategory->id)
            ->select('mating')->distinct()->whereNotNull('mating')->get();

        $pressures = Produk::where('id_cat', $activeCategory->id)
            ->select('maximum_p')->distinct()->whereNotNull('maximum_p')->get();

        return view($locale . '.frontend.category.tygon', compact(
            'produk',
            'activeCategory',
            'matings',
            'pressures',
            'locale'
        ));
    }
    public function showTopTape(Request $request)
    {
        $locale = $request->segment(1);
        $activeCategory = ProdukCategory::where('category', 'top tape')->firstOrFail();

        $produk = Produk::with('category')->where('id_kat', $activeCategory->id)->groupBy('nama_produk')->paginate(12);

        return view($locale . '.frontend.category.toptape', compact('produk', 'activeCategory'));
    }
}
