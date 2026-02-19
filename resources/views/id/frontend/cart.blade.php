@extends('id.layouts.app')

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
            <a href="{{ route('id.frontend.produk') }}">Home</a> > Shopping Cart
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
            $subtotal = 0;
            $final_gross_weight = 0;
            $initialShippingCost = 0;
            $initialShippingService = null;

            if ($cartItems && $cartItems->count() > 0) {
                $subtotal = $cartItems->sum(fn($item) => ($item->price ?? 0) * $item->qty);
                $final_gross_weight = $cartItems->sum(fn($item) => ($item->gros ?? 0) * $item->qty);

                // For logged-in users, get pre-selected shipping info from the cart object
                if (Auth::guard('customer')->check() && isset($cart)) {
                    $initialShippingCost = $cart->shipping_cost ?? 0;
                    $initialShippingService = $cart->shipping_service;
                }
            }

            $total = $subtotal + $initialShippingCost;
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
                        <div class="cart-item">
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
                                Rp. {{ number_format($item->price ?? 0, 2) }}
                            </div>
                            <div class="item-quantity hidden lg:flex items-center justify-center"
                                style="margin-left: -10px;">

                                <span class="quantity-value mx-2">{{ $item->qty }}</span>

                            </div>
                            <div class="item-total-price hidden lg:block text-right">
                                Rp. {{ number_format(($item->price ?? 0) * $item->qty, 2) }}
                            </div>

                            <!-- Remove Button -->
                            <div class="remove-btn-wrapper">
                                <form action="{{ route('id.frontend.cart.remove', $item->id) }}" method="POST">
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
                                            <option value="{{ $address->id }}" data-zip="{{ $address->zip_code }}"
                                                {{ session('selected_address_id') == $address->id ? 'selected' : '' }}>
                                                {{ $address->alamat }}, {{ $address->city }},
                                                {{ $address->state }} {{ $address->zip_code }},
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
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Opsi Pengiriman</h3>
                            <div id="shipping-options-container" class="mt-2 space-y-3">
                                {{-- Opsi pengiriman akan diisi oleh AJAX --}}
                            </div>
                            <div id="shipping-loader" style="display: none; margin-top: 15px; text-align: center;">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Menghitung ongkos kirim...</p>
                            </div>
                            <input type="hidden" id="total_weight"
                                value="{{ number_format($final_gross_weight, 2, ',', '.') }}">
                        </div>

                        <!-- Order Summary -->
                        <div class="cart-total">
                            <h3 style="font-size: 20px; font-weight: 600; color: #1f2937; margin-bottom: 25px;">
                                Ringkasan Pesanan
                            </h3>
                            <div class="total-row">
                                <span>Gross</span>
                                <span>{{ number_format($final_gross_weight, 2, ',', '.') }} kg</span>
                            </div>
                            <div class="total-row">
                                <span>Subtotal ({{ $cartItems->sum('qty') }} items)</span>
                                <span>Rp. {{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="total-row">
                                <span>Ongkos Kirim</span>
                                <span
                                    id="shipping-cost">{{ $initialShippingCost > 0 ? 'Rp. ' . number_format($initialShippingCost, 0, ',', '.') : '-' }}</span>
                            </div>
                            <div class="total-row final">
                                <span>Total</span>
                                <span id="cart-total">Rp. {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <a href="#" class="checkout-btn" id="checkout-btn">Lanjutkan ke Checkout</a>
                            <a href="{{ route('id.frontend.produk') }}" class="continue-shopping">
                                ← Lanjutkan Belanja
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="empty-cart">
                <h3>Keranjang belanja Anda kosong</h3>
                <p>Sepertinya Anda belum menambahkan produk apa pun ke keranjang.</p>
                <a href="{{ route('id.frontend.produk') }}" class="shop-now-btn">Mulai Belanja</a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const csrfToken = '{{ csrf_token() }}';
            const subtotal = parseFloat('{{ $subtotal ?? 0 }}');
            let selectedShippingCost = parseFloat('{{ $initialShippingCost ?? 0 }}');
            let selectedShippingService = '{{ $initialShippingService ?? '' }}';

            function formatRupiah(number) {
                return 'Rp. ' + number.toLocaleString('id-ID');
            }

            function updateSummary() {
                const total = subtotal + selectedShippingCost;
                $('#shipping-cost').text(formatRupiah(selectedShippingCost));
                $('#cart-total').text(formatRupiah(total));
            }

            function fetchShippingRates(addressId) {
                const weight = parseFloat($('#total_weight').val());
                if (!addressId || weight <= 0) {
                    $('#shipping-options-container').html(
                        '<p class="text-sm text-gray-500">Pilih alamat untuk melihat opsi pengiriman.</p>');
                    return;
                }

                $('#shipping-loader').show();
                $('#shipping-options-container').empty();

                $.post('{{ route('id.frontend.lionparcel.get-services') }}', {
                    address_id: addressId,
                    weight: weight
                }, function(res) {
                    $('#shipping-loader').hide();
                    if (res.success && res.data.length > 0) {
                        let html = '<div class="space-y-3">';
                        res.data.forEach(service => {
                            const isChecked = service.serviceCode == selectedShippingService ?
                                'checked' : '';
                            const labelClasses = service.serviceCode == selectedShippingService ?
                                'border-blue-500 bg-blue-50' : '';
                            html += `
                               <label class="flex justify-between border p-3 rounded-lg cursor-pointer hover:border-blue-500 transition-all shipping-option-label ${labelClasses}">
        <div class="flex items-center">
            <input type="radio" name="shipping_option" class="mr-3"
                   value="${service.serviceCode}"
                   data-cost="${service.price}" ${isChecked}>
            <div>
                <div class="font-medium">${service.serviceName}</div>
                <div class="text-xs text-gray-500">
                    ${service.etd ? 'Estimasi: ' + service.etd : 'Estimasi belum tersedia'}
                </div>
            </div>
        </div>
        <div class="font-semibold">${formatRupiah(service.price)}</div>
    </label>
                            `;
                        });
                        html += '</div>';
                        $('#shipping-options-container').html(html);
                    } else {
                        $('#shipping-options-container').html(
                            '<p class="text-sm text-red-500">Opsi pengiriman tidak tersedia untuk alamat ini.</p>'
                        );
                    }
                }).fail(function() {
                    $('#shipping-loader').hide();
                    $('#shipping-options-container').html(
                        '<p class="text-sm text-red-500">Gagal memuat ongkos kirim. Silakan coba lagi.</p>'
                    );
                });
            }

            // 1. Event handler saat alamat diganti
            $('#shipping_address').on('change', function() {
                const addressId = $(this).val();
                selectedShippingCost = 0; // Reset ongkir
                selectedShippingService = ''; // Reset service
                updateSummary();

                if (!addressId) {
                    $('#shipping-options-container').html(
                        '<p class="text-sm text-gray-500">Pilih alamat untuk melihat opsi pengiriman.</p>'
                    );
                    return;
                }

                // Simpan alamat baru dan reset ongkir di backend
                $.post('{{ route('id.frontend.cart.setAddress') }}', {
                    address_id: addressId
                }, function(response) {
                    if (response.success) {
                        console.log('Address updated, fetching new rates...');
                        fetchShippingRates(addressId);
                    } else {
                        alert('Gagal memperbarui alamat. Silakan coba lagi.');
                    }
                }).fail(function() {
                    alert('Terjadi kesalahan saat memperbarui alamat.');
                });
            });

            // 2. Event handler saat memilih opsi pengiriman
            $(document).on('change', 'input[name="shipping_option"]', function() {
                const service = $(this).val();
                const cost = parseFloat($(this).data('cost'));
                const addressId = $('#shipping_address').val();
                const checkoutBtn = $('#checkout-btn');

                selectedShippingCost = cost;
                updateSummary();

                // Tandai yang dipilih
                $('.shipping-option-label').removeClass('border-blue-500 bg-blue-50');
                $(this).closest('label').addClass('border-blue-500 bg-blue-50');

                // Nonaktifkan tombol checkout selama proses penyimpanan
                checkoutBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed').text(
                    'Menyimpan Pengiriman...');

                // Simpan detail pengiriman ke backend
                $.post('{{ route('id.frontend.cart.setShipping') }}', {
                    address_id: addressId,
                    shipping_service: service,
                    shipping_cost: cost
                }, function(response) {
                    if (response.success) {
                        console.log('Shipping information saved.');
                        // Aktifkan kembali tombol checkout setelah berhasil
                        checkoutBtn.prop('disabled', false).removeClass(
                            'opacity-50 cursor-not-allowed').text('Lanjutkan ke Checkout');
                    } else {
                        alert('Gagal menyimpan informasi pengiriman.');
                        checkoutBtn.prop('disabled', false).removeClass(
                            'opacity-50 cursor-not-allowed').text('Lanjutkan ke Checkout');
                    }
                }).fail(function() {
                    alert('Terjadi kesalahan saat menyimpan pengiriman.');
                    checkoutBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed')
                        .text('Lanjutkan ke Checkout');
                });
            });

            // Initial load
            if ($('#shipping_address').val()) {
                $('#shipping_address').trigger('change');
            }

            /* ================= CHECKOUT ================= */
            $('#checkout-btn').on('click', function(e) {
                e.preventDefault();
                const btn = $(this);

                // Langsung nonaktifkan untuk mencegah klik ganda
                btn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed').text('Memproses...');

                if (!$('input[name="shipping_option"]:checked').length) {
                    alert('Silakan pilih metode pengiriman terlebih dahulu.');
                    btn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed').text(
                        'Lanjutkan ke Checkout');
                    return;
                }

                $.post('{{ route('id.frontend.cart.checkout-lion-parcel') }}')
                    .done(res => {
                        if (res.status === 'success') {
                            // Redirect tanpa mengaktifkan kembali tombol
                            window.location.href = res.redirect_url;
                        } else {
                            alert(res.message || 'Terjadi kesalahan saat checkout.');
                            btn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed')
                                .text('Lanjutkan ke Checkout');
                        }
                    })
                    .fail(xhr => {
                        alert(xhr.responseJSON?.message ||
                            'Checkout gagal. Pastikan semua informasi sudah benar.');
                        btn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed').text(
                            'Lanjutkan ke Checkout');
                    });
            });
        });
    </script>
@endpush
