@extends('en.layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-10" style="color:#1A3D7C;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Product Image -->
            <div class="flex justify-center">
                <img src="{{ asset('backend/assets/media/produk/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                    id="product-image" class="w-full max-w-md object-contain border p-2 rounded">
            </div>

            <!-- Product Details -->
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $produk->nama_produk }}</h1>
                <p class="text-gray-500 mb-6">Part Number: {{ $produk->kode_produk ?? '-' }}</p>

                <p class="mb-2 text-sm">Click on Request Quote if you do not see the dimension of your choice</p>

                <!-- Shape Dropdown -->
                <label class="block text-sm font-medium mb-1">* Shape</label>
                <select id="shape-select" class="w-full border rounded p-2 mb-4">
                    <option value="">Select...</option>
                    @foreach ($jenis_unik as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->jenis }}</option>
                    @endforeach
                </select>

                <!-- Dimensions Dropdown -->
                <label class="block text-sm font-medium mb-1">* Dimensions</label>
                <select id="dimensions-select" class="w-full border rounded p-2 mb-4" disabled>
                    <option value="">Select Shape First...</option>
                </select>

                <!-- Price -->
                <div class="mb-4">
                    <p class="text-xl font-bold">Price: <span id="product-price">-</span></p>
                </div>

                <form action="{{ route('en.frontend.cart.add') }}" method="POST" id="cart-form">
                    @csrf
                    <input type="hidden" name="product_stok_id" id="product-stok-id">
                    <!-- QTY -->
                    <div class="flex items-center mb-4">
                        <label class="mr-2 font-medium">QTY</label>
                        <input type="number" id="qty" name="qty" value="1" min="1"
                            class="w-20 border rounded text-center p-2">

                        <button type="submit" id="btn-add-to-cart"
                            class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded" style="display: none;">
                            Add to Cart
                        </button>

                        <a href="{{ route('en.contact') }}" id="btn-contact-us"
                            class="ml-4 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded" style="display: none;">
                            Contact Us
                        </a>
                    </div>
                </form>

                <div class="mb-4">
                    <p id="stock-display" class="text-sm" style="display: none;">Stock available: <span
                            id="stock-value"></span></p>
                </div>

                <!-- Tombol -->
                <div class="flex gap-4">
                    <!-- Request Quote -->
                    <button id="btn-request-quote"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Request Quote</button>
                </div>
            </div>
        </div>

        <!-- Spesifikasi Material -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Kolom Kiri: Spesifikasi -->
            <div style="width: 98%;margin-left: 28px; color:#1A3D7C;">
                <h2 class="text-xl font-bold mb-4">Typical Characteristics of Material</h2>
                <table class="w-full border text-sm">
                    <tbody>
                        <tr class="border">
                            <td class="p-2 font-medium">Temperature - Range</td>
                            <td class="p-2">{{ $produk->tempratur ?? '-' }}</td>
                        </tr>
                        <tr class="border">
                            <td class="p-2 font-medium">Max PV (Continuous)</td>
                            <td class="p-2">{{ $produk->max_pv ?? '-' }}</td>
                        </tr>
                        <tr class="border">
                            <td class="p-2 font-medium">Maximum P - MPa</td>
                            <td class="p-2">{{ $produk->maximum_p ?? '-' }}</td>
                        </tr>
                        <tr class="border">
                            <td class="p-2 font-medium">Max V (no load)</td>
                            <td class="p-2">{{ $produk->max_v ?? '-' }}</td>
                        </tr>
                        <tr class="border">
                            <td class="p-2 font-medium">Shaft Hardness - Minimum</td>
                            <td class="p-2">{{ $produk->hardness ?? 'Rb25' }}</td>
                        </tr>
                        <tr class="border">
                            <td class="p-2 font-medium">Friction - Static & dynamic</td>
                            <td class="p-2">{{ $produk->friction ?? '-' }}</td>
                        </tr>
                        <tr class="border">
                            <td class="p-2 font-medium">Elongation ASTM D638</td>
                            <td class="p-2">{{ $produk->elongation ?? '-' }}</td>
                        </tr>
                        <tr class="border">
                            <td class="p-2 font-medium">Tensile Strenght ASTM D638 Mpa</td>
                            <td class="p-2">{{ $produk->tensile ?? '-' }}</td>
                        </tr>
                        <tr class="border">
                            <td class="p-2 font-medium">Deformation Under Loadx</td>
                            <td class="p-2">{{ $produk->deformation ?? '-' }}</td>
                        </tr>
                        <tr class="border">
                            <td class="p-2 font-medium">Spesific Gravity</td>
                            <td class="p-2">{{ $produk->spesific ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Kolom Kanan: Deskripsi -->
            <div>
                <h2 id="description-toggle" class="flex items-center text-xl font-bold mb-4 cursor-pointer select-none">
                    <span style="color:#1A3D7C;"> Description</span>
                    <span id="description-icon" class="ml-2 transform transition-transform duration-300 inline-block"
                        aria-hidden="true" style="color:#1A3D7C;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" style="display:block;">
                            <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                </h2>
                <div id="description-content"
                    class="text-gray-700 leading-relaxed max-h-0 overflow-hidden transition-all duration-300 font-bold"
                    style="color:#1A3D7C; margin-bottom: 82px;">
                    <p class="mt-2">
                        {!! nl2br(e($produk->deskripsi)) !!}
                    </p>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const shapeSelect = document.getElementById('shape-select');
                const dimensionSelect = document.getElementById('dimensions-select');
                const productImage = document.getElementById('product-image');
                const priceSpan = document.getElementById('product-price');
                const addToCartButton = document.getElementById('btn-add-to-cart');
                const stockDisplay = document.getElementById('stock-display');
                const stockValue = document.getElementById('stock-value');
                const variants = {!! json_encode($variants_data) !!};
                const defaultImage = productImage.src;

                const descriptionToggle = document.getElementById('description-toggle');
                const descriptionContent = document.getElementById('description-content');
                const descriptionIcon = document.getElementById('description-icon');

                if (descriptionToggle) {
                    descriptionToggle.addEventListener('click', function() {
                        if (descriptionContent.style.maxHeight) {
                            descriptionContent.style.maxHeight = null;
                        } else {
                            descriptionContent.style.maxHeight = descriptionContent.scrollHeight + "px";
                        }
                        descriptionIcon.classList.toggle('rotate-90');
                    });
                }

                const productStokIdInput = document.getElementById('product-stok-id');
                const cartForm = document.querySelector('form[action="{{ route('en.frontend.cart.add') }}"]');

                if (cartForm) {
                    cartForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(this);
                        addToCartButton.disabled = true;
                        addToCartButton.textContent = 'Adding...';

                        fetch('{{ route('en.frontend.cart.add') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.location.href = '{{ route('en.frontend.cart.index') }}';
                                } else if (data.redirect) {
                                    window.location.href = data.redirect;
                                } else {
                                    alert(data.message || 'An error occurred. Please try again.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred. Please try again.');
                            })
                            .finally(() => {
                                addToCartButton.textContent = 'Add to Cart';
                                // Re-evaluate button state based on stock
                                const selectedVariant = variants.find(v => v.id == dimensionSelect.value);
                                if (selectedVariant) {
                                    addToCartButton.disabled = selectedVariant.stok <= 0;
                                }
                            });
                    });
                }

                function updateProductDetails(variantId) {
                    const selectedVariant = variants.find(v => v.id == variantId);
                    const addToCartButton = document.getElementById('btn-add-to-cart');
                    const contactUsButton = document.getElementById('btn-contact-us');
                    const cartForm = document.getElementById('cart-form');



                    if (selectedVariant) {
                        // Robust price parsing
                        let rawPrice = selectedVariant.harga ? selectedVariant.harga.toString() : "0";
                        let cleanedPrice = rawPrice.replace(/[^\d,.]/g, '');
                        const lastComma = cleanedPrice.lastIndexOf(',');
                        const lastDot = cleanedPrice.lastIndexOf('.');
                        let price = 0;

                        if (lastComma > lastDot) {
                            price = parseFloat(cleanedPrice.replace(/\./g, '').replace(',', '.'));
                        } else if (lastDot > lastComma) {
                            price = parseFloat(cleanedPrice.replace(/,/g, ''));
                        } else {
                            price = parseFloat(cleanedPrice.replace(',', '.'));
                        }

                        // Update UI based on price
                        if (!isNaN(price)) {
                            // Format to "USD $XX.XX"
                            const formattedPrice = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD'
                            }).format(price);
                            priceSpan.textContent = formattedPrice;

                        } else {
                            priceSpan.textContent = 'Contact for price';
                        }
                        addToCartButton.style.display = 'inline-block';
                        contactUsButton.style.display = 'none';
                        cartForm.action = "{{ route('en.frontend.cart.add') }}";
                        cartForm.onsubmit = null;

                        // Update other details (stock, ID, image)
                        stockValue.textContent = selectedVariant.stok;
                        stockDisplay.style.display = 'block';
                        productStokIdInput.value = selectedVariant.id;
                        addToCartButton.disabled = selectedVariant.stok <= 0;

                        productImage.src = selectedVariant.gambar ?
                            '{{ asset('backend/assets/media/produk/') }}/' + selectedVariant.gambar :
                            defaultImage;

                    } else {
                        // Reset to default state
                        priceSpan.textContent = 'Contact for price';
                        stockDisplay.style.display = 'none';
                        productImage.src = defaultImage;
                        addToCartButton.style.display = 'none';
                        contactUsButton.style.display = 'inline-block';
                        addToCartButton.disabled = true;
                        productStokIdInput.value = '';
                    }
                }

                function updateDimensions(shapeId) {
                    dimensionSelect.innerHTML = '<option value="">Select Dimension...</option>';
                    const filtered = variants.filter(v => v.jenis_id == shapeId);
                    if (filtered.length > 0) {
                        filtered.forEach(v => {
                            const option = document.createElement('option');
                            option.value = v.id;
                            option.textContent = v.dimensi;
                            dimensionSelect.appendChild(option);
                        });
                        dimensionSelect.disabled = false;
                    } else {
                        dimensionSelect.disabled = true;
                    }
                }

                shapeSelect.addEventListener('change', function() {
                    const shapeId = this.value;
                    updateDimensions(shapeId);

                    if (shapeId) {
                        const filtered = variants.find(v => v.jenis_id == shapeId);
                        if (filtered.length > 0) {
                            const firstVariantId = filtered[0].id;
                            dimensionSelect.value = firstVariantId;
                            updateProductDetails(firstVariantId);
                        } else {
                            updateProductDetails(null);
                        }
                    } else {
                        updateProductDetails(null);
                    }
                });

                dimensionSelect.addEventListener('change', function() {
                    updateProductDetails(this.value);
                });

                // Initial state setup
                updateProductDetails(null);
            });
        </script>
    @endpush
@endsection
