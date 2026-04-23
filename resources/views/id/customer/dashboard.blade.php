@extends('id.layouts.app')

@section('title', 'Profil Saya')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --ph-blue: #0B63D8;
            --ph-blue-dark: #084BA8;
            --ph-blue-light: #E8F0FD;
            --ph-bg: #F4F7FB;
            --ph-border: #E5EAF2;
            --ph-text: #1F2A44;
            --ph-muted: #6B7280;
            --ph-success: #16A34A;
            --ph-warning: #D97706;
            --ph-danger: #DC2626;
        }

        body {
            background: var(--ph-bg);
        }

        /* SIDEBAR */
        .ph-sidebar {
            background: #fff;
            border: 1px solid var(--ph-border);
            border-radius: 18px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, .04);
            position: sticky;
            top: 20px;
        }

        .ph-menu .nav-link {
            padding: 12px 16px;
            border-radius: 12px;
            color: var(--ph-text);
            font-weight: 600;
            font-size: 13.5px;
            margin-bottom: 4px;
            transition: all .2s;
            cursor: pointer;
        }

        .ph-menu .nav-link:hover {
            background: var(--ph-blue-light);
            color: var(--ph-blue);
        }

        .ph-menu .nav-link.active {
            background: linear-gradient(90deg, var(--ph-blue-dark), var(--ph-blue));
            color: #fff;
        }

        .ph-menu .nav-link .badge-count {
            background: rgba(255, 255, 255, .25);
            color: #fff;
            border-radius: 999px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 700;
        }

        .ph-menu .nav-link:not(.active) .badge-count {
            background: var(--ph-blue-light);
            color: var(--ph-blue);
        }

        /* HEADER */
        .ph-header {
            background: linear-gradient(135deg, #084BA8 0%, #0B63D8 60%, #1a7ef5 100%);
            border-radius: 20px;
            color: #fff;
            padding: 28px 32px;
            position: relative;
            overflow: hidden;
        }

        .ph-header::before {
            content: '';
            position: absolute;
            right: -60px;
            top: -60px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
        }

        .ph-header::after {
            content: '';
            position: absolute;
            right: 80px;
            bottom: -80px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
        }

        /* STATS */
        .ph-stat {
            background: #fff;
            border: 1px solid var(--ph-border);
            border-radius: 16px;
            padding: 16px 18px;
            transition: transform .2s, box-shadow .2s;
            cursor: pointer;
        }

        .ph-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(11, 99, 216, .1);
        }

        .ph-stat.active-stat {
            border-color: var(--ph-blue);
            background: var(--ph-blue-light);
        }

        .ph-stat .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            margin-bottom: 10px;
        }

        /* CARD */
        .ph-card {
            background: #fff;
            border: 1px solid var(--ph-border);
            border-radius: 18px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, .04);
        }

        /* ORDER CARD */
        .ph-order {
            background: #fff;
            border: 1px solid var(--ph-border);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            transition: box-shadow .2s;
        }

        .ph-order:hover {
            box-shadow: 0 6px 18px rgba(11, 99, 216, .08);
        }

        /* PILLS */
        .ph-pill {
            padding: 5px 14px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 11.5px;
            letter-spacing: .3px;
        }

        /* TABS */
        .tab-section {
            display: none;
        }

        .tab-section.active {
            display: block;
        }

        /* TRACKING */
        .tracking-timeline {
            position: relative;
            padding-left: 24px;
        }

        .tracking-timeline::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: var(--ph-border);
        }

        .tracking-item {
            position: relative;
            margin-bottom: 16px;
        }

        .tracking-item::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 6px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--ph-border);
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px var(--ph-border);
        }

        .tracking-item.latest::before {
            background: var(--ph-blue);
            box-shadow: 0 0 0 3px rgba(11, 99, 216, .2);
        }

        .tracking-item.success::before {
            background: var(--ph-success);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .2);
        }

        .tracking-item.warning::before {
            background: var(--ph-warning);
            box-shadow: 0 0 0 3px rgba(217, 119, 6, .2);
        }

        .tracking-item.danger::before {
            background: var(--ph-danger);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, .2);
        }

        .tracking-status-bar {
            background: linear-gradient(135deg, #f0f7ff, #e8f0fd);
            border: 1px solid #c7dcf8;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 16px;
        }

        /* PRODUCT IMAGE */
        .order-img {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid var(--ph-border);
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--ph-muted);
        }

        .empty-state i {
            font-size: 40px;
            opacity: .3;
            margin-bottom: 12px;
        }

        /* HELP CARD */
        .ph-help-card {
            background: linear-gradient(135deg, #0B63D8, #084BA8);
            border-radius: 16px;
            color: #fff;
            padding: 20px;
        }

        /* SCROLLABLE TRACKING */
        .tracking-scroll {
            max-height: 320px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .tracking-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .tracking-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .tracking-scroll::-webkit-scrollbar-thumb {
            background: #c7dcf8;
            border-radius: 4px;
        }

        /* SPINNER */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .ph-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid var(--ph-blue-light);
            border-top-color: var(--ph-blue);
            border-radius: 50%;
            animation: spin .7s linear infinite;
            display: inline-block;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .ph-sidebar {
                position: static;
                margin-bottom: 20px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4 px-4">
        <div class="row g-4">

            {{-- ===================== SIDEBAR ===================== --}}
            <div class="col-lg-2">
                <div class="ph-sidebar p-3">

                    <div class="nav flex-column ph-menu" id="sideMenu">
                        <a class="nav-link active" data-tab="dashboard" onclick="switchTab('dashboard', this)">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" data-tab="order_placed" onclick="switchTab('order_placed', this)">
                            <i class="fas fa-shopping-cart me-2"></i>Pesanan Dibuat
                            <span class="badge-count float-end">{{ $pendingOrders ?? 0 }}</span>
                        </a>
                        <a class="nav-link" data-tab="order_custom" onclick="switchTab('order_custom', this)">
                            <i class="fas fa-file-alt me-2"></i>Permintaan Penawaran
                            <span class="badge-count float-end">{{ $customOrdersCount }}</span>
                        </a>
                        <a class="nav-link" data-tab="payment_pending" onclick="switchTab('payment_pending', this)">
                            <i class="fas fa-clock me-2"></i>Pembayaran Tertunda
                            <span class="badge-count float-end">{{ $invoices->where('status', 7)->count() }}</span>
                        </a>
                        <a class="nav-link" data-tab="payment_paid" onclick="switchTab('payment_paid', this)">
                            <i class="fas fa-check-circle me-2"></i>Pembayaran Lunas
                            <span class="badge-count float-end">{{ $paidOrdersCount }}</span>
                        </a>
                        <a class="nav-link" data-tab="kirim" onclick="switchTab('kirim', this)">
                            <i class="fas fa-truck me-2"></i>Dikirim
                            <span class="badge-count float-end">{{ $shippedOrdersCount }}</span>
                        </a>
                        <a class="nav-link" data-tab="history" onclick="switchTab('history', this)">
                            <i class="fas fa-box-open me-2"></i>Pesanan Selesai
                            <span class="badge-count float-end">{{ $completedOrdersCount ?? 0 }}</span>
                        </a>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="ph-help-card text-center">
                            <i class="fas fa-headset fs-4 mb-2"></i>
                            <div class="fw-bold small mb-1">Butuh Bantuan?</div>
                            <div class="small mb-3 opacity-75">Hubungi tim kami untuk mendapatkan bantuan.</div>
                            <a href="#" class="btn btn-light btn-sm w-100 fw-bold" style="border-radius:10px">Hubungi
                                Kami →</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== MAIN CONTENT ===================== --}}
            <div class="col-lg-10">

                {{-- HEADER --}}


                {{-- STATS --}}
                <div class="row g-3 mb-4">
                    @php
                        $stats = [
                            [
                                'label' => 'Permintaan Penawaran',
                                'val' => $customOrdersCount,
                                'icon' => 'fas fa-file-alt',
                                'color' => '#0B63D8',
                                'bg' => '#E8F0FD',
                                'tab' => 'order_custom',
                            ],
                            [
                                'label' => 'Pesanan Dibuat',
                                'val' => $pendingOrders ?? 0,
                                'icon' => 'fas fa-shopping-cart',
                                'color' => '#7C3AED',
                                'bg' => '#EDE9FE',
                                'tab' => 'order_placed',
                            ],
                            [
                                'label' => 'Pembayaran Tertunda',
                                'val' => $invoices->where('status', 7)->count(),
                                'icon' => 'fas fa-clock',
                                'color' => '#D97706',
                                'bg' => '#FEF3C7',
                                'tab' => 'payment_pending',
                            ],
                            [
                                'label' => 'Pembayaran Lunas',
                                'val' => $paidOrdersCount,
                                'icon' => 'fas fa-check-circle',
                                'color' => '#16A34A',
                                'bg' => '#DCFCE7',
                                'tab' => 'payment_paid',
                            ],
                            [
                                'label' => 'Dikirim',
                                'val' => $shippedOrdersCount,
                                'icon' => 'fas fa-truck',
                                'color' => '#0891B2',
                                'bg' => '#E0F2FE',
                                'tab' => 'kirim',
                            ],
                            [
                                'label' => 'Pesanan Selesai',
                                'val' => $completedOrdersCount ?? 0,
                                'icon' => 'fas fa-box-open',
                                'color' => '#059669',
                                'bg' => '#D1FAE5',
                                'tab' => 'history',
                            ],
                        ];
                    @endphp
                    @foreach ($stats as $s)
                        <div class="col-md-2">
                            <div class="ph-stat"
                                onclick="switchTab('{{ $s['tab'] }}', document.querySelector('[data-tab={{ $s['tab'] }}]'))">
                                <div class="stat-icon" style="background:{{ $s['bg'] }}; color:{{ $s['color'] }}">
                                    <i class="{{ $s['icon'] }}"></i>
                                </div>
                                <div class="fw-bold fs-4" style="color:{{ $s['color'] }}">{{ $s['val'] }}</div>
                                <div class="small text-muted">{{ $s['label'] }}</div>
                                <div class="small mt-2" style="color:{{ $s['color'] }}">Lihat Semua →</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- CONTENT AREA --}}
                <div class="row g-4">
                    <div class="col-lg-9">

                        {{-- ===== DASHBOARD TAB ===== --}}
                        <div class="tab-section active" id="tab-dashboard">
                            <div class="ph-card p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="fw-bold mb-0">Pesanan Selesai</h5>
                                    <select class="form-select w-auto form-select-sm">
                                        <option>Terbaru</option>
                                    </select>
                                </div>
                                @forelse ($completedOrdersHistory as $order)
                                    <div class="ph-order">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex align-items-center gap-3">
                                                @if ($order->items->isNotEmpty() && optional($order->items->first()->produk)->gambar)
                                                    <img src="{{ asset('backend/assets/media/produk/' . $order->items->first()->produk->gambar) }}"
                                                        class="order-img" alt="">
                                                @else
                                                    <img src="{{ asset('backend/assets/media/produk/no-image.png') }}"
                                                        class="order-img" alt="">
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $order->idtransaksi }}</div>
                                                    <small class="text-muted">Selesai pada
                                                        {{ $order->updated_at->format('d M Y, H:i') }}</small>
                                                </div>
                                            </div>
                                            <span class="ph-pill bg-success-subtle text-success">✓ Selesai</span>
                                        </div>
                                        @foreach ($order->items as $item)
                                            <div class="row border-top pt-2 mt-2 small">
                                                <div class="col-md-4 fw-semibold text-gray-800">
                                                    {{ optional($item->produk)->nama_produk ?? 'Produk tidak tersedia' }}
                                                </div>
                                                <div class="col-md-2 text-muted">Qty: {{ $item->qty }}</div>
                                                <div class="col-md-3 text-muted">Jenis:
                                                    {{ data_get(json_decode($item->jenis), 'jenis') }}</div>
                                                <div class="col-md-3 text-muted">Ukuran:
                                                    {{ data_get(json_decode($item->ukuran), 'nama_ukuran') }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @empty
                                    <div class="empty-state"><i class="fas fa-box-open d-block"></i>Belum ada pesanan
                                        selesai</div>
                                @endforelse
                                <div class="d-flex justify-content-center mt-3">{{ $completedOrdersHistory->links() }}
                                </div>
                            </div>
                        </div>

                        {{-- ===== PESANAN DIBUAT ===== --}}
                        <div class="tab-section" id="tab-order_placed">
                            <div class="ph-card p-4">
                                <h5 class="fw-bold mb-4">Pesanan Dibuat</h5>
                                @forelse ($activeOrders as $order)
                                    <div class="ph-order">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <div class="fw-bold">{{ $order->idtransaksi }}</div>
                                                <small class="text-muted">Ditempatkan pada
                                                    {{ $order->created_at->format('d M Y, H:i') }}</small>
                                            </div>
                                            <span class="ph-pill" style="background:#EDE9FE;color:#7C3AED">Konfirmasi
                                                Pesanan</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="small fw-bold text-muted mb-2">BARANG</div>
                                                @foreach ($order->items as $item)
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        @if ($item->produk && $item->produk->gambar)
                                                            <img src="{{ asset('backend/assets/media/produk/' . $item->produk->gambar) }}"
                                                                class="order-img" style="width:40px;height:40px"
                                                                alt="">
                                                        @endif
                                                        <div class="small">
                                                            <div class="fw-semibold">
                                                                {{ $item->produk ? $item->produk->nama_produk : 'Produk tidak tersedia' }}
                                                            </div>
                                                            <div class="text-muted">Qty: {{ $item->qty }} | Jenis:
                                                                {{ data_get(json_decode($item->jenis), 'jenis') }}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small fw-bold text-muted mb-2">INFO PENGIRIMAN</div>
                                                <div class="small text-muted"><strong>Alamat:</strong>
                                                    {{ $order->address->alamat ?? '-' }}</div>
                                                <div class="small text-muted"><strong>Kurir:</strong>
                                                    {{ $order->shipping_service ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state"><i class="fas fa-shopping-cart d-block"></i>Tidak ada pesanan
                                        aktif</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- ===== PERMINTAAN PENAWARAN ===== --}}
                        <div class="tab-section" id="tab-order_custom">
                            <div class="ph-card p-4">
                                <h5 class="fw-bold mb-4">Permintaan Penawaran</h5>
                                @forelse ($customOrders as $order)
                                    <div class="ph-order">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <div class="fw-bold">{{ $order->request_id }}</div>
                                                <small
                                                    class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                                            </div>
                                            <span class="ph-pill" style="background:#E0F2FE;color:#0891B2">Permintaan
                                                Penawaran</span>
                                        </div>
                                        <div class="small text-muted"><strong>Subjek:</strong> {{ $order->subject }}</div>
                                        <div class="small text-muted"><strong>Pesan:</strong> {{ $order->message }}</div>
                                        @if ($order->file_path)
                                            <a href="{{ asset('storage/' . $order->file_path) }}" target="_blank"
                                                class="btn btn-sm btn-light-primary mt-2">Lihat Lampiran</a>
                                        @endif
                                    </div>
                                @empty
                                    <div class="empty-state"><i class="fas fa-file-alt d-block"></i>Belum ada permintaan
                                        penawaran</div>
                                @endforelse
                                <div class="d-flex justify-content-center mt-3">{{ $customOrders->links() }}</div>
                            </div>
                        </div>

                        {{-- ===== PEMBAYARAN TERTUNDA ===== --}}
                        <div class="tab-section" id="tab-payment_pending">
                            <div class="ph-card p-4">
                                <h5 class="fw-bold mb-4">Pembayaran Tertunda</h5>
                                @forelse ($paymentPendingInvoices as $invoice)
                                    <div class="ph-order">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <div class="fw-bold">{{ $invoice->transaksi->idtransaksi }}</div>
                                                <small
                                                    class="text-muted">{{ $invoice->transaksi->created_at->format('d M Y, H:i') }}</small>
                                            </div>
                                            <span class="ph-pill" style="background:#FEF3C7;color:#D97706">⏳
                                                Tertunda</span>
                                        </div>
                                        @foreach ($invoice->transaksi->items as $item)
                                            <div class="d-flex align-items-center gap-2 mb-2 small">
                                                <img src="{{ $item->produk && $item->produk->gambar ? asset('backend/assets/media/produk/' . $item->produk->gambar) : asset('backend/assets/media/produk/no-image.png') }}"
                                                    style="width:36px;height:36px;border-radius:8px;object-fit:cover"
                                                    alt="">
                                                <div>
                                                    <div class="fw-semibold">{{ $item->produk->nama_produk ?? '-' }}</div>
                                                    <div class="text-muted">Qty: {{ $item->qty }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if ($invoice->kode_inv)
                                            <div class="d-flex gap-2 mt-3">
                                                <a href="{{ route('id.customer.invoice.show', $invoice->id) }}"
                                                    class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="fas fa-eye me-1"></i>Lihat Faktur
                                                </a>
                                                @if (!$invoice->bukti_bayar)
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#uploadModal{{ $invoice->id }}">
                                                        <i class="fas fa-upload me-1"></i>Unggah Pembayaran
                                                    </button>
                                                    <div class="modal fade" id="uploadModal{{ $invoice->id }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Unggah Bukti Pembayaran</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <form action="{{ route('id.customer.upload.payment') }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="invoice_id"
                                                                            value="{{ $invoice->id }}">
                                                                        <div class="mb-3"><label class="form-label">File
                                                                                Bukti Pembayaran</label><input
                                                                                class="form-control" type="file"
                                                                                name="payment_proof" required></div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Tutup</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Unggah</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="badge bg-success-subtle text-success align-self-center">✓
                                                        Pembayaran Diunggah</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="empty-state"><i class="fas fa-clock d-block"></i>Tidak ada pembayaran
                                        tertunda</div>
                                @endforelse
                                <div class="d-flex justify-content-center mt-3">{{ $paymentPendingInvoices->links() }}
                                </div>
                            </div>
                        </div>

                        {{-- ===== PEMBAYARAN LUNAS ===== --}}
                        <div class="tab-section" id="tab-payment_paid">
                            <div class="ph-card p-4">
                                <h5 class="fw-bold mb-4">Pembayaran Lunas</h5>
                                @forelse ($paymentPaidInvoices as $invoice)
                                    <div class="ph-order">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex align-items-center gap-3">
                                                @if ($invoice->transaksi->items->isNotEmpty() && optional($invoice->transaksi->items->first()->produk)->gambar)
                                                    <img src="{{ asset('backend/assets/media/produk/' . $invoice->transaksi->items->first()->produk->gambar) }}"
                                                        class="order-img" alt="">
                                                @else
                                                    <img src="{{ asset('backend/assets/media/produk/no-image.png') }}"
                                                        class="order-img" alt="">
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $invoice->transaksi->idtransaksi }}</div>
                                                    <small class="text-muted">Dibayar pada
                                                        {{ $invoice->updated_at->format('d M Y, H:i') }}</small>
                                                </div>
                                            </div>
                                            <span class="ph-pill bg-success-subtle text-success">✓ Lunas</span>
                                        </div>
                                        @foreach ($invoice->transaksi->items as $item)
                                            <div class="row border-top pt-2 mt-2 small">
                                                <div class="col-md-4 fw-semibold">
                                                    {{ optional($item->produk)->nama_produk ?? '-' }}</div>
                                                <div class="col-md-2 text-muted">Qty: {{ $item->qty }}</div>
                                                <div class="col-md-3 text-muted">
                                                    {{ data_get(json_decode($item->jenis), 'jenis') }}</div>
                                                <div class="col-md-3 text-muted">
                                                    {{ data_get(json_decode($item->ukuran), 'nama_ukuran') }}</div>
                                            </div>
                                        @endforeach
                                        <div class="d-flex gap-2 mt-3">

                                            @if ($invoice->faktur)
                                                <a href="{{ asset('/' . $invoice->faktur) }}"
                                                    class="btn btn-sm btn-success" target="_blank"><i
                                                        class="fas fa-file me-1"></i>Faktur Pajak</a>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state"><i class="fas fa-check-circle d-block"></i>Tidak ada
                                        pembayaran lunas</div>
                                @endforelse
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $paymentPaidInvoices->appends(request()->except('payment_paid_page'))->links() }}
                                </div>
                            </div>
                        </div>

                        {{-- ===== DIKIRIM + TRACKING ===== --}}
                        <div class="tab-section" id="tab-kirim">
                            <div class="ph-card p-4">
                                <h5 class="fw-bold mb-4">Dikirim</h5>
                                @forelse ($shippedOrders as $order)
                                    <div class="ph-order">
                                        {{-- Header --}}
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex align-items-center gap-3">
                                                @if ($order->items->isNotEmpty() && optional($order->items->first()->produk)->gambar)
                                                    <img src="{{ asset('backend/assets/media/produk/' . $order->items->first()->produk->gambar) }}"
                                                        class="order-img" alt="">
                                                @else
                                                    <img src="{{ asset('backend/assets/media/produk/no-image.png') }}"
                                                        class="order-img" alt="">
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $order->idtransaksi }}</div>
                                                    <small class="text-muted">Dikirim pada
                                                        {{ $order->updated_at->format('d M Y, H:i') }}</small>
                                                </div>
                                            </div>
                                            <span class="ph-pill" style="background:#E0F2FE;color:#0891B2">🚚
                                                Dikirim</span>
                                        </div>

                                        {{-- Items --}}
                                        @foreach ($order->items as $item)
                                            <div class="row border-top pt-2 mt-2 small">
                                                <div class="col-md-4 fw-semibold">
                                                    {{ optional($item->produk)->nama_produk ?? '-' }}</div>
                                                <div class="col-md-2 text-muted">Qty: {{ $item->qty }}</div>
                                                <div class="col-md-3 text-muted">
                                                    {{ data_get(json_decode($item->jenis), 'jenis') }}</div>
                                                <div class="col-md-3 text-muted">
                                                    {{ data_get(json_decode($item->ukuran), 'nama_ukuran') }}</div>
                                            </div>
                                        @endforeach

                                        {{-- Tracking Section --}}
                                        @if ($order->lion_parcel_stt)
                                            <div class="mt-3 pt-3 border-top">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="fas fa-barcode text-muted small"></i>
                                                        <span class="small text-muted fw-semibold">No. Resi:</span>
                                                        <span class="badge"
                                                            style="background:#E8F0FD;color:#0B63D8;font-size:11px">{{ $order->lion_parcel_stt }}</span>
                                                    </div>
                                                    <button class="btn btn-sm fw-semibold"
                                                        style="background:#E8F0FD;color:#0B63D8;border-radius:10px;font-size:12px"
                                                        onclick="loadTracking('{{ $order->lion_parcel_stt }}', {{ $order->id }})"
                                                        id="btn-track-{{ $order->id }}">
                                                        <i class="fas fa-map-marker-alt me-1"></i>Lacak Paket
                                                    </button>
                                                </div>

                                                {{-- Tracking Result --}}
                                                <div id="tracking-result-{{ $order->id }}" style="display:none">
                                                    <div id="tracking-loading-{{ $order->id }}"
                                                        class="text-center py-3" style="display:none">
                                                        <div class="ph-spinner me-2"></div>
                                                        <span class="small text-muted">Memuat data tracking...</span>
                                                    </div>
                                                    <div id="tracking-content-{{ $order->id }}"></div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="mt-3 pt-3 border-top">
                                                <div class="small text-muted"><i class="fas fa-info-circle me-1"></i>Nomor
                                                    resi belum tersedia</div>
                                            </div>
                                        @endif

                                        {{-- Tombol Diterima --}}
                                        @if ($order->status == 3)
                                            <div class="d-flex justify-content-end mt-3">
                                                <form action="{{ route('id.frontend.orders.diterima', $order->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check me-1"></i>Pesanan Diterima
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="empty-state"><i class="fas fa-truck d-block"></i>Tidak ada pesanan yang
                                        sedang dikirim</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- ===== PESANAN SELESAI ===== --}}
                        <div class="tab-section" id="tab-history">
                            <div class="ph-card p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="fw-bold mb-0">Pesanan Selesai</h5>
                                    <select class="form-select w-auto form-select-sm">
                                        <option>Terbaru</option>
                                    </select>
                                </div>
                                @forelse ($completedOrdersHistory as $order)
                                    <div class="ph-order">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex align-items-center gap-3">
                                                @if ($order->items->isNotEmpty() && optional($order->items->first()->produk)->gambar)
                                                    <img src="{{ asset('backend/assets/media/produk/' . $order->items->first()->produk->gambar) }}"
                                                        class="order-img" alt="">
                                                @else
                                                    <img src="{{ asset('backend/assets/media/produk/no-image.png') }}"
                                                        class="order-img" alt="">
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $order->idtransaksi }}</div>
                                                    <small class="text-muted">Selesai pada
                                                        {{ $order->updated_at->format('d M Y, H:i') }}</small>
                                                </div>
                                            </div>
                                            <span class="ph-pill bg-success-subtle text-success">✓ Selesai</span>
                                        </div>
                                        @foreach ($order->items as $item)
                                            <div class="row border-top pt-2 mt-2 small">
                                                <div class="col-md-4 fw-semibold">
                                                    {{ optional($item->produk)->nama_produk ?? '-' }}</div>
                                                <div class="col-md-2 text-muted">Qty: {{ $item->qty }}</div>
                                                <div class="col-md-3 text-muted">
                                                    {{ data_get(json_decode($item->jenis), 'jenis') }}</div>
                                                <div class="col-md-3 text-muted">
                                                    {{ data_get(json_decode($item->ukuran), 'nama_ukuran') }}</div>
                                            </div>
                                        @endforeach
                                        @if ($order->status == 3)
                                            <div class="d-flex justify-content-end mt-3">
                                                <form action="{{ route('id.frontend.orders.diterima', $order->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm"><i
                                                            class="fas fa-check me-1"></i>Pesanan Diterima</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="empty-state"><i class="fas fa-box-open d-block"></i>Belum ada pesanan
                                        selesai</div>
                                @endforelse
                                <div class="d-flex justify-content-center mt-3">{{ $completedOrdersHistory->links() }}
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- SIDEBAR KANAN --}}
                    <div class="col-lg-3">
                        <div class="ph-card p-4 mb-3">
                            <h6 class="fw-bold mb-3">Informasi Akun</h6>
                            <div class="small text-muted">Perusahaan</div>
                            <div class="fw-semibold mb-2">{{ $user->detail->perusahaan ?? '-' }}</div>
                            <div class="small text-muted">Email</div>
                            <div class="fw-semibold mb-2">{{ $user->email }}</div>
                            <div class="small text-muted">No. Handphone</div>
                            <div class="fw-semibold mb-2">{{ $user->detail->no_hp ?? '-' }}</div>
                            <div class="small text-muted">Jabatan</div>
                            <div class="fw-semibold">{{ $user->detail->jabatan ?? '-' }}</div>
                        </div>
                        <div class="ph-card p-4 mb-3">
                            <h6 class="fw-bold mb-2">Informasi</h6>
                            <p class="text-muted small mb-0">Terima kasih telah mempercayai <strong>POLYMERHUB</strong>
                                sebagai mitra kebutuhan material Anda.</p>
                        </div>
                        <div class="ph-card p-4">
                            <h6 class="fw-bold mb-2">Keamanan Akun</h6>
                            <p class="text-muted small mb-3">Pastikan akun Anda tetap aman dengan selalu mengubah password
                                secara berkala.</p>
                            <a href="#" class="btn btn-outline-primary btn-sm w-100"><i
                                    class="fas fa-lock me-1"></i>Ubah Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ===================== TAB SWITCHING =====================
        function switchTab(tabName, el) {
            // Hide all sections
            document.querySelectorAll('.tab-section').forEach(s => s.classList.remove('active'));
            // Remove active from all menu links
            document.querySelectorAll('.ph-menu .nav-link').forEach(l => l.classList.remove('active'));

            // Show selected section
            const section = document.getElementById('tab-' + tabName);
            if (section) section.classList.add('active');

            // Set active menu
            const menuLink = document.querySelector('[data-tab="' + tabName + '"]');
            if (menuLink) menuLink.classList.add('active');
        }

        // ===================== TRACKING =====================
        const statusMap = {
            'BKD': {
                label: 'Diproses Agen',
                color: '#0B63D8',
                bg: '#E8F0FD'
            },
            'SHPCRT': {
                label: 'Pengiriman Dibuat',
                color: '#0891B2',
                bg: '#E0F2FE'
            },
            'CRRSRC': {
                label: 'Mencari Kurir',
                color: '#7C3AED',
                bg: '#EDE9FE'
            },
            'PUP': {
                label: 'Dijemput Kurir',
                color: '#0891B2',
                bg: '#E0F2FE'
            },
            'STI-SC': {
                label: 'Di Gudang Transit',
                color: '#D97706',
                bg: '#FEF3C7'
            },
            'STI': {
                label: 'Di Gudang',
                color: '#D97706',
                bg: '#FEF3C7'
            },
            'STI-DEST': {
                label: 'Di Gudang Tujuan',
                color: '#D97706',
                bg: '#FEF3C7'
            },
            'DEL': {
                label: 'Sedang Diantar',
                color: '#0B63D8',
                bg: '#DBEAFE'
            },
            'POD': {
                label: 'Terkirim',
                color: '#16A34A',
                bg: '#DCFCE7'
            },
            'RTS': {
                label: 'Dikembalikan',
                color: '#DC2626',
                bg: '#FEE2E2'
            },
            'RTSHQ': {
                label: 'Return ke HQ',
                color: '#DC2626',
                bg: '#FEE2E2'
            },
            'REROUTE': {
                label: 'Dialihkan',
                color: '#D97706',
                bg: '#FEF3C7'
            },
            'CANCEL': {
                label: 'Dibatalkan',
                color: '#DC2626',
                bg: '#FEE2E2'
            },
            'MISBOOKING': {
                label: 'Kesalahan Booking',
                color: '#DC2626',
                bg: '#FEE2E2'
            },
        };

        function getStatus(code) {
            return statusMap[code] || {
                label: code,
                color: '#6B7280',
                bg: '#F3F4F6'
            };
        }

        function formatDate(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function loadTracking(sttNo, orderId) {
            const btn = document.getElementById('btn-track-' + orderId);
            const result = document.getElementById('tracking-result-' + orderId);
            const loading = document.getElementById('tracking-loading-' + orderId);
            const content = document.getElementById('tracking-content-' + orderId);

            // Toggle: jika sudah terbuka, tutup
            if (result.style.display === 'block') {
                result.style.display = 'none';
                btn.innerHTML = '<i class="fas fa-map-marker-alt me-1"></i>Lacak Paket';
                return;
            }

            result.style.display = 'block';
            loading.style.display = 'block';
            content.innerHTML = '';
            btn.disabled = true;
            btn.innerHTML =
                '<span style="display:inline-block;width:14px;height:14px;border:2px solid #0B63D8;border-top-color:transparent;border-radius:50%;animation:spin .7s linear infinite;vertical-align:middle;margin-right:6px"></span>Memuat...';

            fetch('/api/tracking?stt_no=' + sttNo)
                .then(r => r.json())
                .then(data => {
                    loading.style.display = 'none';
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-times me-1"></i>Tutup Tracking';

                    if (!data.success) {
                        content.innerHTML =
                            `<div class="alert alert-warning small py-2 mt-2"><i class="fas fa-exclamation-circle me-1"></i>Data tracking tidak ditemukan</div>`;
                        return;
                    }

                    const stt = data.data;
                    const currentStatus = getStatus(stt.status_code);

                    // Build history
                    const histories = [...(stt.history || [])].reverse();
                    let historyHtml = '';
                    histories.forEach((h, i) => {
                        const s = getStatus(h.status_code);
                        const isFirst = i === 0;
                        historyHtml += `
                    <div class="tracking-item ${isFirst ? 'latest' : ''}" style="${isFirst ? '--dot-color:' + s.color : ''}">
                        <div style="display:flex;gap:10px">
                            <div>
                                <span class="ph-pill" style="background:${s.bg};color:${s.color};font-size:10.5px">${s.label}</span>
                                <div class="small fw-semibold mt-1" style="color:#1F2A44">${h.remarks}</div>
                                <div class="small" style="color:#9CA3AF">${formatDate(h.datetime)}${h.city ? ' · ' + h.city : ''}</div>
                            </div>
                        </div>
                    </div>`;
                    });

                    content.innerHTML = `
                <div class="tracking-status-bar mt-2">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold small" style="color:#1F2A44">Status Pengiriman</span>
                        <span class="ph-pill" style="background:${currentStatus.bg};color:${currentStatus.color}">${currentStatus.label}</span>
                    </div>
                    <div class="row g-2 small">
                        <div class="col-6">
                            <div style="color:#9CA3AF">Dari</div>
                            <div class="fw-semibold" style="color:#1F2A44">${stt.origin || '-'}</div>
                        </div>
                        <div class="col-6">
                            <div style="color:#9CA3AF">Ke</div>
                            <div class="fw-semibold" style="color:#1F2A44">${stt.destination || '-'}</div>
                        </div>
                        <div class="col-6">
                            <div style="color:#9CA3AF">Pengirim</div>
                            <div class="fw-semibold" style="color:#1F2A44">${stt.sender_name || '-'}</div>
                        </div>
                        <div class="col-6">
                            <div style="color:#9CA3AF">Penerima</div>
                            <div class="fw-semibold" style="color:#1F2A44">${stt.recipient_name || '-'}</div>
                        </div>
                    </div>
                </div>
                <div class="small fw-bold mb-2" style="color:#1F2A44">Riwayat Pengiriman</div>
                <div class="tracking-scroll">
                    <div class="tracking-timeline">${historyHtml || '<p class="small text-muted">Belum ada riwayat</p>'}</div>
                </div>`;
                })
                .catch(() => {
                    loading.style.display = 'none';
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-map-marker-alt me-1"></i>Lacak Paket';
                    content.innerHTML =
                        `<div class="alert alert-danger small py-2 mt-2"><i class="fas fa-times-circle me-1"></i>Gagal memuat data tracking. Coba lagi.</div>`;
                });
        }
    </script>
@endpush
