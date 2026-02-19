@extends('layouts.app')

@section('title', 'Produk')

@section('content')
    <div class="bg-slate-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- Sidebar Filter -->
                <aside class="col-span-1 lg:col-span-1">
                    <form action="{{ route('frontend.produk') }}" method="GET">
                        <div class="bg-white p-6 rounded-xl border border-gray-200 sticky top-28">
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                                <a href="{{ route('frontend.produk') }}"
                                    class="text-blue-600 hover:text-blue-800 text-xs font-semibold transition tracking-wide">CLEAR
                                    ALL</a>
                            </div>

                            <!-- Category Filter -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-800 mb-3 text-sm">Category</h4>
                                <ul class="space-y-2">
                                    @foreach ($categories as $category)
                                        <li>
                                            <label
                                                class="flex items-center cursor-pointer text-gray-600 hover:text-gray-900 transition-colors">
                                                <input type="checkbox" name="category[]" value="{{ $category->id_kat }}"
                                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-300 mr-3"
                                                    {{ in_array($category->id_kat, request('category', [])) ? 'checked' : '' }}>
                                                <span class="text-sm">{{ $category->kategori->kategori }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Mating Surface Hardness Filter -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-800 mb-3 text-sm">Mating Surface Hardness</h4>
                                <ul class="space-y-2">
                                    @foreach ($matings as $mating)
                                        <li>
                                            <label
                                                class="flex items-center cursor-pointer text-gray-600 hover:text-gray-900 transition-colors">
                                                <input type="checkbox" name="mating_surface_hardness[]"
                                                    value="{{ $mating->mating }}"
                                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-300 mr-3"
                                                    {{ in_array($mating->mating, request('mating_surface_hardness', [])) ? 'checked' : '' }}>
                                                <span class="text-sm">{{ $mating->mating }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Pressure Filter -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-3 text-sm">Pressure - Max PV</h4>
                                <ul class="space-y-2">
                                    @foreach ($pressures as $pressure)
                                        <li>
                                            <label
                                                class="flex items-center cursor-pointer text-gray-600 hover:text-gray-900 transition-colors">
                                                <input type="checkbox" name="pressure[]" value="{{ $pressure->maximum_p }}"
                                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-300 mr-3"
                                                    {{ in_array($pressure->maximum_p, request('pressure', [])) ? 'checked' : '' }}>
                                                <span class="text-sm">{{ $pressure->maximum_p }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mt-6">
                                <button type="submit"
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">Apply
                                    Filters</button>
                            </div>
                        </div>
                    </form>
                </aside>

                <!-- Produk Grid -->
                <div class="col-span-1 lg:col-span-3">
                    <!-- Grid Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                        <p class="text-sm text-gray-600 mb-2 sm:mb-0">
                            Showing <span class="font-semibold text-gray-900">{{ $produk->count() }}</span> of <span
                                class="font-semibold text-gray-900">{{ $produk->count() }}</span> Results
                        </p>
                        <div class="flex items-center">
                            <label for="sort" class="text-sm text-gray-600 mr-2">Sort by:</label>
                            <select id="sort"
                                class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 pl-3 pr-8">
                                <option>Best Match</option>
                                <option>Newest</option>
                                <option>Price: Low to High</option>
                                <option>Price: High to Low</option>
                            </select>
                        </div>
                    </div>

                    <!-- Product Items -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($produk as $p)
                            <div
                                class="bg-white rounded-lg overflow-hidden group relative border border-gray-100/80 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                                <!-- Image -->
                                <div class="aspect-square bg-gray-50 flex items-center justify-center">
                                    <img src="{{ asset('backend/assets/media/produk/' . $p->gambar) }}"
                                        alt="{{ $p->nama_produk }}"
                                        class="w-full h-full object-contain p-6 transition-transform duration-300 group-hover:scale-[1.02]"
                                        loading="lazy">
                                </div>

                                <!-- Content -->
                                <div class="p-5">
                                    <h3 class="text-base font-semibold text-gray-900 truncate">
                                        {{ $p->nama_produk }}
                                    </h3>

                                    <div class="mt-4 flex justify-between items-center">
                                        <div>
                                            <p class="text-xs text-gray-500">Temperature</p>
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $p->tempratur }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">Max Pressure</p>
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $p->maximum_p }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hover Button -->
                                <div
                                    class="absolute bottom-0 left-0 right-0 p-3 bg-white/70 backdrop-blur-sm translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out">
                                    <button data-url="{{ route('frontend.produk.show', $p->id) }}"
                                        class="view-options-button w-full text-center bg-blue-600 text-white hover:bg-blue-700 font-bold px-4 py-2 rounded-md text-sm transition-colors">
                                        View Options
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Required Modal -->
    <div id="login-required-modal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm flex items-center justify-center h-full w-full hidden z-50">
        <div
            class="relative max-w-md w-full mx-4 p-8 bg-white rounded-2xl shadow-xl text-center transform transition-all duration-300 scale-95 opacity-0">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-blue-100 mb-6">
                <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <h3 class="text-xl leading-6 font-bold text-gray-900">Login Required</h3>
            <div class="mt-2 px-4 py-3">
                <p class="text-sm text-gray-600">
                    You need to be logged in to view product options and details.
                </p>
            </div>
            <div class="flex items-center justify-center space-x-3 mt-5">
                <button id="close-modal"
                    class="w-full px-5 py-2.5 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors text-sm">Close</button>
                <a href="{{ route('frontend.customer.login') }}"
                    class="w-full px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors text-sm">Login</a>
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
                if (e.target === loginModal) {
                    hideModal();
                }
            });
        });
    </script>
@endpush
