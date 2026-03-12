<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Halaman Statis --}}
    @foreach ($staticPages as $page)
        <url>
            <loc>{{ $page['url'] }}</loc>
            <lastmod>{{ $page['lastmod']->toAtomString() }}</lastmod>
            <priority>{{ $page['priority'] }}</priority>
        </url>
    @endforeach

    {{-- Halaman Produk --}}
    @foreach ($products as $product)
        <url>
            <loc>{{ url('/product/' . $product->slug) }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($product->updated_at)->toAtomString() }}</lastmod>
            <priority>0.9</priority>
        </url>
    @endforeach

    {{-- Halaman Artikel --}}
    @foreach ($articles as $article)
        <url>
            <loc>{{ url('/frontend/article/' . $article->judul) }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($article->updated_at)->toAtomString() }}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>
