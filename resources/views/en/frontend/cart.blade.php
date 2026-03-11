@extends('en.layouts.app')

@section('content')
    <style>
        .cart-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .cart-items {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .cart-header {
            padding: 25px 30px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            color: #374151;
            font-size: 18px;
        }

        .cart-items-header {
            display: none;
            /* Hidden on mobile by default */
        }

        .cart-item {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            /* Stack items vertically on mobile */
            gap: 15px;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-main-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }

        .item-image {
            width: 80px;
            height: 80px;
            background-color: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-details {
            flex: 1;
            min-width: 0;
        }

        .item-details h3 {
            font-size: 16px;
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .item-variant {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .item-pricing-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding-top: 10px;
            margin-top: 10px;
            border-top: 1px solid #f3f4f6;
        }

        .item-price,
        .item-quantity,
        .item-total-price {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            text-align: center;
            flex-basis: 100px;
        }

        .remove-btn-wrapper {
            text-align: right;
        }

        .remove-btn {
            color: #dc2626;
            text-decoration: none;
            font-size: 13px;
            padding: 4px 8px;
            border-radius: 4px;
            transition: background-color 0.3s;
            font-weight: 500;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        .remove-btn:hover {
            background-color: #fef2f2;
        }

        .cart-total {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: #6b7280;
        }

        .total-row.final {
            border-top: 2px solid #e5e7eb;
            padding-top: 20px;
            margin-top: 25px;
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .checkout-btn {
            width: 100%;
            background-color: #1e3a8a;
            color: white;
            border: none;
            padding: 16px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 25px;
            transition: background-color 0.3s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .checkout-btn:hover {
            background-color: #1e40af;
            color: white;
        }

        .continue-shopping {
            display: block;
            text-align: center;
            color: #1e3a8a;
            text-decoration: none;
            margin-top: 20px;
            font-weight: 500;
        }

        .continue-shopping:hover {
            color: #1e40af;
        }

        .empty-cart {
            text-align: center;
            padding: 80px 40px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .empty-cart h3 {
            font-size: 24px;
            color: #6b7280;
            margin-bottom: 15px;
        }

        .empty-cart p {
            color: #9ca3af;
            margin-bottom: 30px;
        }

        .shop-now-btn {
            background-color: #1e3a8a;
            color: white;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .shop-now-btn:hover {
            background-color: #1e40af;
            color: white;
        }

        .breadcrumb {
            margin-bottom: 30px;
            color: #6b7280;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #1e3a8a;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #1e40af;
        }

        .cart-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .cart-summary-col {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .item-quantity button {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            color: #374151;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .item-quantity button:hover {
            background-color: #e5e7eb;
        }

        .item-quantity span {
            min-width: 25px;
            text-align: center;
        }

        @media (min-width: 1024px) {
            .cart-layout {
                grid-template-columns: 2fr 1fr;
            }

            .cart-items-header {
                display: grid;
                grid-template-columns: 3fr 1fr 1fr 1fr auto;
                align-items: center;
                gap: 15px;
                padding: 15px 30px;
                background-color: #f8f9fa;
                border-bottom: 1px solid #e5e7eb;
                font-weight: 600;
                color: #4b5563;
                font-size: 14px;
            }

            .cart-item {
                display: grid;
                grid-template-columns: 3fr 1fr 1fr 1fr auto;
                align-items: center;
                gap: 15px;
                padding: 20px 30px;
                flex-direction: row;
                /* Horizontal layout on desktop */
            }

            .item-pricing-info {
                display: contents;
                /* Let grid handle layout */
            }

            .item-price,
            .item-quantity,
            .item-total-price {
                border: none;
                padding: 0;
                margin: 0;
            }
        }
    </style>

    <div class="container mx-auto px-4 py-8 cart-container">

        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('en.frontend.produk') }}">Home</a> > Shopping Cart
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @php
            // Standardize cart items for both logged-in users and guests
            $cartItems = null;
            if (Auth::guard('customer')->check() && isset($cart)) {
                $cartItems = $cart->items;
            } elseif (session()->has('cart')) {
                $cartItems = collect(session('cart'))->map(function ($item) {
                    return (object) $item;
                });
            }

            // Calculate totals here to ensure they are available for the entire view, including JS.
            if ($cartItems && $cartItems->count() > 0) {
                $subtotal = $cartItems->sum(fn($item) => ($item->harga ?? 0) * $item->qty);
                $totalGrossWeight = $cartItems->sum(fn($item) => ($item->gros ?? 0) * $item->qty);
                $total = $subtotal; // Shipping cost will be added via JS
            } else {
                $subtotal = 0;
                $totalGrossWeight = 0;
                $total = 0;
            }
        @endphp

        @if ($cartItems && $cartItems->count() > 0)
            <div class="cart-layout">
                <!-- Left Column: Cart Items -->
                <div class="cart-items">
                    <div class="cart-header">
                        Shopping Cart ({{ $cartItems->sum('qty') }})
                    </div>

                    <!-- Desktop Header -->
                    <div class="cart-items-header">
                        <div class="text-left">Product</div>
                        <div class="text-center"style="margin-left: -73px;">Price</div>
                        <div class="text-center"style="margin-left: -103px;">Quantity</div>

                        <div class="text-right"style="margin-right: 95px;">Total</div>
                        <div></div> <!-- For remove button -->
                    </div>

                    @foreach ($cartItems as $item)
                        <div class="cart-item" data-item-id="{{ $item->id }}">
                            <!-- Product Details (Image and Name) -->
                            <div class="item-main-info">
                                <div class="item-image">
                                    @php
                                        $image = Auth::guard('customer')->check()
                                            ? $item->produk->gambar ?? null
                                            : $item->image ?? null;
                                        $productName = Auth::guard('customer')->check()
                                            ? $item->produk->nama_produk
                                            : $item->name;
                                    @endphp
                                    @if ($image)
                                        <img src="{{ asset('backend/assets/media/produk/' . $image) }}"
                                            alt="{{ $productName }}">
                                    @else
                                        <div
                                            style="width: 80px; height: 40px; background: linear-gradient(45deg, #8b4513, #a0522d); border-radius: 20px;">
                                        </div>
                                    @endif
                                </div>
                                <div class="item-details">
                                    <h3>{{ $productName }}</h3>
                                    @php
                                        $jenis = Auth::guard('customer')->check()
                                            ? $item->jenis->jenis ?? 'N/A'
                                            : $item->jenis_name ?? 'N/A';
                                        $ukuran = Auth::guard('customer')->check()
                                            ? $item->ukuran->nama_ukuran ?? 'N/A'
                                            : $item->ukuran_name ?? 'N/A';
                                    @endphp
                                    <div class="item-variant">Shape: {{ $jenis }}</div>
                                    <div class="item-variant">Size: {{ $ukuran }}</div>
                                </div>
                            </div>

                            <!-- Mobile Pricing Info -->

                            <!-- Desktop Pricing Info -->
                            <div class="item-price hidden lg:block text-center">
                                ${{ number_format($item->harga ?? 0, 2) }}
                            </div>
                            <div class="item-price hidden lg:block text-center">
                                {{ $item->qty }}
                            </div>
                            <div class="item-total-price hidden lg:block text-right">
                                ${{ number_format(($item->harga ?? 0) * $item->qty, 2) }}
                            </div>


                            <!-- Remove Button -->
                            <div class="remove-btn-wrapper">
                                <form action="{{ route('en.frontend.cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove-btn">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right Column: Expedition and Summary -->
                <div class="cart-summary-col">
                    @if (
                        (Auth::guard('customer')->check() && $cart && $cart->items->count()) ||
                            (session()->has('cart') && collect(session('cart'))->count() > 0))
                        <div class="w-full rounded-lg border border-gray-200 bg-white p-6 shadow-sm mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Shipping Address</h3>
                            @if ($allAddresses->isNotEmpty())
                                <div class="mt-4">
                                    <label for="shipping_address"
                                        class="block text-sm font-medium text-gray-700 mb-2">Choose Address</label>
                                    <select id="shipping_address" name="shipping_address"
                                        class="form-input w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3">
                                        @foreach ($allAddresses as $address)
                                            <option value="{{ $address->id }}"
                                                {{ session('selected_address_id') == $address->id ? 'selected' : '' }}>
                                                {{ $address->alamat }}, {{ $address->city }},
                                                {{ $address->state }}, {{ $address->zip_code }},
                                                {{ $address->kode_iso }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <p class="text-sm text-gray-600">You have no saved addresses. Please <a href="#"
                                        class="text-blue-600 hover:underline">add an address</a> to your profile.</p>
                            @endif
                        </div>

                        <!-- Shipping Details -->

                        <div class="w-full rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <!--<h3 class="text-lg font-semibold text-gray-800 mb-4">Shipping Details</h3>
                                                                                                                                                                                                                                                                                                            <div class="mt-4">
                                                                                                                                                                                                                                                                                                                <label for="destination_zip"
                                                                                                                                                                                                                                                                                                                    class="block text-sm font-medium text-gray-700 mb-2">Destination Zip Code</label>
                                                                                                                                                                                                                                                                                                                <div class="flex items-center">
                                                                                                                                                                                                                                                                                                                    <input type="text" id="destination_zip" placeholder="Enter zip code"
                                                                                                                                                                                                                                                                                                                        class="form-input w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                                                                                                                                                                                                                                                                                    <button id="calculate_shipping"
                                                                                                                                                                                                                                                                                                                        class="ml-3 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Calculate</button>
                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                            </div>-->
                            <div class="mt-4">
                                <label for="shipping" class="block text-sm font-medium text-gray-700 mb-2">Choose Shipping
                                    Service</label>
                                <div id="shipping-options-container" class="mt-2 space-y-3">
                                    <p class="text-sm text-gray-500">Please select a shipping address to view options.</p>
                                </div>
                            </div>
                            <input type="hidden" id="customer_zip" value="{{ $defaultAddress->zip_code ?? '' }}">
                            <input type="hidden" id="customer_country" value="{{ $defaultAddress->kode_iso ?? '' }}">
                            <input type="hidden" id="total_weight"value="{{ number_format($final_gross_weight, 2) }} ">
                        </div>

                        <!-- Order Summary -->
                        <div class="cart-total">
                            <h3 style="font-size: 20px; font-weight: 600; color: #1f2937; margin-bottom: 25px;">
                                Order Summary
                            </h3>
                            <div class="total-row">
                                <span>Subtotal ({{ $cartItems->sum('qty') }} items)</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="total-row" id="shipping-cost-row" style="display: none;">
                                <span>Shipping</span>
                                <span id="shipping-cost-value"></span>
                            </div>
                            <div class="total-row">
                                <span>Total Gross Weight</span>
                                <span id="cart-total-gross-weight">{{ number_format($final_gross_weight, 2) }} kg</span>
                            </div>
                            <div class="total-row final">
                                <span>Total</span>
                                <span id="total-amount">${{ number_format($total, 2) }}</span>
                            </div>
                            <form id="checkout-form" action="{{ route('en.frontend.cart.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="checkout-btn">
                                    Proceed to Checkout
                                </button>
                            </form>
                            <a href="{{ route('en.frontend.produk') }}" class="continue-shopping">
                                ← Continue Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="empty-cart">
                <h3>Your shopping cart is empty</h3>
                <p>Looks like you haven't added any products to your cart yet.</p>
                <a href="{{ route('en.frontend.produk') }}" class="shop-now-btn">Start Shopping</a>
            </div>
        @endif
    </div>

@endsection

@push('styles')
    <style>
        .quantity-btn {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            color: #374151;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0.25rem 0.75rem;
        }

        .quantity-input {
            width: 40px;
            text-align: center;
            border: 1px solid #d1d5db;
            margin: 0 5px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Element references
            const addressSelect = document.getElementById('shipping_address');
            const shippingOptionsContainer = document.getElementById('shipping-options-container');
            const totalWeightInput = document.getElementById('total_weight');
            const shippingCostValue = document.getElementById('shipping-cost-value');
            const shippingCostRow = document.getElementById('shipping-cost-row');
            const totalElement = document.getElementById('total-amount');
            const subtotal = {{ $subtotal ?? 0 }};

            function formatCurrency(value) {
                // This function can be localized based on the currency
                return '$' + parseFloat(value).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Function to fetch shipping rates
            function fetchRates(addressId) {
                const selectedOption = addressSelect.options[addressSelect.selectedIndex];

                const city = selectedOption.dataset.city;
                const state = selectedOption.dataset.state;
                const country = selectedOption.dataset.country;
                const zip = selectedOption.dataset.zip;
                const totalWeight = totalWeightInput.value;

                if (!addressId || totalWeight <= 0) {
                    shippingOptionsContainer.innerHTML =
                        '<p class="text-sm text-gray-500">Select an address to see rates</p>';
                    return;
                }

                shippingOptionsContainer.innerHTML = '<p class="text-sm text-gray-500">Loading services...</p>';

                fetch('{{ route('en.frontend.cart.getShippingRate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            address_id: addressId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(
                                    `Request failed: ${response.status} ${response.statusText} - ${text}`
                                );
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        shippingOptionsContainer.innerHTML = ''; // Clear previous options

                        if (data && data.rates && data.rates.length > 0) {
                            data.rates.forEach(function(rate) {
                                const optionHtml = `
                                <div class="shipping-option flex items-center justify-between p-3 border rounded-md cursor-pointer hover:bg-gray-50" data-rate="${rate.total_charge}" data-currency="${rate.currency}" data-service-type="${rate.service_type}">
                                    <div>
                                        <input type="radio" name="shipping_option" value="${rate.service_name}" class="me-2">
                                        <strong>${rate.service_name}</strong>
                                        <small class="d-block text-muted">Est. Delivery: ${rate.delivery_timestamp}</small>
                                    </div>
                                    <div class="fw-bold">${formatCurrency(rate.total_charge)}</div>
                                </div>`;
                                shippingOptionsContainer.insertAdjacentHTML('beforeend', optionHtml);
                            });
                        } else {
                            console.log('No rates found in response.');
                            shippingOptionsContainer.innerHTML =
                                '<p class="text-sm text-gray-500">No shipping services available for this address</p>';
                        }
                    })
                    .catch(err => {
                        console.error('An error occurred while fetching shipping rates:', err);
                        shippingOptionsContainer.innerHTML =
                            '<p class="text-sm text-red-500">Error loading shipping services</p>';
                    });
            }

            // Delegated event listener for shipping option selection
            shippingOptionsContainer.addEventListener('change', function(e) {
                if (e.target.name === 'shipping_option' && e.target.checked) {
                    const shippingOption = e.target.closest('.shipping-option');
                    const rate = parseFloat(shippingOption.dataset.rate);
                    const serviceType = shippingOption.dataset.serviceType;
                    const addressId = addressSelect.value;

                    // Update UI
                    shippingCostRow.style.display = 'flex';
                    shippingCostValue.textContent = formatCurrency(rate);
                    const newTotal = subtotal + rate;
                    totalElement.textContent = formatCurrency(newTotal);

                    // Save the selected address and shipping option to the session
                    updateShippingSelection(addressId, serviceType, rate);
                }
            });

            // Event listener for address change
            if (addressSelect) {
                addressSelect.addEventListener('change', function() {
                    const selectedAddressId = this.value;
                    // Reset and fetch new rates
                    shippingOptionsContainer.innerHTML =
                        '<p class="text-sm text-gray-500">Loading services...</p>';
                    shippingCostRow.style.display = 'none';
                    totalElement.textContent = formatCurrency(subtotal);
                    fetchRates(selectedAddressId);
                    // Save the selected address, shipping will be null initially
                    updateShippingSelection(selectedAddressId, null, null);
                });

                // Initial fetch if an address is already selected
                if (addressSelect.value) {
                    fetchRates(addressSelect.value);
                    // Also save the initially selected address
                    updateShippingSelection(addressSelect.value, null, null);
                }
            }

            function updateShippingSelection(addressId, service, cost) {
                fetch('{{ route('en.frontend.cart.updateShippingSelection') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            address_id: addressId,
                            shipping_service: service,
                            shipping_cost: cost
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            console.error('Failed to update shipping selection.');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating shipping selection:', error);
                    });
            }

            // Quantity update logic
            const quantityButtons = document.querySelectorAll('.quantity-btn');
            quantityButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const action = this.dataset.action;
                    const itemId = this.dataset.id;
                    updateQuantity(itemId, action);
                });
            });

            function updateQuantity(itemId, action) {
                const url = `/en/frontend/cart/update-quantity/${itemId}`;

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            action: action
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(
                                'An error occurred while updating the quantity. Please check the console for more details.'
                            );
                            console.error('Error:', data.message);
                        }
                    })
                    .catch(error => {
                        alert(
                            'An error occurred while updating the quantity. Please check the console for more details.'
                        );
                        console.error('Error:', error);
                    });
            }

            // Handle checkout form submission
            const checkoutForm = document.getElementById('checkout-form');
            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent default form submission

                    const checkoutButton = this.querySelector('button[type="submit"]');
                    checkoutButton.disabled = true;
                    checkoutButton.textContent = 'Processing...';

                    fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else {
                                // Handle errors (e.g., display a message)
                                alert(data.error || 'An unexpected error occurred.');
                                checkoutButton.disabled = false;
                                checkoutButton.textContent = 'Proceed to Checkout';
                            }
                        })
                        .catch(error => {
                            console.error('Checkout error:', error);
                            alert('An error occurred during checkout. Please try again.');
                            checkoutButton.disabled = false;
                            checkoutButton.textContent = 'Proceed to Checkout';
                        });
                });
            }
        });
    </script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartItemsContainer = document.querySelector('.cart-items');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (!cartItemsContainer) {
                console.error('Cart items container not found!');
                return;
            }

            cartItemsContainer.addEventListener('click', function(event) {
                const target = event.target;
                const quantityBtn = target.closest('.quantity-btn');

                if (quantityBtn) {
                    event.preventDefault();
                    const itemId = quantityBtn.dataset.id;
                    const action = quantityBtn.dataset.action;

                    if (!itemId || !action) {
                        console.error('Missing data-id or data-action on the button.');
                        return;
                    }

                    // Disable buttons to prevent multiple clicks
                    const allButtons = cartItemsContainer.querySelectorAll(
                        `.quantity-btn[data-id="${itemId}"]`);
                    allButtons.forEach(btn => btn.disabled = true);

                    updateQuantity(itemId, action, allButtons);
                }
            });

            async function updateQuantity(itemId, action, buttons) {
                // The route seems to be /cart/update/{itemId} and expects a qty
                // We need to get the current quantity first to increment/decrement it.
                const itemRow = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
                if (!itemRow) return;

                const quantityEl = itemRow.querySelector('.quantity-value');
                let currentQty = parseInt(quantityEl.textContent, 10);

                let newQty;
                if (action === 'increase') {
                    newQty = currentQty + 1;
                } else if (action === 'decrease') {
                    newQty = Math.max(1, currentQty - 1);
                } else {
                    return; // Should not happen
                }

                const url = `/cart/update-quantity/{item_id}`;

                try {
                    const response = await fetch(url, {
                        method: 'POST', // Should be POST or PUT/PATCH as per convention
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            qty: newQty,
                            _method: 'PATCH' // Method spoofing for Laravel if route is PATCH
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Update UI dynamically
                        quantityEl.textContent = data.new_quantity;

                        const itemSubtotalEl = itemRow.querySelector('.item-total-price');
                        if (itemSubtotalEl) {
                            itemSubtotalEl.textContent = formatCurrency(data.item_subtotal);
                        }

                        const itemTotalWeightEl = itemRow.querySelector('.item-total-weight');
                        if (itemTotalWeightEl) {
                            itemTotalWeightEl.textContent = data.item_total_weight.toFixed(2) + ' kg';
                        }

                        // Update cart totals
                        const cartTotalEl = document.getElementById('total-amount');
                        if (cartTotalEl) {
                            cartTotalEl.textContent = formatCurrency(data.cart_total);
                        }

                        const cartTotalGrossWeightEl = document.getElementById('cart-total-gross-weight');
                        if (cartTotalGrossWeightEl) {
                            cartTotalGrossWeightEl.textContent = data.cart_total_gross_weight.toFixed(2) +
                                ' kg';
                        }
                    } else {
                        // Display server-side error message
                        console.error('Error updating quantity:', data.message);
                        alert('Error: ' + data.message);
                    }

                } catch (error) {
                    console.error('A network error occurred:', error);
                    alert(
                        'An error occurred while updating the quantity. Please check the console for more details.'
                    );
                } finally {
                    // Re-enable buttons
                    buttons.forEach(btn => btn.disabled = false);
                }
            }

            function formatCurrency(amount) {
                // This will format the number according to US Dollar standards
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(amount);
            }
        });
    </script>
@endpush
