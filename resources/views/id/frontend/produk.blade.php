@extends('id.layouts.app')

@section('title', 'Produk')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-12 gap-6">

            <!-- Sidebar Filter
                    <aside class="col-span-12 md:col-span-3 bg-white p-4 rounded-lg shadow">
                        <h3 class="font-bold mb-4">Filters</h3>
                        <a href="#" class="text-blue-600 text-sm mb-4 inline-block">Clear All</a>

                        <div class="mb-6">
                            <h4 class="font-semibold mb-2">Category</h4>
                            <ul class="space-y-1 text-sm">
                                <li><label><input type="checkbox" class="mr-2"> Special (10)</label></li>
                                <li><label><input type="checkbox" class="mr-2"> Standard (4)</label></li>
                            </ul>
                        </div>

                        <div class="mb-6">
                            <h4 class="font-semibold mb-2">Mating surface hardness</h4>
                            <ul class="space-y-1 text-sm">
                                <li><label><input type="checkbox" class="mr-2"> Rb 25 (9)</label></li>
                                <li><label><input type="checkbox" class="mr-2"> Rc 35 (5)</label></li>
                            </ul>
                        </div>

                        <div class="mb-6">
                            <h4 class="font-semibold mb-2">Pressure - Max PV</h4>
                            <ul class="space-y-1 text-sm">
                                <li><label><input type="checkbox" class="mr-2"> up to 0,26 Mpa (14)</label></li>
                                <li><label><input type="checkbox" class="mr-2"> up to 0,35 Mpa (13)</label></li>
                            </ul>
                        </div>
                    </aside>
        -->
            <!-- Produk Grid -->
            <div class="col-span-12 md:col-span-12">
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600">1 - {{ $produk->count() }} of {{ $produk->count() }} Results</p>
                    <select class="border rounded px-2 py-1 text-sm">
                        <option>Best Match</option>
                        <option>Newest</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($produk as $p)
                        <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">
                            <img src="{{ asset('backend/assets/media/produk/' . $p->gambar) }}" alt="{{ $p->nama_produk }}"
                                class="w-full h-40 object-contain mb-4">

                            <h3 class="font-bold text-center">{{ $p->nama_produk }}</h3>
                            <h3 class="font-bold text-center">{{ $p->tempratur }}</h3>
                            <h3 class="font-bold text-center">{{ $p->maximum_p }}</h3>

                            <a href="#" data-url="{{ route('id.frontend.produk.show', $p->id) }}"
                                class="view-options-button mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                LIHAT DETAIL
                            </a>
                        </div>
                    @endforeach
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
