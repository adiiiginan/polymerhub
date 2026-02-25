@extends('en.layouts.app')

@section('title', 'Produk')

@section('content')
    <div class="bg-gray-100" style="--main-color: #1A365D; --accent-color: #5DADE2;">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">

            <!-- Page Header -->
            <div class="text-center mb-14">
                <h1 class="text-4xl font-extrabold tracking-tight text-[var(--main-color)] sm:text-5xl">
                    Our Product Catalog
                </h1>
                <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600">
                    Discover our premier sealing solutions, crafted for durability and performance.
                </p>
            </div>

            <!-- Filters -->
            <div class="mb-10 bg-white rounded-xl shadow-md">
                <div class="p-6">
                    <form action="{{ route('en.frontend.produk') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-6">
                            <!-- Category Filter -->
                            <div class="lg:col-span-1">
                                <h4 class="font-semibold text-gray-800 mb-3">Category</h4>
                                <div class="max-h-40 overflow-y-auto pr-2">
                                    @foreach ($categories as $category)
                                        <label
                                            class="flex items-center cursor-pointer text-gray-600 hover:text-[var(--main-color)] transition-colors group mb-2">
                                            <input type="checkbox" name="category[]" value="{{ $category->id_kat }}"
                                                class="h-4 w-4 rounded border-gray-300 text-[var(--accent-color)] focus:ring-[var(--accent-color)] mr-3"
                                                {{ in_array($category->id_kat, request('category', [])) ? 'checked' : '' }}>
                                            <span class="text-sm">{{ $category->kategori->kategori }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Mating Surface Hardness Filter -->
                            <div class="lg:col-span-1">
                                <h4 class="font-semibold text-gray-800 mb-3">Mating Surface Hardness</h4>
                                <div class="max-h-40 overflow-y-auto pr-2">
                                    @foreach ($matings as $mating)
                                        <label
                                            class="flex items-center cursor-pointer text-gray-600 hover:text-[var(--main-color)] transition-colors group mb-2">
                                            <input type="checkbox" name="mating_surface_hardness[]"
                                                value="{{ $mating->mating }}"
                                                class="h-4 w-4 rounded border-gray-300 text-[var(--accent-color)] focus:ring-[var(--accent-color)] mr-3"
                                                {{ in_array($mating->mating, request('mating_surface_hardness', [])) ? 'checked' : '' }}>
                                            <span class="text-sm">{{ $mating->mating }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Pressure Filter -->
                            <div class="lg:col-span-1">
                                <h4 class="font-semibold text-gray-800 mb-3">Pressure - Max PV</h4>
                                <div class="max-h-40 overflow-y-auto pr-2">
                                    @foreach ($pressures as $pressure)
                                        <label
                                            class="flex items-center cursor-pointer text-gray-600 hover:text-[var(--main-color)] transition-colors group mb-2">
                                            <input type="checkbox" name="pressure[]" value="{{ $pressure->maximum_p }}"
                                                class="h-4 w-4 rounded border-gray-300 text-[var(--accent-color)] focus:ring-[var(--accent-color)] mr-3"
                                                {{ in_array($pressure->maximum_p, request('pressure', [])) ? 'checked' : '' }}>
                                            <span class="text-sm">{{ $pressure->maximum_p }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="lg:col-span-1 flex flex-col justify-end">
                                <div class="flex items-center space-x-3 mt-4">
                                    <a href="{{ route('en.frontend.produk') }}"
                                        class="w-full text-center px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold text-sm">
                                        Clear
                                    </a>
                                    <button type="submit"
                                        class="w-full text-center px-4 py-2.5 bg-[var(--main-color)] text-white rounded-lg hover:opacity-90 transition-opacity font-semibold text-sm shadow">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Produk Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse ($produk as $p)
                    <div
                        class="bg-white rounded-lg overflow-hidden group flex flex-col border border-gray-200 hover:shadow-xl hover:border-[var(--main-color)]/50 transition-all duration-300">
                        <!-- Image -->
                        <div class="h-52 w-full flex items-center justify-center bg-white p-4 overflow-hidden">
                            <img src="{{ asset('backend/assets/media/produk/' . $p->gambar) }}"
                                alt="{{ $p->nama_produk }}"
                                class="max-h-full w-auto object-contain transition-transform duration-300 ease-in-out group-hover:scale-105"
                                loading="lazy">
                        </div>

                        <!-- Content -->
                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-base font-bold text-gray-800 flex-grow mb-4 leading-tight">
                                {{ $p->nama_produk }}
                            </h3>

                            <div class="grid grid-cols-2 gap-3 text-xs text-gray-500 mb-5 text-center">
                                <div class="bg-gray-50 border border-gray-200/80 p-2 rounded-md">
                                    <p class="font-semibold">Temperature</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">
                                        {{ $p->tempratur }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 border border-gray-200/80 p-2 rounded-md">
                                    <p class="font-semibold">Max Pressure</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">
                                        {{ $p->maximum_p }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <button data-url="{{ route('en.frontend.produk.show', $p->id) }}"
                                    class="view-options-button w-full text-center bg-[var(--accent-color)] text-white hover:bg-opacity-90 font-bold px-4 py-2.5 rounded-md text-sm transition-all duration-300 transform group-hover:scale-105">
                                    View Options
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="sm:col-span-2 xl:col-span-4 text-center py-24 bg-white rounded-lg shadow-sm">
                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-4 text-xl font-semibold text-gray-800">No Products Found</h3>
                        <p class="mt-2 text-base text-gray-500">Your search returned no results. Please try different
                            filters.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Login Required Modal -->
    <div id="login-required-modal"
        class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm flex items-center justify-center h-full w-full hidden z-50 p-4">
        <div
            class="relative max-w-md w-full mx-auto p-8 bg-white rounded-2xl shadow-xl text-center transform transition-all duration-300 scale-95 opacity-0">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-6">
                <svg class="h-8 w-8 text-[var(--accent-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <h3 class="text-2xl leading-6 font-bold text-gray-900">Login Required</h3>
            <div class="mt-3 px-4 py-3">
                <p class="text-base text-gray-600">
                    Please log in to see more details and product options.
                </p>
            </div>
            <div class="flex items-center justify-center space-x-4 mt-6">
                <button id="close-modal"
                    class="w-full px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors text-sm">Close</button>
                <a href="{{ route('login') }}"
                    class="w-full px-6 py-3 bg-[var(--main-color)] text-white font-semibold rounded-lg hover:opacity-90 transition-opacity text-sm">Login</a>
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
