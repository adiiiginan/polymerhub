@extends('en.layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-10" style="color:#1A3D7C;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Gambar Produk -->
            <div class="flex justify-center">
                <img src="{{ asset('backend/assets/media/produk/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                    id="product-image" class="w-full max-w-md object-contain border p-2 rounded">

            </div>

            <!-- Detail Produk -->
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $produk->nama_produk }}</h1>
                <p class="text-gray-500 mb-6">Kode : {{ $produk->kode_produk ?? '-' }}</p>

                <p class="mb-2 text-sm">Klik Request Quote jika Anda tidak melihat dimensi yang Anda inginkan</p>

                @if ($jenis_unik->isNotEmpty() && collect($variants_data)->isNotEmpty())
                    <!-- Dropdown Shape -->
                    <label for="shape-select" class="block text-sm font-medium mb-1">* Bentuk</label>
                    <select id="shape-select" name="shape"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 mb-4">
                        <option value="">Select....</option>
                        @foreach ($jenis_unik as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->jenis }}</option>
                        @endforeach
                    </select>

                    <!-- Dropdown Dimensions -->
                    <label for="dimensions-select" class="block text-sm font-medium mb-1">* Dimensions</label>
                    <select id="dimensions-select" name="dimension"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 mb-4"
                        disabled>
                        <option value="">Select Shape First...</option>
                    </select>
                @else
                    <div class="mb-4 p-4 border border-yellow-300 bg-yellow-50 text-yellow-800 rounded">
                        <p>This product does not have available shape or dimension options at the moment. Please
                            contact us or use the "Request Quote" button for more information.</p>
                    </div>
                @endif

                <!-- Price -->
                <div class="mb-4">
                    <p class="text-xl font-bold">Price: <span id="product-price">-</span></p>
                </div>

                <form action="{{ route('en.frontend.cart.add') }}" method="POST" id="cart-form">
                    @csrf
                    <input type="hidden" name="product_stok_id" id="product-stok-id">
                    <!-- QTY -->
                    <div class="flex items-center mb-4">
                        <label for="qty" class="mr-2 font-medium">Quantity</label>
                        <div class="flex items-center border border-gray-300 rounded-md shadow-sm">
                            <button type="button" id="qty-minus"
                                class="px-3 py-1 text-lg text-gray-600 hover:bg-gray-100 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">-</button>
                            <input type="number" id="qty" name="qty" value="1" min="1"
                                class="w-16 border-0 text-center p-2 focus:ring-0">
                            <button type="button" id="qty-plus"
                                class="px-3 py-1 text-lg text-gray-600 hover:bg-gray-100 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500">+</button>
                        </div>

                        <button type="submit" id="btn-add-to-cart"
                            class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-sm"
                            style="display: none;">
                            Add to Cart
                        </button>

                        <a href="{{ route('en.frontend.contact') }}" id="btn-contact-us"
                            class="ml-4 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md shadow-sm"
                            style="display: none;">
                            Contact Us
                        </a>
                    </div>
                </form>

                <div class="mb-4">
                    <p id="stock-display" class="text-sm" style="display: none;">Stok Tersedia: <span
                            id="stock-value"></span>
                    </p>
                </div>

                <!-- Tombol -->
                <div class="flex gap-4">
                    <!-- Request Quote -->
                    <button id="btn-request-quote"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow-sm">Permintaan
                        Quote</button>
                </div>
            </div>
        </div>

        <!-- Deskripsi & Spesifikasi -->
        <div class="mt-12 w-full">
            <!-- Deskripsi -->
            <div class="border border-gray-200 rounded-lg mb-10">
                <button id="description-toggle"
                    class="flex items-center justify-between w-full p-4 text-xl font-bold text-left cursor-pointer select-none hover:bg-gray-50 rounded-t-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    aria-expanded="true" aria-controls="description-content">
                    <span style="color:#1A3D7C;">Description</span>
                    <span id="description-icon" class="transform transition-transform duration-300 rotate-180">
                        <svg class="w-6 h-6" style="color:#1A3D7C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </span>
                </button>
                <div id="description-content" class="overflow-hidden transition-all duration-500 ease-in-out">
                    <div class="p-4 border-t border-gray-200">
                        <p class="text-gray-700 leading-relaxed">
                            {!! nl2br(e($produk->deskripsi)) !!}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Spesifikasi Material -->
            <div style="color:#1A3D7C;">
                <h2 class="text-xl font-bold mb-4">Spesifikasi Material</h2>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full text-sm divide-y divide-gray-200">
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-medium">Temperature - Range</td>
                                <td class="p-3 text-gray-700">{{ $produk->tempratur ?? '-' }}</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-medium">Max PV (Continuous)</td>
                                <td class="p-3 text-gray-700">{{ $produk->max_pv ?? '-' }}</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-medium">Maximum P - MPa</td>
                                <td class="p-3 text-gray-700">{{ $produk->maximum_p ?? '-' }}</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-medium">Max V (no load)</td>
                                <td class="p-3 text-gray-700">{{ $produk->max_v ?? '-' }}</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-medium">Shaft Hardness - Minimum</td>
                                <td class="p-3 text-gray-700">{{ $produk->hardness ?? 'Rb25' }}</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-medium">Friction - Static & dynamic</td>
                                <td class="p-3 text-gray-700">{{ $produk->friction ?? '-' }}</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-medium">Elongation ASTM D638</td>
                                <td class="p-3 text-gray-700">{{ $produk->elongation ?? '-' }}</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-medium">Tensile Strenght ASTM D638 Mpa</td>
                                <td class="p-3 text-gray-700">{{ $produk->tensile ?? '-' }}</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-medium">Deformation Under Loadx</td>
                                <td class="p-3 text-gray-700">{{ $produk->deformation ?? '-' }}</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-medium">Spesific Gravity</td>
                                <td class="p-3 text-gray-700">{{ $produk->spesific ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                /* ===============================
                 * ELEMENTS
                 * =============================== */
                const shapeSelect = document.getElementById('shape-select');
                const dimensionSelect = document.getElementById('dimensions-select');
                const productImage = document.getElementById('product-image');
                const priceSpan = document.getElementById('product-price');
                const stockDisplay = document.getElementById('stock-display');
                const stockValue = document.getElementById('stock-value');
                const addToCartButton = document.getElementById('btn-add-to-cart');
                const contactUsButton = document.getElementById('btn-contact-us');
                const productStokIdInput = document.getElementById('product-stok-id');
                const cartForm = document.getElementById('cart-form');
                const qtyInput = document.getElementById('qty');
                const qtyPlus = document.getElementById('qty-plus');
                const qtyMinus = document.getElementById('qty-minus');
                const descriptionToggle = document.getElementById('description-toggle');
                const descriptionContent = document.getElementById('description-content');
                const descriptionIcon = document.getElementById('description-icon');

                const variants = {!! json_encode($variants_data) !!};
                const defaultImage = productImage ? productImage.src : '';

                /* ===============================
                 * HELPER FUNCTIONS
                 * =============================== */

                function formatPrice(price) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(price);
                }

                function resetProductView() {
                    priceSpan.textContent = '-';
                    stockDisplay.style.display = 'none';
                    addToCartButton.style.display = 'none';
                    contactUsButton.style.display = 'none';
                    productStokIdInput.value = '';
                    productImage.src = defaultImage;
                }

                /* ===============================
                 * UPDATE DIMENSIONS BY SHAPE
                 * =============================== */
                function updateDimensions(shapeId) {
                    dimensionSelect.innerHTML = '<option value="">Pilih Dimensi...</option>';
                    dimensionSelect.disabled = true;

                    if (!shapeId) return;

                    const filtered = variants.filter(v => v.jenis_id == shapeId);

                    if (filtered.length) {
                        filtered.forEach(v => {
                            const option = document.createElement('option');
                            option.value = v.id;
                            option.textContent = v.dimensi;
                            dimensionSelect.appendChild(option);
                        });
                        dimensionSelect.disabled = false;
                    }
                }

                /* ===============================
                 * UPDATE PRODUCT DETAIL
                 * =============================== */
                function updateProductDetails(variantId) {
                    resetProductView();

                    const variant = variants.find(v => v.id == variantId);
                    if (!variant) return;

                    // PRICE
                    const price = parseFloat(variant.hargi || 0);
                    if (price > 0) {
                        priceSpan.textContent = formatPrice(price);
                        addToCartButton.style.display = 'inline-block';
                        contactUsButton.style.display = 'none';
                        addToCartButton.disabled = variant.stok <= 0;
                    } else {
                        priceSpan.textContent = 'Hubungi untuk harga';
                        addToCartButton.style.display = 'none';
                        contactUsButton.style.display = 'inline-block';
                    }

                    // STOCK
                    stockValue.textContent = variant.stok;
                    stockDisplay.style.display = 'block';

                    // IMAGE
                    productImage.src = variant.gambar ?
                        '{{ asset('backend/assets/media/produk/') }}/' + variant.gambar :
                        defaultImage;

                    productStokIdInput.value = variant.id;
                }

                /* ===============================
                 * EVENTS
                 * =============================== */

                if (shapeSelect) {
                    shapeSelect.addEventListener('change', function() {
                        updateDimensions(this.value);
                        resetProductView();
                    });
                }

                if (dimensionSelect) {
                    dimensionSelect.addEventListener('change', function() {
                        updateProductDetails(this.value);
                    });
                }

                if (cartForm) {
                    cartForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        addToCartButton.disabled = true;
                        addToCartButton.textContent = 'Loading...';

                        fetch(this.action, {
                                method: 'POST',
                                body: new FormData(this),
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        text: data.message || "Produk berhasil ditambahkan.",
                                        icon: "success",
                                        confirmButtonText: "OK"
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href =
                                                "{{ route('en.frontend.cart.index') }}";
                                        }
                                    });
                                } else {
                                    if (data.redirect) {
                                        Swal.fire({
                                            text: data.message,
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Login',
                                            cancelButtonText: 'Batal'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = data.redirect;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            text: data.message || "Gagal menambahkan produk.",
                                            icon: "error",
                                            confirmButtonText: "OK"
                                        });
                                    }
                                }
                            })
                            .catch(() => {
                                alert('Terjadi kesalahan');
                            })
                            .finally(() => {
                                addToCartButton.textContent = 'Masukan Keranjang';
                                addToCartButton.disabled = false;
                            });
                    });
                }

                /* ===============================
                 * QTY INPUT HANDLER
                 * =============================== */
                if (qtyPlus && qtyMinus && qtyInput) {
                    qtyPlus.addEventListener('click', () => {
                        qtyInput.value = parseInt(qtyInput.value) + 1;
                    });

                    qtyMinus.addEventListener('click', () => {
                        const currentValue = parseInt(qtyInput.value);
                        if (currentValue > 1) {
                            qtyInput.value = currentValue - 1;
                        }
                    });
                }

                /* ===============================
                 * DESCRIPTION ACCORDION
                 * =============================== */
                if (descriptionToggle && descriptionContent && descriptionIcon) {
                    // Set initial state based on aria-expanded attribute
                    const isInitiallyExpanded = descriptionToggle.getAttribute('aria-expanded') === 'true';
                    if (isInitiallyExpanded) {
                        descriptionContent.style.maxHeight = descriptionContent.scrollHeight + 'px';
                    } else {
                        descriptionContent.style.maxHeight = '0px';
                    }

                    descriptionToggle.addEventListener('click', () => {
                        const isExpanded = descriptionToggle.getAttribute('aria-expanded') === 'true';

                        if (isExpanded) {
                            descriptionContent.style.maxHeight = '0px';
                            descriptionIcon.classList.remove('rotate-180');
                            descriptionToggle.setAttribute('aria-expanded', 'false');
                        } else {
                            descriptionContent.style.maxHeight = descriptionContent.scrollHeight + 'px';
                            descriptionIcon.classList.add('rotate-180');
                            descriptionToggle.setAttribute('aria-expanded', 'true');
                        }
                    });
                }

            });
        </script>
    @endpush
@endsection
