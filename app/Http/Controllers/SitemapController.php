<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SitemapController extends Controller
{
    public function index()
    {
        // Ambil data produk. Pastikan nama kolom sudah sesuai.
        $products = DB::table('produk')->orderBy('updated_at', 'desc')->get(['slug', 'updated_at']);

        // Ambil data artikel.
        $articles = DB::table('articles')->orderBy('updated_at', 'desc')->get(['judul', 'updated_at']);

        // Halaman statis
        $staticPages = [
            ['url' => url('/'), 'lastmod' => Carbon::now()->subDays(1), 'priority' => '1.0'],
            ['url' => url('/frontend/produk'), 'lastmod' => Carbon::now()->subDays(7), 'priority' => '0.9'],
            ['url' => url('/frontend/white-paper'), 'lastmod' => Carbon::parse('2024-01-01'), 'priority' => '0.7'],
            ['url' => url('/frontend/brochure'), 'lastmod' => Carbon::parse('2024-01-01'), 'priority' => '0.7'],
        ];

        return response()->view('frontend.sitemap', [
            'products' => $products,
            'articles' => $articles,
            'staticPages' => $staticPages,
        ])->header('Content-Type', 'text/xml');
    }
}
