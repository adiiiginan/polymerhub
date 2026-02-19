@extends('layouts.app')

@section('content')
    <style>
        /* ... existing styles ... */
        .shipping-options {
            margin-top: 20px;
        }

        .shipping-option {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .shipping-option.selected {
            border-color: #1e3a8a;
            background-color: #f0f8ff;
        }

        #shipping-loader {
            text-align: center;
            padding: 20px;
            display: none;
            /* Hidden by default */
        }
    </style>

    <div class="container mx-auto px-4 py-8">

        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('welcome') }}">Home</a> > Keranjang Belanja
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @php
            $cartItems = [];
            if (Auth::guard('customer')->check() && isset($cart) && $cart->items) {
                $cartItems = $cart->items;
            }
        @endphp

        @if (count($cartItems) > 0)
            <div class="cart-layout">
                <!-- Cart Items -->
                <div class="cart-items">
                    <!-- ... existing cart items loop ... -->
                </div>

                <!-- Order Summary -->
                <div class="cart-total">
                    <h3 style="font-size: 20px; font-weight: 600; color: #1f2937; margin-bottom: 25px;">
                        Ringkasan Pesanan
                    </h3>

                    @php
                        $isLoggedIn = Auth::guard('customer')->check();
                        $subtotal = collect($cartItems)->sum(function ($details) {
                            return $details->harga * $details->qty;
                        });
                        $totalGrossWeight = collect($cartItems)->sum(function ($details) {
                            return $details->gros * $details->qty;
                        });
                    @endphp

                    <div class="total-row">
                        <span>Subtotal ({{ collect($cartItems)->sum('qty') }} produk)</span>
                        <span>Rp {{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>

                    <div class="total-row">
                        <span>Total Berat Kotor</span>
                        <span>{{ number_format($totalGrossWeight, 2) }} kg</span>
                    </div>

                    <hr>

                    @if ($isLoggedIn && count($userAddresses) > 0)
                        <div class="mb-3">
                            <label for="address-selector" class="form-label fw-bold">Alamat Pengiriman</label>
                            <select id="address-selector" name="address_id" class="form-select">
                                @foreach ($userAddresses as $address)
                                    <option value="{{ $address->id }}"
                                        {{ $activeAddress && $activeAddress->id == $address->id ? 'selected' : '' }}>
                                        {{ $address->address_name }} - {{ Str::limit($address->address_line_1, 20) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($isLoggedIn)
                        <div class="alert alert-warning">Silakan tambahkan alamat pengiriman di profil Anda untuk menghitung
                            ongkos kirim.</div>
                    @endif

                    <div id="shipping-options-container">
                        {{-- Opsi pengiriman akan diisi oleh AJAX --}}
                    </div>

                    <div id="shipping-loader">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Menghitung ongkos kirim...</p>
                    </div>

                    <div class="total-row mt-3">
                        <span>Ongkos Kirim</span>
                        <span id="shipping-cost">-</span>
                    </div>

                    <div class="total-row final">
                        <span>Total</span>
                        <span id="cart-total">Rp {{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>

                    <a href="#" class="checkout-btn" id="checkout-btn">Lanjutkan ke Checkout</a>

                    <a href="{{ route('frontend.produk') }}" class="continue-shopping">
                        ← Lanjutkan Belanja
                    </a>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="empty-cart">
                <h3>Keranjang belanja Anda kosong</h3>
                <p>Sepertinya Anda belum menambahkan produk apa pun ke keranjang.</p>
                <a href="{{ route('frontend.produk') }}" class="shop-now-btn">Mulai Belanja</a>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const subtotal = {{ $subtotal ?? 0 }};
            const totalGrossWeight = {{ $totalGrossWeight ?? 0 }};
            const checkoutBtn = $('#checkout-btn');

            // Disable checkout button initially
            checkoutBtn.addClass('disabled').prop('disabled', true);

            function formatCurrency(value) {
                return 'Rp ' + parseFloat(value).toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function updateTotal() {
                const selectedRate = parseFloat($('.shipping-option.selected').attr('data-rate')) || 0;
                const total = subtotal + selectedRate;
                $('#shipping-cost').text(selectedRate > 0 ? formatCurrency(selectedRate) : '-');
                $('#cart-total').text(formatCurrency(total));
            }

            function fetchLionParcelServices() {
                const addressId = $('#address-selector').val();

                if (!addressId) {
                    $('#shipping-options-container').html(
                        '<div class="alert alert-info mt-3">Pilih alamat untuk melihat opsi pengiriman.</div>');
                    $('#shipping-loader').hide();
                    checkoutBtn.addClass('disabled').prop('disabled', true);
                    updateTotal();
                    return;
                }

                if (!totalGrossWeight || totalGrossWeight <= 0) {
                    $('#shipping-options-container').html(
                        '<div class="alert alert-warning mt-3">Tidak ada produk di keranjang untuk dihitung beratnya.</div>'
                    );
                    $('#shipping-loader').hide();
                    updateTotal();
                    return;
                }

                $('#shipping-loader').show();
                $('#shipping-options-container').empty();
                checkoutBtn.addClass('disabled').prop('disabled', true); // Disable on refresh
                updateTotal();

                $.ajax({
                    url: '{{ route('id.frontend.lionparcel.services') }}',
                    type: 'GET',
                    data: {
                        weight: totalGrossWeight,
                        address_id: addressId
                    },
                    success: function(response) {
                        $('#shipping-loader').hide();
                        if (response.success && response.data.length > 0) {
                            let optionsHtml =
                                '<h5 class="mt-3 fw-bold">Opsi Pengiriman</h5><div class="shipping-options">';
                            response.data.forEach(function(rate, index) {
                                // FIX: Store service code in data-service-code and use it for the radio value
                                optionsHtml += `
                                    <div class="shipping-option" data-rate="${rate.price}" data-service-code="${rate.serviceCode}">
                                        <div>
                                            <input type="radio" name="shipping_option" id="shipping_${index}" value="${rate.serviceCode}" class="me-2">
                                            <label for="shipping_${index}" style="cursor:pointer;">
                                                <strong>${rate.serviceName}</strong>
                                                <small class="d-block text-muted">Est. Sampai: ${rate.etd}</small>
                                            </label>
                                        </div>
                                        <div class="fw-bold">${formatCurrency(rate.price)}</div>
                                    </div>
                                `;
                            });
                            optionsHtml += '</div>';
                            $('#shipping-options-container').html(optionsHtml);
                        } else {
                            $('#shipping-options-container').html(
                                `<div class="alert alert-danger mt-3">${response.message || 'Gagal mendapatkan opsi pengiriman.'}</div>`
                            );
                        }
                    },
                    error: function(xhr) {
                        $('#shipping-loader').hide();
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message : 'Terjadi kesalahan saat mengambil data ongkir.';
                        $('#shipping-options-container').html(
                            `<div class="alert alert-danger mt-3">${errorMsg}</div>`
                        );
                    }
                });
            }

            // Event listener for selecting a shipping option
            $(document).on('click', '.shipping-option', function() {
                $(this).find('input[type="radio"]').prop('checked', true);
                $('.shipping-option').removeClass('selected');
                $(this).addClass('selected');
                updateTotal();
                saveShippingDetails(); // Save shipping details on selection
            });

            function saveShippingDetails() {
                const selectedAddress = $('#address-selector').val();
                const selectedOption = $('.shipping-option.selected');

                if (!selectedAddress || selectedOption.length === 0) {
                    checkoutBtn.addClass('disabled').prop('disabled', true);
                    return;
                }

                const shippingService = selectedOption.data('service-code');
                const shippingCost = selectedOption.data('rate');

                $.ajax({
                    url: '{{ route('cart.saveShippingDetails') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        address_id: selectedAddress,
                        shipping_service: shippingService,
                        shipping_cost: shippingCost
                    },
                    success: function(response) {
                        if (response.success) {
                            checkoutBtn.removeClass('disabled').prop('disabled',
                            false); // Enable checkout
                            // Optionally update total from server response
                            $('#cart-total').text('Rp ' + response.total);
                        } else {
                            alert(response.message || 'Gagal menyimpan detail pengiriman.');
                            checkoutBtn.addClass('disabled').prop('disabled', true);
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat menyimpan detail pengiriman.');
                        checkoutBtn.addClass('disabled').prop('disabled', true);
                    }
                });
            }

            // Add event listener for address change
            $('#address-selector').on('change', function() {
                fetchLionParcelServices();
                checkoutBtn.addClass('disabled').prop('disabled',
                true); // Disable until shipping is selected
            });

            // Initial fetch on page load
            if ($('#address-selector').val()) {
                fetchLionParcelServices();
            }

            // Checkout button handler
            checkoutBtn.on('click', function(e) {
                e.preventDefault();

                if ($(this).hasClass('disabled')) {
                    alert('Silakan pilih alamat dan layanan pengiriman terlebih dahulu.');
                    return;
                }

                // Show loading state
                $(this).html('Memproses... <i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

                // Simple POST to checkout, data is already saved
                $.ajax({
                    url: '{{ route('checkout.lionparcel') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success' && response.redirect_url) {
                            window.location.href = response.redirect_url;
                        } else {
                            alert(response.message || 'Checkout gagal. Silakan coba lagi.');
                            checkoutBtn.html('Lanjutkan ke Checkout').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message :
                            'Terjadi kesalahan. Periksa kembali pilihan Anda.';
                        alert('Error: ' + errorMsg);
                        checkoutBtn.html('Lanjutkan ke Checkout').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
