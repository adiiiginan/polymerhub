@extends('id.layouts.app')

@section('content')
    <div class="max-w-screen-m mx-auto px-4 sm:px-6 lg:px-10 py-6">

        {{-- ============================================================
         HERO SECTION
    ============================================================ --}}
        <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl overflow-hidden mb-6"
            style="min-height: 220px;">
            {{-- Decorative background pattern --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <svg class="absolute right-0 top-0 h-full opacity-10" viewBox="0 0 600 300"
                    preserveAspectRatio="xMaxYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="500" cy="150" r="200" fill="white" />
                    <circle cx="420" cy="50" r="120" fill="white" />
                    <circle cx="580" cy="260" r="100" fill="white" />
                </svg>
                {{-- Grid lines --}}
                <svg class="absolute inset-0 w-full h-full opacity-5" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <div class="relative z-10 flex items-stretch">
                {{-- Left Content --}}
                <div class="flex-1 p-8 pb-6">
                    <p class="text-blue-300 text-xs font-bold uppercase tracking-widest mb-2">KATEGORI PRODUK</p>
                    <h1 class="text-5xl font-extrabold text-white mb-3 leading-tight">SG - 25</h1>
                    <p class="text-blue-100 text-base max-w-md leading-relaxed mb-6">
                        Material packing dan sealing berkualitas tinggi yang dirancang untuk meningkatkan efisiensi
                        operasional serta menjaga keandalan sistem industri.
                    </p>

                    {{-- Feature Badges --}}
                    <div class="flex flex-wrap gap-3">
                        <div
                            class="flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-3 py-2">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-bold text-xs leading-tight">Kualitas Premium</p>
                                <p class="text-blue-200 text-xs leading-tight">Material terbaik</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-3 py-2">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-bold text-xs leading-tight">Tahan Suhu Tinggi</p>
                                <p class="text-blue-200 text-xs leading-tight">Hingga 260°C</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-3 py-2">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-bold text-xs leading-tight">ISO Certified</p>
                                <p class="text-blue-200 text-xs leading-tight">Standar Internasional</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-3 py-2">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-bold text-xs leading-tight">Aplikasi Luas</p>
                                <p class="text-blue-200 text-xs leading-tight">Industri & Packaging</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Product Image --}}
                <div class="hidden lg:flex items-center justify-end pr-8 w-auto flex-shrink-0">
                    <img src="{{ asset('backend/assets/media/hero/sg25-hero.png.png') }}" alt="SG-25 Products"
                        class="max-h-80 w-auto object-contain drop-shadow-2xl" onerror="this.style.display='none'">
                </div>
            </div>
        </div>

        {{-- ============================================================
         SEARCH BAR
    ============================================================ --}}


        {{-- ============================================================
         MAIN LAYOUT: SIDEBAR + PRODUCTS
    ============================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            {{-- ============================================================
             SIDEBAR FILTER
        ============================================================ --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-4 overflow-hidden">
                    {{-- Filter Header --}}
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <h3 class="text-base font-bold text-gray-900">Filter Produk</h3>
                        <button class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 7a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('id.frontend.category.SG-25') }}" method="GET" id="filterForm">
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if (request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <div class="px-5 py-4 space-y-1">

                            {{-- Filter Group: Mating Surface Hardness --}}
                            <div class="filter-group" x-data="{ open: true }">
                                <button type="button"
                                    class="w-full flex items-center justify-between py-3 border-b border-gray-100 group"
                                    onclick="toggleFilterGroup(this)">
                                    <span class="font-semibold text-gray-800 text-sm">Mating Surface Hardness</span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 chevron-icon"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="filter-options pt-2 pb-1 space-y-0.5">
                                    @foreach ($matings ?? [] as $mating)
                                        @php
                                            $count = $mating->products_count ?? 0;
                                        @endphp
                                        <label
                                            class="flex items-center justify-between py-2 px-1 rounded-lg hover:bg-gray-50 cursor-pointer group/item transition">
                                            <div class="flex items-center gap-2.5">
                                                <input type="checkbox" name="mating_surface_hardness[]"
                                                    value="{{ $mating->mating }}"
                                                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0"
                                                    {{ in_array($mating->mating, request('mating_surface_hardness', [])) ? 'checked' : '' }}
                                                    onchange="document.getElementById('filterForm').submit()">
                                                <span
                                                    class="text-sm text-gray-600 group-hover/item:text-gray-900 transition">{{ $mating->mating }}</span>
                                            </div>
                                            @if ($count > 0)
                                                <span
                                                    class="text-xs text-gray-400 font-medium bg-gray-100 rounded-full px-2 py-0.5 min-w-[28px] text-center">{{ $count }}</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Filter Group: Pressure --}}
                            <div class="filter-group mt-2">
                                <button type="button"
                                    class="w-full flex items-center justify-between py-3 border-b border-gray-100"
                                    onclick="toggleFilterGroup(this)">
                                    <span class="font-semibold text-gray-800 text-sm">Pressure — Max PV</span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 chevron-icon"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="filter-options pt-2 pb-1 space-y-0.5">
                                    @foreach ($pressures ?? [] as $pressure)
                                        @php
                                            $count = $pressure->products_count ?? 0;
                                        @endphp
                                        <label
                                            class="flex items-center justify-between py-2 px-1 rounded-lg hover:bg-gray-50 cursor-pointer group/item transition">
                                            <div class="flex items-center gap-2.5">
                                                <input type="checkbox" name="pressure[]"
                                                    value="{{ $pressure->maximum_p }}"
                                                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0"
                                                    {{ in_array($pressure->maximum_p, request('pressure', [])) ? 'checked' : '' }}
                                                    onchange="document.getElementById('filterForm').submit()">
                                                <span
                                                    class="text-sm text-gray-600 group-hover/item:text-gray-900 transition">{{ $pressure->maximum_p }}</span>
                                            </div>
                                            @if ($count > 0)
                                                <span
                                                    class="text-xs text-gray-400 font-medium bg-gray-100 rounded-full px-2 py-0.5 min-w-[28px] text-center">{{ $count }}</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Filter Action Buttons --}}
                        <div class="px-5 pb-5 pt-2 space-y-2">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-2.5 rounded-lg flex items-center justify-center gap-2 transition text-sm shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Terapkan Filter
                            </button>
                            <a href="{{ route('id.frontend.category.SG-25') }}"
                                class="w-full border border-gray-200 hover:border-gray-300 text-gray-600 hover:bg-gray-50 font-medium py-2.5 rounded-lg flex items-center justify-center gap-2 transition text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Bersihkan Filter
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ============================================================
             PRODUCTS GRID
        ============================================================ --}}
            <div class="lg:col-span-3">
                {{-- Results count --}}
                @if ($produk->total() > 0)
                    <p class="text-sm text-gray-500 mb-5">
                        Menampilkan
                        <span class="font-semibold text-gray-800">{{ $produk->firstItem() }} -
                            {{ $produk->lastItem() }}</span>
                        dari
                        <span class="font-semibold text-gray-800">{{ number_format($produk->total()) }}</span>
                        produk
                    </p>
                @endif

                {{-- Grid / List View --}}
                <div id="productContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @forelse ($produk as $item)
                        {{-- ===== GRID CARD ===== --}}
                        <div
                            class="product-card bg-white rounded-xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col">

                            {{-- Product Image --}}
                            <a href="{{ route('id.frontend.produk.show', $item->id) }}" class="block">
                                <div class="relative bg-gray-50 aspect-square overflow-hidden group">
                                    <img src="{{ asset('backend/assets/media/produk/' . $item->gambar) }}"
                                        alt="{{ $item->nama_produk }}"
                                        class="w-full h-full object-contain p-6 group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy">

                                    {{-- Brand Badge --}}
                                    @if ($item->merk)
                                        <div
                                            class="absolute top-3 right-3 bg-yellow-400 text-gray-900 text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">
                                            {{ $item->merk }}
                                        </div>
                                    @endif
                                </div>
                            </a>

                            {{-- Card Body --}}
                            <div class="p-4 flex flex-col flex-grow">
                                {{-- Category Label --}}
                                @if ($item->kategori)
                                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">
                                        {{ $item->kategori->kategori }}
                                    </p>
                                @else
                                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">SPESIAL</p>
                                @endif

                                {{-- Product Name --}}
                                <h3 class="font-bold text-gray-900 text-sm leading-snug mb-2 flex-grow">
                                    <a href="{{ route('id.frontend.produk.show', $item->id) }}"
                                        class="hover:text-blue-600 transition line-clamp-2">{{ $item->nama_produk }}</a>
                                </h3>

                                {{-- Description --}}
                                <p class="text-xs text-gray-500 mb-4 line-clamp-2 leading-relaxed">
                                    {{ Str::limit($item->deskripsi, 90) }}
                                </p>

                                {{-- Detail Button --}}
                                <a href="{{ route('id.frontend.category.produksg', $item->id) }}"
                                    class="mt-auto pt-3 border-t border-gray-100 text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center justify-center gap-1.5 hover:gap-2.5 transition-all group">
                                    Lihat Detail
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-16">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">Tidak ada produk yang ditemukan.</p>
                            <p class="text-gray-400 text-sm mt-1">Coba ubah filter atau kata kunci pencarian Anda.</p>
                            <a href="{{ route('id.frontend.category.SG-25') }}"
                                class="inline-flex items-center gap-2 mt-4 text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset semua filter
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- ============================================================
                 PAGINATION
            ============================================================ --}}
                @if ($produk->hasPages())
                    <div class="mt-10 flex justify-center">
                        <nav class="flex items-center gap-1" aria-label="Pagination">
                            {{-- Previous --}}
                            @if ($produk->onFirstPage())
                                <span
                                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $produk->previousPageUrl() }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @endif

                            {{-- Page Numbers --}}
                            @foreach ($produk->getUrlRange(1, $produk->lastPage()) as $page => $url)
                                @if ($page == $produk->currentPage())
                                    <span
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-600 text-white text-sm font-semibold shadow-sm">
                                        {{ $page }}
                                    </span>
                                @elseif ($page == 1 || $page == $produk->lastPage() || abs($page - $produk->currentPage()) <= 1)
                                    <a href="{{ $url }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 text-sm hover:bg-gray-50 hover:border-gray-300 transition">
                                        {{ $page }}
                                    </a>
                                @elseif (abs($page - $produk->currentPage()) == 2)
                                    <span class="w-9 h-9 flex items-center justify-center text-gray-400 text-sm">…</span>
                                @endif
                            @endforeach

                            {{-- Next --}}
                            @if ($produk->hasMorePages())
                                <a href="{{ $produk->nextPageUrl() }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <span
                                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                @endif
            </div>
        </div>

        {{-- ============================================================
         JAVASCRIPT
    ============================================================ --}}
        <script>
            // ---- View Toggle (Grid / List) ----
            function setView(mode) {
                const container = document.getElementById('productContainer');
                const btnGrid = document.getElementById('btnGrid');
                const btnList = document.getElementById('btnList');
                const cards = container.querySelectorAll('.product-card');

                if (mode === 'list') {
                    container.classList.remove('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3');
                    container.classList.add('grid-cols-1');
                    cards.forEach(card => {
                        card.classList.add('flex-row', 'max-h-40');
                        card.querySelector('a.block')?.classList.add('w-40', 'flex-shrink-0');
                    });
                    btnList.classList.add('bg-blue-600', 'text-white');
                    btnList.classList.remove('text-gray-500');
                    btnGrid.classList.remove('bg-blue-600', 'text-white');
                    btnGrid.classList.add('text-gray-500');
                    localStorage.setItem('productView', 'list');
                } else {
                    container.classList.add('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3');
                    cards.forEach(card => {
                        card.classList.remove('flex-row', 'max-h-40');
                        card.querySelector('a.block')?.classList.remove('w-40', 'flex-shrink-0');
                    });
                    btnGrid.classList.add('bg-blue-600', 'text-white');
                    btnGrid.classList.remove('text-gray-500');
                    btnList.classList.remove('bg-blue-600', 'text-white');
                    btnList.classList.add('text-gray-500');
                    localStorage.setItem('productView', 'grid');
                }
            }

            // Restore saved view on page load
            document.addEventListener('DOMContentLoaded', () => {
                const saved = localStorage.getItem('productView');
                if (saved === 'list') setView('list');
            });

            // ---- Filter Group Toggle ----
            function toggleFilterGroup(btn) {
                const group = btn.closest('.filter-group');
                const options = group.querySelector('.filter-options');
                const chevron = btn.querySelector('.chevron-icon');

                if (options.style.display === 'none') {
                    options.style.display = '';
                    chevron.style.transform = 'rotate(0deg)';
                } else {
                    options.style.display = 'none';
                    chevron.style.transform = 'rotate(-90deg)';
                }
            }
        </script>

        <style>
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>

    </div>{{-- end max-w-screen-xl wrapper --}}
@endsection
