@extends('id.layouts.app')

@section('title', 'Produk')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-12 gap-6">

            <!-- Sidebar Filter -->
            <aside class="col-span-12 md:col-span-3 bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold mb-4">Filters</h3>
                <form action="{{ route('id.frontend.produk') }}" method="GET">
                    <a href="{{ route('id.frontend.produk') }}" class="text-blue-600 text-sm mb-4 inline-block">Clear All</a>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Category</h4>
                        <ul class="space-y-1 text-sm">
                            @foreach ($kategori as $item)
                                <li><label><input type="checkbox" name="kategori[]" value="{{ $item->id }}"
                                            class="mr-2"
                                            {{ in_array($item->id, request()->get('kategori', [])) ? 'checked' : '' }}>
                                        {{ $item->nama_kategori }}</label></li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Mating surface hardness</h4>
                        <ul class="space-y-1 text-sm">
                            @foreach ($kekerasan_permukaan_kawin as $item)
                                <li><label><input type="checkbox" name="kekerasan_permukaan_kawin[]"
                                            value="{{ $item->kekerasan_permukaan_kawin }}" class="mr-2"
                                            {{ in_array($item->kekerasan_permukaan_kawin, request()->get('kekerasan_permukaan_kawin', [])) ? 'checked' : '' }}>
                                        {{ $item->kekerasan_permukaan_kawin }}</label></li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Pressure - Max PV</h4>
                        <ul class="space-y-1 text-sm">
                            @foreach ($tekanan as $item)
                                <li><label><input type="checkbox" name="tekanan[]" value="{{ $item->tekanan }}"
                                            class="mr-2"
                                            {{ in_array($item->tekanan, request()->get('tekanan', [])) ? 'checked' : '' }}>
                                        {{ $item->tekanan }}</label></li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Filter</button>
                </form>
            </aside>
            <!-- Produk Grid -->
            <div class="col-span-12 md:col-span-9">
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600">Menampilkan {{ $produk->firstItem() }} - {{ $produk->lastItem() }} dari
                        {{ $produk->total() }} Hasil</p>
                    <select
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Urutkan menurut</option>
                        <option>Terbaru</option>
                        <option>Harga: Rendah ke Tinggi</option>
                        <option>Harga: Tinggi ke Rendah</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($produk as $p)
                        <div
                            class="bg-white rounded-lg shadow-md p-4 flex flex-col items-center transform transition-transform duration-300 hover:scale-105">
                            <a href="{{ route('id.frontend.produk.show', $p->id) }}" class="w-full">
                                <img src="{{ asset('backend/assets/media/produk/' . $p->gambar) }}"
                                    alt="{{ $p->nama_produk }}" class="w-full h-48 object-contain mb-4">
                                <h3 class="font-bold text-lg text-center mb-2">{{ $p->nama_produk }}</h3>
                            </a>
                            <div class="grid grid-cols-2 gap-4 mt-2 w-full">
                                <div>
                                    <p class="text-center text-xs text-gray-500">Mating Surface Hardness</p>
                                    <h3 class="font-semibold text-center">{{ $p->kekerasan_permukaan_kawin }}</h3>
                                </div>
                                <div>
                                    <p class="text-center text-xs text-gray-500">Pressure</p>
                                    <h3 class="font-semibold text-center">{{ $p->tekanan }}</h3>
                                </div>
                            </div>

                            <a href="#" data-url="{{ route('id.frontend.produk.show', $p->id) }}"
                                class="view-options-button mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-full transition-colors duration-300">
                                LIHAT DETAIL
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500 text-lg">Tidak ada produk yang ditemukan.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $produk->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- Login Required Modal -->
    <div id="login-required-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Login Required</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        You need to be logged in to view product options.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="close-modal"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 mr-2">Close</button>
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Login</a>
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

            viewOptionsButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    @if (Auth::guard('customer')->check())
                        window.location.href = this.dataset.url;
                    @else
                        loginModal.classList.remove('hidden');
                    @endif
                });
            });

            if (closeModalButton) {
                closeModalButton.addEventListener('click', function() {
                    loginModal.classList.add('hidden');
                });
            }

            // Close modal if clicking outside of it
            window.addEventListener('click', function(e) {
                if (e.target === loginModal) {
                    loginModal.classList.add('hidden');
                }
            });
        });
    </script>
@endpush
