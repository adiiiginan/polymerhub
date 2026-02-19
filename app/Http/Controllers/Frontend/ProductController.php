<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProdukEnvi;
use App\Models\ProdukJenis;
use App\Models\ProdukStok;

use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $locale = $request->segment(1);

        // Start with a base query
        $query = Produk::query();

        // Filter by Category
        if ($request->has('category')) {
            $query->whereIn('id_kat', $request->category);
        }

        // Filter by Mating Surface Hardness
        if ($request->has('mating_surface_hardness')) {
            $query->whereIn('mating', $request->mating_surface_hardness);
        }

        // Filter by Pressure
        if ($request->has('pressure')) {
            $query->whereIn('maximum_p', $request->pressure);
        }

        $produk = $query->get()->unique('nama_produk');

        // Get filter options
        $categories = Produk::select('id_kat')->distinct()->get();
        $matings = Produk::select('mating')->distinct()->get();
        $pressures = Produk::select('maximum_p')->distinct()->get();


        $view_data = compact('produk', 'categories', 'matings', 'pressures');

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
            // Ensure both relationships and their required properties exist
            if (!$variant->jenis || !$variant->ukuran || !$variant->ukuran->nama_ukuran) {
                return null;
            }
            return [
                'id' => $variant->id,
                'jenis_id' => $variant->id_jenis,
                'ukuran_id' => $variant->id_ukuran,
                'dimensi' => $variant->ukuran->nama_ukuran,
                'stok' => $variant->stok,
                'hargi' => $variant->hargi,
                'harga' => $variant->harga,
                'gambar' => $variant->gambar ?? optional($variant->produk)->gambar,
            ];
        })->filter()->values();

        // Get related products
        $related_products = Produk::where('id_kat', $produk->id_kat)
            ->where('id', '!=', $id)
            ->take(5)
            ->get();

        $view_data = compact(
            'produk',
            'related_products',
            'jenis_unik',
            'variants_data'
        );

        if (view()->exists($locale . '.frontend.show')) {
            return view($locale . '.frontend.show', $view_data);
        }

        return view(
            'frontend.show',
            $view_data
        );
    }

    public function getUkurans(Request $request)
    {
        $id_jenis = $request->query('id_jenis');
        $id_produk = $request->query('id_produk');

        if (!$id_jenis || !$id_produk) {
            return response()->json([], 400);
        }

        $jenis = ProdukJenis::find($id_jenis);
        if (!$jenis) {
            return response()->json([], 404);
        }

        // Ambil produk untuk mendapatkan nama
        $produk = Produk::find($id_produk);
        if (!$produk) {
            return response()->json([], 404);
        }

        // Ambil semua produk dengan nama yang sama
        $productIds = Produk::where('nama_produk', $produk->nama_produk)->pluck('id');

        // Ambil varian dengan stok > 0
        $variants = ProdukStok::whereIn('id_produk', $productIds)
            ->where('id_jenis', $id_jenis)
            ->where('stok', '>', 0)
            ->with(['ukuran'])
            ->get()
            ->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'dimensi' => $variant->ukuran->nama_ukuran,
                    'stok' => $variant->stok,
                    'hargi' => $variant->hargi,
                    'harga' => $variant->harga,
                    'gambar' => $variant->gambar,
                ];
            });

        return response()->json($variants);
    }

    public function getStock(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|integer',
            'id_jenis' => 'required|integer',
            'id_ukuran' => 'required|integer',
        ]);

        // Find the product to get its name
        $produk = Produk::find($request->id_produk);
        if (!$produk) {
            return response()->json(['stok' => 0]);
        }

        // Find all product IDs with the same name
        $productIds = Produk::where('nama_produk', $produk->nama_produk)->pluck('id');

        if ($productIds->isEmpty()) {
            return response()->json(['stok' => 0]);
        }

        // Sum the stock for the given variant across all those products
        $totalStock = ProdukStok::whereIn('id_produk', $productIds)
            ->where('id_jenis', $request->id_jenis)
            ->where('id_ukuran', $request->id_ukuran)
            ->sum('stok');

        return response()->json([
            'stok' => $totalStock ?? 0
        ]);
    }
}
