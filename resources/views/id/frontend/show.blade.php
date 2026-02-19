@extends('id.layouts.app')

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
                    <label class="block text-sm font-medium mb-1">* Bentuk</label>
                    <select id="shape-select" class="w-full border rounded p-2 mb-4">
                        <option value="">Pilih...</option>
                        @foreach ($jenis_unik as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->jenis }}</option>
                        @endforeach
                    </select>

                    <!-- Dropdown Dimensions -->
                    <label class="block text-sm font-medium mb-1">* Dimensi</label>
                    <select id="dimensions-select" class="w-full border rounded p-2 mb-4" disabled>
                        <option value="">Pilih Shape Terlebih Dahulu...</option>
                    </select>
                @else
                    <div class="mb-4 p-4 border border-yellow-300 bg-yellow-50 text-yellow-800 rounded">
                        <p>Produk ini tidak memiliki varian bentuk atau dimensi yang dapat dipilih saat ini. Silakan hubungi
                            kami atau gunakan tombol "Permintaan Penawaran" untuk informasi lebih lanjut.</p>
                    </div>
                @endif

                <!-- Price -->
                <div class="mb-4">
                    <p class="text-xl font-bold">Harga: <span id="product-price">-</span></p>
                </div>

                <form action="{{ route('id.frontend.cart.add') }}" method="POST" id="cart-form">
                    @csrf
                    <input type="hidden" name="product_stok_id" id="product-stok-id">
                    <!-- QTY -->
                    <div class="flex items-center mb-4">
                        <label class="mr-2 font-medium">Jumlah </label>
                        <input type="number" id="qty" name="qty" value="1" min="1"
                            class="w-20 border rounded text-center p-2">

                        <button type="submit" id="btn-add-to-cart"
                            class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded" style="display: none;">
                            Masukan Keranjang
                        </button>

                        <a href="{{ route('id.frontend.contact') }}" id="btn-contact-us"
                            class="ml-4 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded" style="display: none;">
                            Hubungi Kami
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
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Permintaan Penawaran</button>
                </div>
            </div>
        </div>

        <!-- Spesifikasi Material -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Kolom Kiri: Spesifikasi -->
            <div style="width: 98%;margin-left: 28px; color:#1A3D7C;">
                <h2 class="text-xl font-bold mb-4">Spesifikasi Material</h2>
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
                    <span style="color:#1A3D7C;"> Deskripsi</span>
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
                        {!! nl2br(e($produk->rincian)) !!}
                    </p>
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
                                                "{{ route('id.frontend.cart.index') }}";
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

            });
        </script>
    @endpush
@endsection
