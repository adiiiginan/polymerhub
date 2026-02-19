<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <a href="" class="flex items-center space-x-2" aria-label="JNS Logo">
                <img src="{{ asset('backend/assets/media/logos/logos/3.png') }}" alt="JNS Logo" class="h-20 w-auto">

            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-8">
                <a href="{{ route('home') }}"
                    class="text-gray-600 hover:text-blue-600 transition-colors {{ request()->routeIs('home') ? 'font-semibold text-blue-600' : '' }}">Home</a>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                        class="flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                        Products
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" class="absolute mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                        <a href="{{ route('frontend.produk') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Products</a>
                    </div>
                </div>
                <a href="{{ route('frontend.white') }}"
                    class="text-gray-600 hover:text-blue-600 transition-colors {{ request()->routeIs('frontend.white') ? 'font-semibold text-blue-600' : '' }}">White
                    Paper</a>
                <a href="{{ route('frontend.brochure') }}"
                    class="text-gray-600 hover:text-blue-600 transition-colors {{ request()->routeIs('frontend.brochure') ? 'font-semibold text-blue-600' : '' }}">Brochure</a>
                <a href="{{ route('frontend.contact') }}"
                    class="text-gray-600 hover:text-blue-600 transition-colors {{ request()->routeIs('frontend.contact') ? 'font-semibold text-blue-600' : '' }}">Contact
                    Us</a>
            </nav>

            <!-- Right side icons -->
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-600 hover:text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 p-4 bg-white rounded-md shadow-lg w-64 z-20">
                        <form action="{{ route('frontend.produk') }}" method="GET">
                            <input type="search" name="search" placeholder="Search products..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </form>
                    </div>
                </div>

                <!-- Cart -->
                <a href="{{ route('en.frontend.cart.index') }}" class="relative text-gray-600 hover:text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 9.172A1 1 0 005.618 22h12.764a1 1 0 00.91-1.828L17 13M7 13h10">
                        </path>
                    </svg>
                    @php
                        $cartCount = 0;
                        if (Auth::guard('customer')->check()) {
                            $cart = \App\Models\Cart::where('iduser', Auth::guard('customer')->id())
                                ->where('status', 1)
                                ->first();
                            if ($cart) {
                                $cartCount = $cart->items()->sum('qty');
                            }
                        } else {
                            $cart_session = session()->get('cart', []);
                            $cartCount = collect($cart_session)->sum('qty');
                        }
                    @endphp
                    <span id="cart-badge"
                        class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full px-1.5 py-0.5"
                        style="{{ $cartCount == 0 ? 'display: none;' : '' }}">{{ $cartCount }}</span>
                </a>

                <!-- User Dropdown / Login -->
                @if (Auth::guard('customer')->check())
                    @php
                        $customer = Auth::guard('customer')->user();
                        $customer->load('detail');
                    @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center space-x-2 focus:outline-none">
                            <img src="{{ asset('backend/assets/media/avatars/blank.png') }}" alt="Avatar"
                                class="w-8 h-8 rounded-full border-2 border-gray-300">
                        </button>
                        <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                            <div class="px-4 py-2 text-sm text-gray-700">
                                <p class="font-semibold">{{ $customer->detail->nama }}</p>
                                <p class="text-xs text-gray-500">{{ $customer->email }}</p>
                            </div>
                            <hr>
                            <a href="{{ route('en.customer.dashboard') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                            <a href="{{ route('en.customer.profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <hr>
                            <form method="POST" action="{{ route('en.customer.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="text-gray-600 hover:text-blue-600 transition-colors">Login</a>
                @endif

                <!-- Mobile Menu Button -->
                <button class="lg:hidden text-gray-600 hover:text-blue-600" @click="mobileMenuOpen = !mobileMenuOpen">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" class="lg:hidden" x-cloak>
        <div class="pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('home') }}"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">Home</a>
            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full text-left flex justify-between items-center px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    <span>Products</span>
                    <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-show="open" class="pl-4">
                    <a href="{{ route('frontend.produk') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">All
                        Products</a>
                </div>
            </div>
            <a href="{{ route('frontend.white') }}"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">White
                Paper</a>
            <a href="{{ route('frontend.brochure') }}"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Brochure</a>
            <a href="{{ route('frontend.contact') }}"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Contact
                Us</a>
        </div>
    </div>
</header>

<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // You can add any specific JS for the new navbar here if needed.
        // Alpine.js is handling the dropdowns and mobile menu.
    });
</script>
