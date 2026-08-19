@extends('id.layouts.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        :root {
            --navy: #1A3D7C;
            --navy-dark: #0f2550;
            --navy-light: #e8eef8;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --surface: #f7f9fc;
            --border: #e2e8f0;
            --text: #1e293b;
            --muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        .pd-page {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--surface);
            min-height: 100vh;
            color: var(--text);
            padding-bottom: 80px;
        }

        /* ══ HEADER ══ */
        .pd-header-inner {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 28px;
        }

        .pd-kode-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--navy-light);
            color: var(--navy);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .5px;
            padding: 5px 14px;
            border-radius: 20px;
            border: 1px solid rgba(26, 61, 124, .15);
            margin-bottom: 8px;
        }

        .pd-header-title {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 6px;
            line-height: 1.2;
        }

        .pd-header-sub {
            font-size: 13px;
            color: var(--muted);
            margin: 0;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: none;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            text-decoration: none;
            transition: border-color .2s, color .2s;
            white-space: nowrap;
            flex-shrink: 0;
            margin-top: 6px;
        }

        .btn-back:hover {
            border-color: var(--navy);
            color: var(--navy);
        }

        /* ══ MAIN LAYOUT ══ */
        .pd-main {
            display: grid;
            grid-template-columns: 440px 1fr;
            gap: 28px;
        }

        @media(max-width: 960px) {
            .pd-main {
                grid-template-columns: 1fr;
            }
        }

        /* ══ IMAGE PANEL ══ */
        .pd-image-panel {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .pd-image-main {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pd-image-main img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 28px;
            transition: transform .4s ease;
        }

        .pd-image-main img:hover {
            transform: scale(1.04);
        }

        .slide-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            color: var(--navy);
            box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
            transition: box-shadow .2s, transform .2s;
            z-index: 2;
        }

        .slide-btn:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .15);
            transform: translateY(-50%) scale(1.05);
        }

        .slide-btn-prev {
            left: 12px;
        }

        .slide-btn-next {
            right: 12px;
        }

        .pd-thumbs {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .pd-thumb {
            flex-shrink: 0;
            width: 80px;
            height: 80px;
            border: 2px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            background: #fff;
            transition: border-color .2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pd-thumb.active {
            border-color: var(--accent);
        }

        .pd-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 6px;
        }

        .no-img-placeholder {
            text-align: center;
            color: #94a3b8;
        }

        .no-img-placeholder i {
            font-size: 52px;
            display: block;
            margin-bottom: 10px;
        }

        /* ══ RIGHT PANEL ══ */
        .pd-right {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ══ VARIANT CARD ══ */
        .variant-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .variant-card-grid {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 0;
        }

        @media(max-width: 640px) {
            .variant-card-grid {
                grid-template-columns: 1fr;
            }
        }

        .vc-col {
            padding: 20px;
        }

        .vc-col:first-child {
            border-right: 1px solid var(--border);
        }

        @media(max-width: 640px) {
            .vc-col:first-child {
                border-right: none;
                border-bottom: 1px solid var(--border);
            }
        }

        .vc-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 14px;
        }

        /* Bentuk Option */
        .bentuk-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 10px;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
        }

        .bentuk-option:last-child {
            margin-bottom: 0;
        }

        .bentuk-option input[type="radio"] {
            accent-color: var(--accent);
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .bentuk-option.active {
            border-color: var(--accent);
            background: #eff6ff;
        }

        .bentuk-only-badge {
            display: block;
            margin-top: 10px;
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
            width: fit-content;
        }

        /* Dimensi Table */
        .dim-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dim-table thead th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--muted);
            padding: 6px 10px;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        .dim-table thead th:last-child {
            text-align: right;
        }

        .dim-table tbody tr {
            cursor: pointer;
            transition: background .15s;
        }

        .dim-table tbody tr:hover {
            background: #f8fafc;
        }

        .dim-table tbody tr.selected-row {
            background: #eff6ff;
        }

        .dim-table tbody td {
            padding: 10px;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .dim-table tbody tr:last-child td {
            border-bottom: none;
        }

        .dim-table td:last-child {
            text-align: right;
        }

        .dim-radio {
            accent-color: var(--accent);
            width: 15px;
            height: 15px;
            cursor: pointer;
        }

        .stok-chip {
            display: inline-block;
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 20px;
        }

        .stok-empty {
            color: #dc2626;
            font-size: 11px;
            font-weight: 600;
        }

        .dim-notice {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            background: #eff6ff;
            border-radius: 8px;
            padding: 10px 12px;
            margin-top: 12px;
            font-size: 12px;
            color: var(--accent);
            font-weight: 500;
        }

        .dim-notice i {
            font-size: 13px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .dim-empty {
            padding: 20px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
            font-style: italic;
        }

        /* ══ ORDER CARD ══ */
        .order-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px 24px;
            display: grid;
            grid-template-columns: 1fr 1fr 1.8fr;
            gap: 20px;
            align-items: end;
        }

        @media(max-width: 700px) {
            .order-card {
                grid-template-columns: 1fr 1fr;
            }

            .btn-area {
                grid-column: 1/-1;
            }
        }

        @media(max-width: 450px) {
            .order-card {
                grid-template-columns: 1fr;
            }
        }

        .oc-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .oc-price {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
        }

        .oc-price.empty {
            font-size: 18px;
            color: #cbd5e1;
        }

        .qty-stepper {
            display: flex;
            align-items: center;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }

        .qty-btn {
            width: 38px;
            height: 44px;
            background: var(--surface);
            border: none;
            font-size: 18px;
            color: var(--navy);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s;
            flex-shrink: 0;
        }

        .qty-btn:hover {
            background: var(--navy-light);
        }

        .qty-input {
            width: 54px;
            border: none;
            border-left: 1.5px solid var(--border);
            border-right: 1.5px solid var(--border);
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            padding: 10px 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fff;
        }

        .qty-input:focus {
            outline: none;
        }

        .qty-input::-webkit-inner-spin-button,
        .qty-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }

        .btn-area {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn-quote {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px 20px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: background .2s, transform .1s;
            width: 100%;
        }

        .btn-quote:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
        }

        .btn-quote-sub {
            font-size: 11px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 6px;
            justify-content: center;
            margin: 0;
        }

        .btn-quote-sub i {
            font-size: 12px;
            color: var(--accent);
        }

        /* ══ TRUST BADGES ══ */
        .trust-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        @media(max-width: 600px) {
            .trust-row {
                grid-template-columns: 1fr;
            }
        }

        .trust-item {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px 18px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .trust-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--navy-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--navy);
            font-size: 15px;
            flex-shrink: 0;
        }

        .trust-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .trust-desc {
            font-size: 12px;
            color: var(--muted);
            margin: 0;
        }

        /* ══ BOTTOM ══ */
        .pd-bottom {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            margin-top: 28px;
        }

        .pd-bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        @media(max-width: 800px) {
            .pd-bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        .bottom-col {
            padding: 30px 32px;
        }

        .bottom-col:first-child {
            border-right: 1px solid var(--border);
        }

        @media(max-width: 800px) {
            .bottom-col:first-child {
                border-right: none;
                border-bottom: 1px solid var(--border);
            }
        }

        .bottom-col-header {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 18px;
        }

        .bottom-col-header i {
            color: var(--accent);
        }

        .desc-text {
            font-size: 14px;
            line-height: 1.85;
            color: #374151;
        }

        .spec-tbl {
            width: 100%;
            border-collapse: collapse;
        }

        .spec-tbl thead th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--muted);
            padding: 6px 14px;
            border-bottom: 2px solid var(--border);
            text-align: left;
        }

        .spec-tbl thead th:last-child {
            text-align: right;
        }

        .spec-tbl tbody tr:nth-child(even) td {
            background: var(--surface);
        }

        .spec-tbl tbody td {
            padding: 9px 14px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
            color: #374151;
        }

        .spec-tbl tbody tr:last-child td {
            border-bottom: none;
        }

        .spec-tbl td:first-child {
            color: #0f172a;
            font-weight: 500;
        }

        .spec-tbl td:last-child {
            text-align: right;
            font-weight: 600;
            color: var(--navy);
        }

        .btn-lihat-semua {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 14px;
            font-size: 13px;
            font-weight: 600;
            color: var(--accent);
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .spec-extra {
            display: none;
        }

        .spec-extra.show {
            display: table-row-group;
        }
    </style>

    <div class="pd-page">
        <div class="container-xxl py-4">

            {{-- ══ HEADER ══ --}}
            <div class="pd-header-inner">
                <div>

                    <h1 class="pd-header-title">{{ $produk->nama_produk }}</h1>
                    <p class="pd-header-sub">Klik <strong>Permintaan Penawaran</strong> jika Anda tidak melihat dimensi yang
                        Anda inginkan</p>
                </div>
                <a href="{{ url()->previous() }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            {{-- ══ MAIN GRID ══ --}}
            <div class="pd-main">

                {{-- LEFT: Image --}}
                <div class="pd-image-panel">
                    <div class="pd-image-main">
                        <button class="slide-btn slide-btn-prev" id="btn-prev">
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        @if ($produk->gambar)
                            <img src="{{ asset('backend/assets/media/produk/' . $produk->gambar) }}"
                                alt="{{ $produk->nama_produk }}" id="main-img">
                        @else
                            <div class="no-img-placeholder">
                                <i class="fas fa-box-open"></i>
                                <p style="font-size:13px;margin:0;">Belum ada gambar</p>
                            </div>
                        @endif

                        <button class="slide-btn slide-btn-next" id="btn-next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="pd-thumbs" id="thumb-wrap">
                        @if ($produk->gambar)
                            <div class="pd-thumb active"
                                data-img="{{ asset('backend/assets/media/produk/' . $produk->gambar) }}">
                                <img src="{{ asset('backend/assets/media/produk/' . $produk->gambar) }}" alt="">
                            </div>
                        @endif
                        @if (!empty($produk->gambar_tambahan))
                            @foreach (json_decode($produk->gambar_tambahan) as $gTambahan)
                                <div class="pd-thumb" data-img="{{ asset('backend/assets/media/produk/' . $gTambahan) }}">
                                    <img src="{{ asset('backend/assets/media/produk/' . $gTambahan) }}" alt="">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="pd-right">

                    {{-- ── Variant Card ── --}}
                    <div class="variant-card">
                        <div class="variant-card-grid">

                            {{-- Pilih Bentuk --}}
                            <div class="vc-col">
                                <p class="vc-label">Pilih </p>
                                @forelse ($jenis_unik as $jenis)
                                    <label class="bentuk-option {{ $loop->first ? 'active' : '' }}"
                                        data-jenis-id="{{ $jenis->id }}">
                                        <input type="radio" name="bentuk" value="{{ $jenis->id }}"
                                            {{ $loop->first ? 'checked' : '' }}>
                                        {{ $jenis->jenis }}
                                    </label>
                                @empty
                                    <p style="font-size:13px;color:var(--muted);">Tidak ada bentuk tersedia.</p>
                                @endforelse

                                @if ($jenis_unik->count() === 1)
                                    <span class="bentuk-only-badge">
                                        Bentuk hanya tersedia: {{ $jenis_unik->first()->nama_jenis }}
                                    </span>
                                @endif
                            </div>

                            {{-- Pilih Dimensi --}}
                            <div class="vc-col">
                                <p class="vc-label">Pilih Dimensi (Ukuran Ready / Stok Tersedia)</p>
                                <table class="dim-table" id="dim-table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Ukuran</th>
                                            <th>Stok Tersedia</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dim-tbody">
                                        <tr>
                                            <td colspan="3" class="dim-empty">Pilih bentuk terlebih dahulu...</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="dim-notice">
                                    <i class="fas fa-info-circle"></i>
                                    Stok dapat berubah sewaktu-waktu.
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ── Order Card ── --}}
                    <div class="order-card">
                        <div>
                            <p class="oc-label">Harga</p>
                            <div class="oc-price empty" id="display-harga">—</div>
                        </div>

                        <div>
                            <p class="oc-label">Jumlah</p>
                            <div class="qty-stepper">
                                <button type="button" class="qty-btn" id="qty-minus">−</button>
                                <input type="number" class="qty-input" id="qty-main" value="1" min="1"
                                    max="9999">
                                <button type="button" class="qty-btn" id="qty-plus">+</button>
                            </div>
                        </div>

                        <div class="btn-area">
                            <form action="{{ route('id.frontend.cart.add') }}" method="POST" id="add-cart-form">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                <input type="hidden" name="id_jenis" id="input-jenis" value="">
                                <input type="hidden" name="id_ukuran" id="input-ukuran" value="">
                                <input type="hidden" name="qty" id="qty-hidden" value="1">
                                <button type="submit" class="btn-quote">
                                    <i class="fas fa-paper-plane"></i>
                                    Masukan Keranjang
                                </button>
                            </form>

                        </div>
                    </div>

                    {{-- ── Trust Badges ── --}}
                    <div class="trust-row">
                        <div class="trust-item">
                            <div class="trust-icon"><i class="fas fa-shield-alt"></i></div>
                            <div>
                                <p class="trust-title">Kualitas Terjamin</p>
                                <p class="trust-desc">Produk original &amp; berkualitas</p>
                            </div>
                        </div>
                        <div class="trust-item">
                            <div class="trust-icon"><i class="fas fa-file-invoice"></i></div>
                            <div>
                                <p class="trust-title">Request Quote</p>
                                <p class="trust-desc">Dapatkan penawaran terbaik</p>
                            </div>
                        </div>
                        <div class="trust-item">
                            <div class="trust-icon"><i class="fas fa-headset"></i></div>
                            <div>
                                <p class="trust-title">Customer Support</p>
                                <p class="trust-desc">Siap membantu Anda</p>
                            </div>
                        </div>
                    </div>

                </div>{{-- end pd-right --}}
            </div>{{-- end pd-main --}}

            <div class="pd-bottom">
                <div class="bottom-col">
                    <div class="bottom-col-header">
                        <i class="fas fa-file-alt"></i> Deskripsi
                    </div>
                    <div class="desc-text">
                        {!! nl2br(e($produk->deskripsi ?? ($produk->rincian ?? 'Tidak ada deskripsi.'))) !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                /* ── Data dari controller ── */
                const variantsData = @json($variants_data);
                const produkId = {{ $produk->id }};

                /* ── Image Slider ── */
                const mainImg = document.getElementById('main-img');
                const thumbEls = document.querySelectorAll('.pd-thumb');
                let curIdx = 0;
                const images = Array.from(thumbEls).map(t => t.dataset.img);

                function setSlide(idx) {
                    if (!mainImg || !images.length) return;
                    curIdx = (idx + images.length) % images.length;
                    mainImg.src = images[curIdx];
                    thumbEls.forEach((t, i) => t.classList.toggle('active', i === curIdx));
                }
                thumbEls.forEach((t, i) => t.addEventListener('click', () => setSlide(i)));
                document.getElementById('btn-prev')?.addEventListener('click', () => setSlide(curIdx - 1));
                document.getElementById('btn-next')?.addEventListener('click', () => setSlide(curIdx + 1));

                /* ── QTY Stepper ── */
                const qtyInput = document.getElementById('qty-main');
                const qtyHidden = document.getElementById('qty-hidden');

                document.getElementById('qty-minus')?.addEventListener('click', () => {
                    if (parseInt(qtyInput.value) > 1) {
                        qtyInput.value--;
                        qtyHidden.value = qtyInput.value;
                    }
                });
                document.getElementById('qty-plus')?.addEventListener('click', () => {
                    const max = parseInt(qtyInput.getAttribute('max')) || 9999;
                    if (parseInt(qtyInput.value) < max) {
                        qtyInput.value++;
                        qtyHidden.value = qtyInput.value;
                    }
                });
                qtyInput?.addEventListener('input', () => {
                    qtyHidden.value = qtyInput.value;
                });

                /* ── Render Dimensi Table ── */
                let selectedJenisId = null;
                let selectedUkuranId = null;

                function formatRupiah(num) {
                    if (!num) return '—';
                    return 'Rp ' + parseInt(num).toLocaleString('id-ID');
                }

                function renderDimensi(jenisId) {
                    selectedJenisId = jenisId;
                    selectedUkuranId = null;
                    document.getElementById('input-jenis').value = jenisId;
                    document.getElementById('input-ukuran').value = '';
                    document.getElementById('display-harga').textContent = '—';
                    document.getElementById('display-harga').className = 'oc-price empty';
                    qtyInput.max = 9999;

                    const rows = variantsData.filter(v => v.jenis_id == jenisId);
                    const tbody = document.getElementById('dim-tbody');

                    if (!rows.length) {
                        tbody.innerHTML =
                            `<tr><td colspan="3" class="dim-empty">Tidak ada ukuran tersedia untuk bentuk ini.</td></tr>`;
                        return;
                    }

                    tbody.innerHTML = rows.map((v, i) => `
                <tr class="dim-row ${i === 0 ? 'selected-row' : ''}"
                    data-ukuran-id="${v.ukuran_id}"
                    data-harga="${v.harga || v.hargi || 0}"
                    data-stok="${v.stok}"
                    onclick="selectDim(this)">
                    <td><input type="radio" class="dim-radio" name="dim"
                        value="${v.ukuran_id}" ${i === 0 ? 'checked' : ''}></td>
                    <td>${v.dimensi}</td>
                    <td>${v.stok > 0
                        ? `<span class="stok-chip">${v.stok} rol</span>`
                        : `<span class="stok-empty">Habis</span>`
                    }</td>
                </tr>
            `).join('');

                    /* Auto-select first row */
                    const firstRow = tbody.querySelector('.dim-row');
                    if (firstRow) selectDim(firstRow);
                }

                window.selectDim = function(row) {
                    document.querySelectorAll('.dim-row').forEach(r => r.classList.remove('selected-row'));
                    row.classList.add('selected-row');
                    row.querySelector('input[type="radio"]').checked = true;

                    selectedUkuranId = row.dataset.ukuranId;
                    const harga = row.dataset.harga;
                    const stok = parseInt(row.dataset.stok) || 0;

                    document.getElementById('input-ukuran').value = selectedUkuranId;
                    const hargaEl = document.getElementById('display-harga');
                    hargaEl.textContent = formatRupiah(harga);
                    hargaEl.className = harga > 0 ? 'oc-price' : 'oc-price empty';
                    qtyInput.max = stok > 0 ? stok : 9999;
                    if (parseInt(qtyInput.value) > stok && stok > 0) qtyInput.value = 1;
                };

                /* ── Pilih Bentuk ── */
                document.querySelectorAll('.bentuk-option').forEach(label => {
                    label.addEventListener('click', function() {
                        document.querySelectorAll('.bentuk-option').forEach(l => l.classList.remove(
                            'active'));
                        this.classList.add('active');
                        this.querySelector('input[type="radio"]').checked = true;
                        renderDimensi(this.dataset.jenisId);

                        /* Update gambar jika ada variant gambar */
                        if (mainImg) {
                            const jenisId = this.dataset.jenisId;
                            const v = variantsData.find(x => x.jenis_id == jenisId && x.gambar);
                            if (v && v.gambar) mainImg.src = '/backend/assets/media/produk/' + v.gambar;
                        }
                    });
                });

                /* Auto-load first bentuk on page load */
                const firstBentuk = document.querySelector('.bentuk-option');
                if (firstBentuk) renderDimensi(firstBentuk.dataset.jenisId);

                /* ── Lihat Semua Spesifikasi ── */
                let expanded = false;
                document.getElementById('btn-lihat-semua')?.addEventListener('click', function() {
                    expanded = !expanded;
                    document.getElementById('spec-extra')?.classList.toggle('show', expanded);
                    this.innerHTML = expanded ?
                        'Sembunyikan <i class="fas fa-chevron-up"></i>' :
                        'Lihat semua spesifikasi <i class="fas fa-chevron-down"></i>';
                });

            });
        </script>
    @endpush
@endsection
