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
                            <div class="mt-4">
                                <label for="shipping" class="block text-sm font-medium text-gray-700 mb-2">Choose Shipping
                                    Service</label>
                                <div id="shipping-options-container" class="mt-2 space-y-3">
                                    <p class="text-sm text-gray-500">Please select a shipping address to view options.</p>
                                </div>
                            </div>
                            <input type="hidden" id="customer_zip" value="{{ $defaultAddress->zip_code ?? '' }}">
                            <input type="hidden" id="customer_country" value="{{ $defaultAddress->kode_iso ?? '' }}">
                            <input type="hidden" id="total_weight" value="{{ number_format($final_gross_weight, 2) }}">
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
        /**
         * Cart Shipping Module - FIXED
         * Handles shipping address selection, rate calculation, and selection
         * FIX: Now properly sends both address_id and weight to backend
         */

        document.addEventListener('DOMContentLoaded', function() {
            // ============================================================
            // ELEMENT REFERENCES
            // ============================================================
            const addressSelect = document.getElementById('shipping_address');
            const shippingOptionsContainer = document.getElementById('shipping-options-container');
            const totalWeightInput = document.getElementById('total_weight');
            const shippingCostValue = document.getElementById('shipping-cost-value');
            const shippingCostRow = document.getElementById('shipping-cost-row');
            const totalElement = document.getElementById('total-amount');
            const checkoutForm = document.getElementById('checkout-form');
            const subtotal = {{ $subtotal ?? 0 }};

            // ============================================================
            // UTILITY FUNCTIONS
            // ============================================================

            /**
             * Format a number as USD currency
             */
            function formatCurrency(value) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(value);
            }

            /**
             * Get CSRF token from meta tag
             */
            function getCsrfToken() {
                return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            }

            /**
             * Show loading state in shipping options container
             */
            function showLoading() {
                shippingOptionsContainer.innerHTML =
                    '<p class="text-sm text-gray-500">Loading shipping services...</p>';
            }

            /**
             * Show error state
             */
            function showError(message) {
                shippingOptionsContainer.innerHTML =
                    `<p class="text-sm text-red-500">${message}</p>`;
            }

            /**
             * Show info state (no services)
             */
            function showInfo(message) {
                shippingOptionsContainer.innerHTML =
                    `<p class="text-sm text-gray-500">${message}</p>`;
            }

            // ============================================================
            // MAIN SHIPPING RATE FETCH FUNCTION (FIXED!)
            // ============================================================

            /**
             * Fetch shipping rates from backend
             * FIX: Now sends both address_id AND weight
             * @param {string} addressId - The selected address ID
             */
            function fetchRates(addressId) {
                // Validate inputs
                if (!addressId) {
                    showInfo('Please select a shipping address to view options.');
                    return;
                }

                const totalWeight = totalWeightInput.value.trim();

                if (!totalWeight || parseFloat(totalWeight) <= 0) {
                    showError('Unable to calculate shipping. Please ensure items are in your cart.');
                    return;
                }

                console.log('✅ Fetching rates for:', {
                    addressId: addressId,
                    totalWeight: totalWeight
                });

                showLoading();

                // Call backend API
                fetch('{{ route('en.frontend.cart.getShippingRate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            address_id: addressId,
                            weight: parseFloat(totalWeight) // ✅ THIS WAS MISSING - NOW FIXED!
                        })
                    })
                    .then(response => {
                        // Handle different HTTP status codes
                        if (response.status === 503) {
                            throw new Error('ServiceUnavailable');
                        }
                        if (response.status === 404) {
                            throw new Error('AddressNotFound');
                        }
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.error || data.message || 'Request failed');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('✅ Rates fetched successfully:', data);

                        // Validate response structure
                        if (!data.success) {
                            showError(data.error || 'Failed to fetch shipping rates');
                            return;
                        }

                        if (!data.rates || data.rates.length === 0) {
                            showInfo('No shipping services available for this address.');
                            return;
                        }

                        // Clear previous options
                        shippingOptionsContainer.innerHTML = '';

                        // Render shipping options
                        data.rates.forEach(function(rate, index) {
                            const optionId = `shipping-option-${index}`;
                            const serviceType = rate.service_type || rate.service_name ||
                                'Unknown Service';
                            const serviceName = rate.service_name || serviceType;
                            const deliveryTime = rate.delivery_timestamp || 'TBD';
                            const totalCharge = parseFloat(rate.total_charge || 0);

                            const optionHtml = `
                        <div class="shipping-option flex items-start justify-between p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors" 
                             data-rate="${totalCharge}" 
                             data-currency="${rate.currency || 'USD'}" 
                             data-service-type="${serviceType}">
                            <div class="flex items-start gap-3 flex-1">
                                <input type="radio" 
                                       id="${optionId}" 
                                       name="shipping_option" 
                                       value="${serviceName}" 
                                       class="mt-1"
                                       data-service-type="${serviceType}"
                                       data-rate="${totalCharge}">
                                <div>
                                    <label for="${optionId}" class="block font-medium text-gray-900 cursor-pointer">
                                        ${serviceName}
                                    </label>
                                    <small class="block text-gray-600 mt-1">Est. Delivery: ${deliveryTime}</small>
                                </div>
                            </div>
                            <div class="font-bold text-gray-900 ml-4">
                                ${formatCurrency(totalCharge)}
                            </div>
                        </div>`;

                            shippingOptionsContainer.insertAdjacentHTML('beforeend', optionHtml);
                        });

                        console.log(`✅ ${data.rates.length} shipping options rendered`);
                    })
                    .catch(error => {
                        console.error('❌ Error fetching shipping rates:', error);

                        if (error.message === 'ServiceUnavailable') {
                            showError(
                                'Shipping services are temporarily unavailable. Please try again later or contact support.'
                            );
                        } else if (error.message === 'AddressNotFound') {
                            showError('The selected address could not be found. Please refresh and try again.');
                        } else {
                            showError(
                                'Error loading shipping services. Please ensure your address is complete (zip code, country).'
                            );
                        }

                        console.error('Full error:', error);
                    });
            }

            // ============================================================
            // UPDATE SHIPPING SELECTION
            // ============================================================

            /**
             * Update shipping selection in backend
             * @param {string} addressId 
             * @param {string|null} service 
             * @param {number|null} cost 
             */
            function updateShippingSelection(addressId, service, cost) {
                fetch('{{ route('en.frontend.cart.updateShippingSelection') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
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
                            console.error('❌ Failed to update shipping selection:', data);
                        } else {
                            console.log('✅ Shipping selection updated successfully');
                        }
                    })
                    .catch(error => {
                        console.error('❌ Error updating shipping selection:', error);
                    });
            }

            // ============================================================
            // EVENT LISTENERS
            // ============================================================

            /**
             * Shipping option selection handler
             */
            shippingOptionsContainer.addEventListener('change', function(e) {
                if (e.target.name === 'shipping_option' && e.target.checked) {
                    const shippingOption = e.target.closest('.shipping-option');
                    const rate = parseFloat(e.target.dataset.rate);
                    const serviceType = e.target.dataset.serviceType;
                    const addressId = addressSelect.value;
                    const serviceName = e.target.value;

                    console.log('✅ Shipping option selected:', {
                        service: serviceName,
                        rate: rate,
                        serviceType: serviceType
                    });

                    // Update UI with shipping cost
                    shippingCostRow.style.display = 'flex';
                    shippingCostValue.textContent = formatCurrency(rate);

                    // Update total
                    const newTotal = subtotal + rate;
                    totalElement.textContent = formatCurrency(newTotal);

                    // Update backend
                    updateShippingSelection(addressId, serviceType, rate);

                    // Add visual feedback
                    document.querySelectorAll('.shipping-option').forEach(option => {
                        option.classList.remove('bg-blue-50');
                    });
                    shippingOption.classList.add('bg-blue-50');
                }
            });

            /**
             * Address selection handler
             */
            if (addressSelect) {
                addressSelect.addEventListener('change', function() {
                    const selectedAddressId = this.value;

                    console.log('✅ Address changed to:', selectedAddressId);

                    // Reset shipping options
                    showLoading();
                    shippingCostRow.style.display = 'none';
                    totalElement.textContent = formatCurrency(subtotal);

                    // Fetch new rates for selected address
                    fetchRates(selectedAddressId);

                    // Update backend with new address (shipping cleared)
                    updateShippingSelection(selectedAddressId, null, null);
                });

                // Initial fetch if address is pre-selected
                if (addressSelect.value) {
                    console.log('✅ Initial address:', addressSelect.value);
                    fetchRates(addressSelect.value);
                    updateShippingSelection(addressSelect.value, null, null);
                } else {
                    showInfo('Please select a shipping address to view options.');
                }
            }

            // ============================================================
            // CHECKOUT HANDLER
            // ============================================================

            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validate shipping selection
                    const selectedShipping = document.querySelector(
                        'input[name="shipping_option"]:checked');
                    if (!selectedShipping) {
                        alert('Please select a shipping option before proceeding to checkout.');
                        return;
                    }

                    const checkoutButton = this.querySelector('button[type="submit"]');
                    checkoutButton.disabled = true;
                    checkoutButton.textContent = 'Processing...';

                    console.log('✅ Submitting checkout');

                    fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.redirect_url) {
                                console.log('✅ Checkout successful, redirecting to:', data
                                    .redirect_url);
                                window.location.href = data.redirect_url;
                            } else {
                                alert(data.error || 'An unexpected error occurred.');
                                checkoutButton.disabled = false;
                                checkoutButton.textContent = 'Proceed to Checkout';
                            }
                        })
                        .catch(error => {
                            console.error('❌ Checkout error:', error);
                            alert('An error occurred during checkout. Please try again.');
                            checkoutButton.disabled = false;
                            checkoutButton.textContent = 'Proceed to Checkout';
                        });
                });
            }

            // ============================================================
            // QUANTITY UPDATE HANDLER (Delegated)
            // ============================================================

            document.addEventListener('click', function(e) {
                const quantityBtn = e.target.closest('.quantity-btn');
                if (!quantityBtn) return;

                e.preventDefault();

                const itemId = quantityBtn.dataset.id;
                const action = quantityBtn.dataset.action;

                if (!itemId || !action) {
                    console.error('Missing data-id or data-action on button');
                    return;
                }

                updateQuantity(itemId, action);
            });

            /**
             * Update cart item quantity
             */
            function updateQuantity(itemId, action) {
                const url = `/en/frontend/cart/update-quantity/${itemId}`;

                const quantityBtn = document.querySelector(`.quantity-btn[data-id="${itemId}"]`);
                quantityBtn.disabled = true;

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
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
                            // Reload page to reflect all changes
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                            quantityBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Quantity update error:', error);
                        alert('An error occurred while updating quantity. Please try again.');
                        quantityBtn.disabled = false;
                    });
            }

            // ============================================================
            // CART ITEM REMOVAL
            // ============================================================

            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to remove this item?')) {
                        e.preventDefault();
                    }
                });
            });

            // ============================================================
            // DEBUG: Log initial state
            // ============================================================

            console.log('🎯 Cart Shipping Module initialized', {
                subtotal: subtotal,
                totalWeight: totalWeightInput.value,
                selectedAddress: addressSelect?.value || 'none',
                status: '✅ FIXED - Now sending address_id + weight'
            });
        });
    </script>
@endpush
