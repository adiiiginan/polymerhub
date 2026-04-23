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

        .pd-page {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--surface);
            min-height: 100vh;
            color: var(--text);
            padding-bottom: 60px;
        }

        /* ══════════════════════════════
                                                                                                                               PAGE HEADER
                                                                                                                            ══════════════════════════════ */
        .show-header {
            background: linear-gradient(135deg, #0f2550 0%, #1a3d7c 60%, #2563eb 100%);
            border-radius: 16px;
            padding: 24px 32px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            position: relative;
            overflow: hidden;
        }

        .show-header::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
            pointer-events: none;
        }

        .show-header::after {
            content: '';
            position: absolute;
            bottom: -30px;
            left: 30%;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
            pointer-events: none;
        }

        .show-header-left {
            position: relative;
            z-index: 1;
        }

        .show-header-badge {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .5);
            margin-bottom: 4px;
        }

        .show-header-title {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }

        .show-header-sub {
            font-size: 13px;
            color: rgba(255, 255, 255, .6);
            margin-top: 4px;
        }

        /* QTY + Cart in Header */
        .header-cart-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
            flex-wrap: wrap;
        }

        .qty-wrap-header {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, .15);
            border: 1.5px solid rgba(255, 255, 255, .3);
            border-radius: 10px;
            overflow: hidden;
        }

        .qty-btn-h {
            width: 36px;
            height: 42px;
            background: none;
            border: none;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s;
        }

        .qty-btn-h:hover {
            background: rgba(255, 255, 255, .1);
        }

        #header-qty {
            width: 48px;
            border: none;
            border-left: 1px solid rgba(255, 255, 255, .2);
            border-right: 1px solid rgba(255, 255, 255, .2);
            background: transparent;
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            padding: 10px 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        #header-qty:focus {
            outline: none;
        }

        #header-qty::-webkit-inner-spin-button,
        #header-qty::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }

        .btn-header-cart {
            background: #fff;
            color: #1a3d7c;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: opacity .2s, transform .1s;
            white-space: nowrap;
        }

        .btn-header-cart:hover {
            opacity: .92;
            transform: translateY(-1px);
        }

        .btn-header-back {
            background: rgba(255, 255, 255, .12);
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, .25);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background .2s;
        }

        .btn-header-back:hover {
            background: rgba(255, 255, 255, .2);
            color: #fff;
        }

        /* ══════════════════════════════
                                                                                                                               MAIN GRID  (image | details)
                                                                                                                            ══════════════════════════════ */
        .pd-grid {
            display: grid;
            grid-template-columns: 420px 1fr;
            gap: 0;
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 2px 20px rgba(0, 0, 0, .06);
            margin-bottom: 24px;
        }

        @media(max-width: 900px) {
            .pd-grid {
                grid-template-columns: 1fr;
            }
        }

        /* LEFT: Image Panel */
        .pd-image-panel {
            background: linear-gradient(145deg, #e8eef8, #f0f4ff);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 36px;
            position: relative;
            min-height: 480px;
        }

        .pd-image-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 70%, rgba(26, 61, 124, .08) 0%, transparent 70%);
            pointer-events: none;
        }

        #product-image {
            width: 100%;
            max-width: 340px;
            height: 300px;
            object-fit: contain;
            position: relative;
            z-index: 1;
            transition: transform .4s ease;
            filter: drop-shadow(0 12px 32px rgba(26, 61, 124, .18));
        }

        #product-image:hover {
            transform: scale(1.04);
        }

        .pd-kode {
            position: absolute;
            top: 20px;
            left: 20px;
            background: var(--navy);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            padding: 5px 14px;
            border-radius: 20px;
            z-index: 2;
        }

        .pd-sku {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(37, 99, 235, .12);
            color: var(--accent);
            border: 1px solid rgba(37, 99, 235, .2);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            padding: 5px 12px;
            border-radius: 20px;
            z-index: 2;
        }

        .no-image-placeholder {
            text-align: center;
            color: #94a3b8;
            position: relative;
            z-index: 1;
        }

        .no-image-placeholder i {
            font-size: 52px;
            margin-bottom: 10px;
            display: block;
        }

        .no-image-placeholder p {
            font-size: 13px;
            margin: 0;
        }

        /* Stok & Harga bawah gambar */
        .image-price-stok {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            width: 100%;
            max-width: 340px;
            margin-top: 24px;
            position: relative;
            z-index: 1;
        }

        .price-box {
            background: linear-gradient(135deg, #0f2550, #1a3d7c);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }

        .price-box-label {
            font-size: 10px;
            color: rgba(255, 255, 255, .55);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 6px;
        }

        .price-box-value {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
        }

        .stok-box {
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }

        .stok-box-label {
            font-size: 10px;
            color: #16a34a;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 6px;
        }

        .stok-box-value {
            font-size: 22px;
            font-weight: 700;
            color: #15803d;
        }

        .stok-box-unit {
            font-size: 11px;
            color: #4ade80;
            margin-top: 2px;
        }

        /* RIGHT: Detail Panel */
        .pd-detail-panel {
            padding: 36px 40px;
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 0;
            overflow-y: auto;
        }

        @media(max-width: 900px) {
            .pd-detail-panel {
                border-left: none;
                border-top: 1px solid var(--border);
                padding: 28px 24px;
            }
        }

        /* ── Info Cards inside right panel ── */
        .info-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .info-card:last-child {
            margin-bottom: 0;
        }

        .info-card-header {
            padding: 12px 18px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        .icon-blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .icon-green {
            background: #f0fdf4;
            color: #16a34a;
        }

        .icon-amber {
            background: #fffbeb;
            color: #d97706;
        }

        .icon-navy {
            background: #f0f4ff;
            color: #1a3d7c;
        }

        .icon-rose {
            background: #fff1f2;
            color: #e11d48;
        }

        .info-card-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .info-card-body {
            padding: 16px 18px;
        }

        /* ── Field Row ── */
        .field-row {
            display: flex;
            align-items: flex-start;
            padding: 8px 0;
            border-bottom: 1px solid #f8fafc;
            gap: 12px;
        }

        .field-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .field-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: #94a3b8;
            min-width: 150px;
            flex-shrink: 0;
            padding-top: 2px;
        }

        .field-value {
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
            word-break: break-word;
        }

        .field-value.empty {
            color: #cbd5e1;
            font-style: italic;
        }

        /* ── Status Badges ── */
        .badge-aktif {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .badge-nonaktif {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .unit-tag {
            display: inline-block;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 6px;
            vertical-align: middle;
        }

        /* ── Dimension Chips ── */
        .dim-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
        }

        .dim-chip {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
            text-align: center;
        }

        .dim-chip-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #94a3b8;
            margin-bottom: 5px;
        }

        .dim-chip-value {
            font-size: 16px;
            font-weight: 700;
            color: #1a3d7c;
            line-height: 1;
        }

        .dim-chip-unit {
            font-size: 10px;
            font-weight: 500;
            color: #64748b;
            margin-top: 3px;
        }

        /* ── Pressure / Vacuum Grid ── */
        .temp-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .temp-cell {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
        }

        .temp-cell-label {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .temp-cell-value {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
        }

        .temp-cell-unit {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .dot-cold {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #3b82f6;
            display: inline-block;
        }

        .dot-hot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ef4444;
            display: inline-block;
        }

        .spec-section-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 8px;
        }

        /* ══════════════════════════════
                                                                                                                               BOTTOM TABS
                                                                                                                            ══════════════════════════════ */
        .pd-bottom {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0, 0, 0, .06);
            border: 1px solid var(--border);
        }

        .pd-tabs {
            display: flex;
            border-bottom: 2px solid var(--border);
        }

        .pd-tab {
            padding: 16px 28px;
            font-size: 14px;
            font-weight: 600;
            color: var(--muted);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: color .2s, border-color .2s;
            user-select: none;
            background: none;
            border-top: none;
            border-left: none;
            border-right: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .pd-tab.active {
            color: var(--navy);
            border-bottom-color: var(--navy);
        }

        .pd-tab:hover:not(.active) {
            color: var(--text);
        }

        .pd-tab-content {
            display: none;
            padding: 36px 40px;
        }

        .pd-tab-content.active {
            display: block;
        }

        .pd-description-text {
            font-size: 15px;
            line-height: 1.85;
            color: #374151;
            max-width: 760px;
        }

        .pd-spec-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pd-spec-table tr:nth-child(even) td {
            background: var(--surface);
        }

        .pd-spec-table td {
            padding: 12px 18px;
            font-size: 14px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .pd-spec-table td:first-child {
            font-weight: 600;
            color: var(--navy);
            width: 42%;
        }

        .pd-spec-table td:last-child {
            color: #374151;
        }

        .pd-spec-table tr:last-child td {
            border-bottom: none;
        }

        .pd-spec-table thead tr th {
            background: var(--navy-dark);
            color: rgba(255, 255, 255, .85);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            padding: 10px 18px;
            border: none;
        }

        .pd-spec-table thead tr th:first-child {
            border-radius: 8px 0 0 0;
        }

        .pd-spec-table thead tr th:last-child {
            border-radius: 0 8px 0 0;
        }

        /* Spec Table Sections */
        .spec-table-section {
            margin-bottom: 28px;
        }

        .spec-table-section:last-child {
            margin-bottom: 0;
        }

        .spec-table-section-header {
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
            display: flex;
            align-items: center;
            padding: 0 0 10px 0;
            border-bottom: 2px solid var(--navy-light);
            margin-bottom: 0;
        }

        .spec-table-section-header i {
            color: var(--accent);
        }

        /* Breadcrumb */
        .pd-breadcrumb {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 12px 0;
            font-size: 12px;
            color: var(--muted);
        }

        .pd-breadcrumb a {
            color: var(--navy);
            text-decoration: none;
            font-weight: 500;
        }

        .pd-breadcrumb a:hover {
            text-decoration: underline;
        }
    </style>

    <div class="pd-page">

        {{-- Breadcrumb --}}


        <div class="container-xxl py-4">

            {{-- ══ PAGE HEADER with Cart ══ --}}
            <div class="show-header">
                <div class="show-header-left">
                    <p class="show-header-badge">Detail Produk · Tygon 3350</p>
                    <h1 class="show-header-title">{{ $produk->nama_produk }}</h1>

                </div>

            </div>

            {{-- ══ MAIN CARD ══ --}}
            <div class="pd-grid">

                {{-- LEFT: Image + Stok & Harga --}}
                <div class="pd-image-panel">
                    <span class="pd-kode">{{ $produk->kode_produk ?? 'N/A' }}</span>
                    @if ($produk->sku)
                        <span class="pd-sku">{{ $produk->sku }}</span>
                    @endif

                    @if ($produk->gambar)
                        <img src="{{ asset('backend/assets/media/produk/' . $produk->gambar) }}"
                            alt="{{ $produk->nama_produk }}" id="product-image">
                    @else
                        <div class="no-image-placeholder">
                            <i class="fas fa-box-open"></i>
                            <p>Belum ada gambar</p>
                        </div>
                    @endif

                    {{-- Stok & Harga under image --}}

                </div>

                {{-- RIGHT: All Detail Cards --}}
                <div class="pd-detail-panel">

                    {{-- Informasi Dasar --}}
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="info-card-icon icon-navy"><i class="fas fa-info-circle"></i></div>
                            <h6 class="info-card-title">Informasi Dasar</h6>
                        </div>
                        <div class="info-card-body">
                            <div class="field-row">
                                <span class="field-label">Nama Produk</span>
                                <span class="field-value">{{ $produk->nama_produk }}</span>
                            </div>
                            <div class="field-row">
                                <span class="field-label">Series</span>
                                <span class="field-value">Tygon 3350</span>
                            </div>
                            <div class="field-row">
                                <span class="field-label">Color</span>
                                <span class="field-value">Clear/Semi-Clear</span>
                            </div>
                            <div class="field-row">
                                <span class="field-label">Tubing Construction</span>
                                <span class="field-value">Silicone</span>
                            </div>
                            <div class="field-row">
                                <span class="field-label">Dimensi Produk</span>
                                <span class="field-value">
                                    @if ($produk->length || $produk->width || $produk->height)
                                        {{ $produk->length ?? '?' }} × {{ $produk->width ?? '?' }} ×
                                        {{ $produk->height ?? '?' }} cm
                                    @else
                                        <span class="empty">—</span>
                                    @endif
                                </span>
                            </div>

                            {{-- Harga --}}
                            <div class="field-row">
                                <span class="field-label">Harga</span>
                                <span class="field-value text-primary fw-bold">
                                    @if ($produk->stok && $produk->stok->harga)
                                        Rp {{ number_format($produk->stok->harga, 0, ',', '.') }}
                                    @else
                                        <span class="empty">—</span>
                                    @endif
                                </span>
                            </div>

                            {{-- Stok --}}
                            <div class="field-row">
                                <span class="field-label">Stok</span>
                                <span class="field-value">
                                    @if ($produk->stok && $produk->stok->stok > 0)
                                        <span class="badge bg-success">{{ $produk->stok->stok }} tersedia</span>
                                    @else
                                        <span class="badge bg-danger">Stok Habis</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>



                    <div class="flex items-center mb-4">
                        <form action="{{ route('id.frontend.cart.add') }}" method="POST"
                            class="d-flex align-items-center gap-2">
                            @csrf
                            <div class="flex items-center border border-gray-300 rounded-md shadow-sm">
                                <button type="button" id="qty-minus"
                                    class="px-3 py-1 text-lg text-gray-600 hover:bg-gray-100 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">-</button>
                                <input type="number" id="qty" name="qty" value="1" min="1"
                                    max="{{ $produk->stok->stok ?? 9999 }}"
                                    class="w-16 border-0 text-center p-2 focus:ring-0">
                                <button type="button" id="qty-plus"
                                    class="px-3 py-1 text-lg text-gray-600 hover:bg-gray-100 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500">+</button>
                            </div>

                            <button type="submit" class="btn-header-cart">
                                <i class="fas fa-shopping-cart"></i>
                                Masukan Keranjang
                            </button>

                            <a href="{{ route('id.frontend.contact') }}" id="btn-contact-us"
                                class="ml-4 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md shadow-sm"
                                style="display: none;">
                                Hubungi Kami
                            </a>
                    </div>
                    </form>
                </div>{{-- end pd-detail-panel --}}


            </div>{{-- end pd-grid --}}

            {{-- ══ BOTTOM TABS ══ --}}
            <div class="pd-bottom">
                <div class="pd-tabs">
                    <button class="pd-tab active" data-tab="description">Deskripsi</button>
                    <button class="pd-tab" data-tab="specs">Spesifikasi Material</button>
                </div>

                {{-- Description Tab --}}
                <div id="tab-description" class="pd-tab-content active">
                    <p class="pd-description-text">
                        {!! nl2br(e($produk->deskripsi ?? ($produk->rincian ?? ''))) !!}
                    </p>
                </div>

                {{-- Specs Tab --}}
                <div id="tab-specs" class="pd-tab-content">
                    @php
                        $sizeCategory = $produk->tygon_size_category;
                        $unitLabel = $sizeCategory === 'metric' ? 'mm' : 'inches';
                        $lenLabel = $sizeCategory === 'metric' ? 'm' : 'feet';
                    @endphp

                    {{-- Section: Dimensi Tube --}}
                    <div class="spec-table-section">
                        <div class="spec-table-section-header">
                            <i class="fas fa-ruler-combined me-2"></i>
                            Dimensi Tube
                            <span
                                class="unit-tag ms-2">{{ $sizeCategory === 'metric' ? 'Metric (mm)' : 'Tubing Inventory (inches)' }}</span>
                        </div>
                        <table class="pd-spec-table">
                            <thead>
                                <tr>
                                    <th>Parameter</th>
                                    <th>Nilai</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Inner Diameter (ID)</td>
                                    <td>{{ $produk->inner_diameter ?? '—' }}</td>
                                    <td>{{ $unitLabel }}</td>
                                </tr>
                                <tr>
                                    <td>Outer Diameter (OD)</td>
                                    <td>{{ $produk->outer_diameter ?? '—' }}</td>
                                    <td>{{ $unitLabel }}</td>
                                </tr>
                                <tr>
                                    <td>Wall Thickness</td>
                                    <td>{{ $produk->wall_thickness ?? '—' }}</td>
                                    <td>{{ $unitLabel }}</td>
                                </tr>
                                <tr>
                                    <td>Length / Roll</td>
                                    <td>{{ $produk->tygon_length ?? '—' }}</td>
                                    <td>{{ $lenLabel }}</td>
                                </tr>
                                <tr>
                                    <td>Min. Bend Radius</td>
                                    <td>{{ $produk->min_bend_radius ?? '—' }}</td>
                                    <td>{{ $unitLabel }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Section: Working Pressure --}}
                    <div class="spec-table-section">
                        <div class="spec-table-section-header">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Max. Suggested Working Pressure
                        </div>
                        <table class="pd-spec-table">
                            <thead>
                                <tr>
                                    <th>Kondisi</th>
                                    <th>Nilai</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="dot-cold me-2"></span> Pada 73°F (23°C)</td>
                                    <td>{{ $produk->tygon_working_pressure_73 ?? '—' }}</td>
                                    <td>psi</td>
                                </tr>
                                <tr>
                                    <td><span class="dot-hot me-2"></span> Pada 320°F (160°C)</td>
                                    <td>{{ $produk->tygon_working_pressure_320 ?? '—' }}</td>
                                    <td>psi</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Section: Vacuum Rating --}}
                    <div class="spec-table-section">
                        <div class="spec-table-section-header">
                            <i class="fas fa-compress-arrows-alt me-2"></i>
                            Vacuum Rating
                        </div>
                        <table class="pd-spec-table">
                            <thead>
                                <tr>
                                    <th>Kondisi</th>
                                    <th>Nilai</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="dot-cold me-2"></span> Pada 73°F (23°C)</td>
                                    <td>{{ $produk->tygon_vacuum_73 ?? '—' }}</td>
                                    <td>in. Hg</td>
                                </tr>
                                <tr>
                                    <td><span class="dot-hot me-2"></span> Pada 320°F (160°C)</td>
                                    <td>{{ $produk->tygon_vacuum_320 ?? '—' }}</td>
                                    <td>in. Hg</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Section: Spesifikasi Material --}}

                </div>
            </div>

        </div>{{-- end container --}}
    </div>{{-- end pd-page --}}

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                /* ── Inline QTY (form keranjang) ── */
                const qtyInput = document.getElementById('qty');
                if (qtyInput) {
                    const btnMinus = document.getElementById('qty-minus');
                    const btnPlus = document.getElementById('qty-plus');
                    const maxStok = parseInt(qtyInput.getAttribute('max')) || 9999;

                    if (btnMinus) {
                        btnMinus.addEventListener('click', () => {
                            if (parseInt(qtyInput.value) > 1) qtyInput.value--;
                        });
                    }
                    if (btnPlus) {
                        btnPlus.addEventListener('click', () => {
                            if (parseInt(qtyInput.value) < maxStok) qtyInput.value++;
                        });
                    }
                }

                /* ── Tabs ── */
                document.querySelectorAll('.pd-tab').forEach(tab => {
                    tab.addEventListener('click', () => {
                        document.querySelectorAll('.pd-tab').forEach(t => t.classList.remove('active'));
                        document.querySelectorAll('.pd-tab-content').forEach(c => c.classList.remove(
                            'active'));
                        tab.classList.add('active');
                        document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
                    });
                });

            });
        </script>
    @endpush
@endsection
