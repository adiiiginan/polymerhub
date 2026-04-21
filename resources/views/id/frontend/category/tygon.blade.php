@extends('id.layouts.app')

@section('title', 'Tygon Tubing — Produk')

@section('content')

    <div style="background:red;color:white;padding:1rem;">
        DEBUG: produk count = {{ $produk->total() }} |
        activeCategory = {{ $activeCategory->id ?? 'NULL' }} |
        category = {{ $activeCategory->category ?? 'NULL' }}
    </div>
    <div style="--main-color: #1A365D; --accent-color: #5DADE2; --accent-light: #EBF5FB;">

        {{-- Hero Banner --}}
        <div
            style="background: var(--main-color); padding: 3.5rem 1.5rem; text-align: center; position: relative; overflow: hidden;">
            <div
                style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; border-radius: 50%; background: var(--accent-color); opacity: 0.1;">
            </div>
            <div
                style="position: absolute; bottom: -40px; left: -40px; width: 150px; height: 150px; border-radius: 50%; background: var(--accent-color); opacity: 0.07;">
            </div>

            <span
                style="display: inline-block; background: var(--accent-color); color: #fff; font-size: 11px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; padding: 4px 14px; border-radius: 999px; margin-bottom: 1rem;">
                Kategori Produk
            </span>
            <h1 style="color: #fff; font-size: 2.5rem; font-weight: 700; margin-bottom: 0.75rem; position: relative;">
                {{ $activeCategory->kategori ?? 'Tygon Tubing' }}
            </h1>
            <p
                style="color: rgba(255,255,255,0.7); font-size: 1rem; max-width: 500px; margin: 0 auto; line-height: 1.7; position: relative;">
                Selang industri berkualitas tinggi untuk kebutuhan laboratorium, medis, dan manufaktur.
            </p>
        </div>

        {{-- Main Content --}}
        <div style="background: #F3F4F6; padding: 2rem 1.5rem; min-height: 600px;">
            <div style="max-width: 1280px; margin: 0 auto;">

                {{-- Breadcrumb --}}
                <nav
                    style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #6B7280; margin-bottom: 1.5rem;">
                    <a href="{{ route('id.home') }}" style="color: var(--main-color); text-decoration: none;">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('id.frontend.produk') }}"
                        style="color: var(--main-color); text-decoration: none;">Produk</a>
                    <span>/</span>
                    <span style="color: #374151;">{{ $activeCategory->kategori ?? 'Tygon' }}</span>
                </nav>

                <div style="display: grid; grid-template-columns: 220px 1fr; gap: 1.5rem; align-items: start;">

                    {{-- Sidebar Filter --}}
                    <aside
                        style="background: #fff; border-radius: 12px; border: 0.5px solid #E5E7EB; padding: 1.25rem; position: sticky; top: 1rem;">
                        <p
                            style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1rem;">
                            Filter Produk</p>

                        <form action="{{ route('id.frontend.category.tygon') }}" method="GET">

                            {{-- Filter: Mating Surface Hardness --}}
                            <div style="margin-bottom: 1.25rem;">
                                <p style="font-size: 13px; font-weight: 600; color: #1F2937; margin-bottom: 0.6rem;">Mating
                                    Surface Hardness</p>
                                @foreach ($matings ?? [] as $mating)
                                    <label
                                        style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #6B7280; padding: 3px 0; cursor: pointer;">
                                        <input type="checkbox" name="mating_surface_hardness[]"
                                            value="{{ $mating->mating }}" style="accent-color: var(--accent-color);"
                                            {{ in_array($mating->mating, request('mating_surface_hardness', [])) ? 'checked' : '' }}>
                                        {{ $mating->mating }}
                                    </label>
                                @endforeach
                            </div>

                            <hr style="border: none; border-top: 0.5px solid #E5E7EB; margin: 1rem 0;">

                            {{-- Filter: Pressure --}}
                            <div style="margin-bottom: 1.25rem;">
                                <p style="font-size: 13px; font-weight: 600; color: #1F2937; margin-bottom: 0.6rem;">
                                    Pressure — Max PV</p>
                                @foreach ($pressures ?? [] as $pressure)
                                    <label
                                        style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #6B7280; padding: 3px 0; cursor: pointer;">
                                        <input type="checkbox" name="pressure[]" value="{{ $pressure->maximum_p }}"
                                            style="accent-color: var(--accent-color);"
                                            {{ in_array($pressure->maximum_p, request('pressure', [])) ? 'checked' : '' }}>
                                        {{ $pressure->maximum_p }}
                                    </label>
                                @endforeach
                            </div>

                            <hr style="border: none; border-top: 0.5px solid #E5E7EB; margin: 1rem 0;">

                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <button type="submit"
                                    style="width: 100%; padding: 9px; background: var(--main-color); color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                    Terapkan
                                </button>
                                <a href="{{ route('id.frontend.category.tygon') }}"
                                    style="display: block; width: 100%; padding: 9px; background: #F3F4F6; color: #6B7280; border: 0.5px solid #E5E7EB; border-radius: 8px; font-size: 13px; text-align: center; text-decoration: none; box-sizing: border-box;">
                                    Bersihkan
                                </a>
                            </div>

                        </form>
                    </aside>

                    {{-- Product Area --}}
                    <div>
                        @php
                            $locale = $locale ?? request()->segment(1);
                        @endphp

                        {{-- Toolbar --}}
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                            <p style="font-size: 13px; color: #6B7280;">
                                Menampilkan <strong>{{ $produk->count() }}</strong> produk
                            </p>
                        </div>

                        {{-- Product Grid --}}
                        <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">

                            @forelse ($produk as $item)
                                <div
                                    style="background: #fff; border-radius: 12px; border: 0.5px solid #E5E7EB; padding: 12px;">
                                    <div style="display: flex; gap: 12px;">

                                        {{-- GAMBAR --}}
                                        <div
                                            style="
                    width: 120px; height: 90px;
                    background: var(--accent-light);
                    border-radius: 8px;
                    display: flex; align-items: center; justify-content: center;
                    position: relative; flex-shrink: 0;
                ">
                                            @if ($item->gambar)
                                                <img src="{{ asset('storage/' . $item->gambar) }}"
                                                    alt="{{ $item->nama_produk }}"
                                                    style="width: 100%; height: 100%; object-fit: contain; border-radius: 8px; padding: 6px;">
                                            @else
                                                <svg width="70" height="40" viewBox="0 0 80 50" fill="none">
                                                    <rect x="4" y="10" width="72" height="12" rx="6"
                                                        stroke="#5DADE2" stroke-width="2" fill="none" />
                                                    <rect x="10" y="14" width="60" height="4" rx="2"
                                                        fill="#5DADE2" fill-opacity="0.2" />
                                                    <rect x="4" y="28" width="72" height="8" rx="4"
                                                        stroke="#5DADE2" stroke-width="1.5" fill="none" />
                                                </svg>
                                            @endif

                                            @if ($item->kode_produk)
                                                <span
                                                    style="
                            position: absolute; top: 6px; right: 6px;
                            background: var(--main-color); color: #fff;
                            font-size: 10px; padding: 2px 6px; border-radius: 999px;
                        ">{{ $item->kode_produk }}</span>
                                            @endif
                                        </div>

                                        {{-- CONTENT --}}
                                        <div style="flex: 1;">

                                            <p
                                                style="font-size: 14px; font-weight: 600; color: #1F2937; margin-bottom: 2px;">
                                                {{ $item->nama_produk }}
                                            </p>

                                            {{-- Dimensi --}}
                                            @if ($item->inner_diameter || $item->outer_diameter)
                                                <p style="font-size: 12px; color: #9CA3AF; margin-bottom: 6px;">
                                                    @if ($item->inner_diameter)
                                                        ID {{ $item->inner_diameter }}"
                                                    @endif
                                                    @if ($item->outer_diameter)
                                                        × OD {{ $item->outer_diameter }}"
                                                    @endif
                                                    @if ($item->wall_thickness)
                                                        × WT {{ $item->wall_thickness }}"
                                                    @endif
                                                </p>
                                            @endif

                                            {{-- TAGS --}}
                                            <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 8px;">

                                                {{-- ✅ Kolom yang benar: tempratur (typo di DB) --}}
                                                @if ($item->tempratur)
                                                    <span
                                                        style="background: #FEF3C7; color: #92400E; font-size: 10px; padding: 2px 8px; border-radius: 999px;">
                                                        {{ $item->tempratur }}
                                                    </span>
                                                @endif

                                                @if ($item->mating)
                                                    <span
                                                        style="background: var(--accent-light); color: #185FA5; font-size: 10px; padding: 2px 8px; border-radius: 999px;">
                                                        {{ $item->mating }}
                                                    </span>
                                                @endif

                                                {{-- Working Pressure --}}
                                                @if ($item->tygon_working_pressure_73)
                                                    <span
                                                        style="background: #F0FDF4; color: #166534; font-size: 10px; padding: 2px 8px; border-radius: 999px;">
                                                        {{ $item->tygon_working_pressure_73 }} psi @73°F
                                                    </span>
                                                @endif

                                                @if ($item->fda === 'Ya')
                                                    <span
                                                        style="background: #EDE9FE; color: #5B21B6; font-size: 10px; padding: 2px 8px; border-radius: 999px;">
                                                        FDA Listed
                                                    </span>
                                                @endif

                                            </div>

                                            {{-- FOOTER --}}
                                            <div
                                                style="display: flex; justify-content: space-between; align-items: center;">
                                                <span style="font-size: 14px; font-weight: 600; color: var(--main-color);">
                                                    @if ($item->harga)
                                                        Rp {{ number_format($item->harga, 0, ',', '.') }} / m
                                                    @else
                                                        <span style="font-size: 12px; color: #9CA3AF;">Hubungi Kami</span>
                                                    @endif
                                                </span>

                                                <a href="{{ route('id.frontend.produk.show', $item->id) }}"
                                                    class="view-options-button"
                                                    data-url="{{ route('id.frontend.produk.show', $item->id) }}"
                                                    style="font-size: 11px; padding: 5px 12px; background: var(--main-color); color: white; border-radius: 6px; text-decoration: none;">
                                                    Lihat Detail
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            @empty
                                <div
                                    style="text-align: center; padding: 3rem; color: #9CA3AF; background: #fff; border-radius: 12px;">
                                    <p style="font-size: 14px; font-weight: 500;">Tidak ada produk ditemukan.</p>
                                    <p style="font-size: 12px; margin-top: 4px;">Coba ubah filter yang dipilih.</p>
                                </div>
                            @endforelse

                        </div>

                        {{-- Pagination --}}
                        <div style="display: flex; justify-content: center; margin-top: 2rem;">
                            {{ $produk->links() }}
                        </div>

                    </div>
                </div>
            </div>

            {{-- Login Required Modal (sama seperti halaman produk) --}}
            <div id="login-required-modal"
                class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm flex items-center justify-center h-full w-full hidden z-50 p-4">
                <div
                    class="relative max-w-md w-full mx-auto p-8 bg-white rounded-2xl shadow-xl text-center transform transition-all duration-300 scale-95 opacity-0">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-6">
                        <svg class="h-8 w-8 text-[#5DADE2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl leading-6 font-bold text-gray-900">Login Diperlukan</h3>
                    <div class="mt-3 px-4 py-3">
                        <p class="text-base text-gray-600">Silakan login untuk melihat detail lebih lanjut dan
                            opsi
                            produk.
                        </p>
                    </div>
                    <div class="flex items-center justify-center space-x-4 mt-6">
                        <button id="close-modal"
                            class="w-full px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors text-sm">
                            Tutup
                        </button>
                        <a href="{{ route('id.customer.login') }}"
                            class="w-full px-6 py-3 bg-[#1A365D] text-white font-semibold rounded-lg hover:opacity-90 transition-opacity text-sm">
                            Login
                        </a>
                    </div>
                </div>
            </div>

        </div>
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const viewOptionsButtons = document.querySelectorAll('.view-options-button');
                const loginModal = document.getElementById('login-required-modal');
                const closeModalButton = document.getElementById('close-modal');
                const modalContent = loginModal.querySelector('.transform');

                function showModal() {
                    loginModal.classList.remove('hidden');
                    setTimeout(() => {
                        modalContent.classList.remove('scale-95', 'opacity-0');
                        modalContent.classList.add('scale-100', 'opacity-100');
                    }, 50);
                }

                function hideModal() {
                    modalContent.classList.add('scale-95', 'opacity-0');
                    modalContent.classList.remove('scale-100', 'opacity-100');
                    setTimeout(() => {
                        loginModal.classList.add('hidden');
                    }, 300);
                }

                viewOptionsButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        @if (Auth::guard('customer')->check())
                            window.location.href = this.dataset.url;
                        @else
                            showModal();
                        @endif
                    });
                });

                if (closeModalButton) {
                    closeModalButton.addEventListener('click', hideModal);
                }

                window.addEventListener('click', function(e) {
                    if (e.target === loginModal) hideModal();
                });
            });
        </script>
    @endpush
