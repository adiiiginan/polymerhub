@extends('id.layouts.app')

@section('title', 'Profil Saya')
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!--begin::Navbar-->
                <div class="card mb-5 mb-xxl-8">
                    <div class="card-body pt-9 pb-0">
                        <!--begin::Details-->
                        <div class="d-flex flex-wrap flex-sm-nowrap">
                            <!--begin: Pic-->
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <img src="{{ asset('/backend/assets/media/avatars/blank.png') }}" alt="image" />
                                    <div
                                        class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">

                                    </div>
                                </div>
                            </div>
                            <!--end::Pic-->
                            <!--begin::Info-->
                            <div class="flex-grow-1">
                                <!--begin::Title-->
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <!--begin::User-->
                                    <div class="d-flex flex-column">
                                        <!--begin::Name-->
                                        <div class="d-flex align-items-center mb-2">
                                            <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                                                {{ $user->detail->nama ?? ($user->name ?? 'User') }}</a>
                                            <a href="#">
                                                <i class="ki-outline ki-verify fs-1 text-primary"></i>
                                            </a>
                                        </div>
                                        <!--end::Name-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <a href="#"
                                                class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                                <i class="ki-outline ki-profile-circle fs-4 me-1"></i>
                                                {{ $user->detail->perusahaan ?? ($user->profile->perusahaan ?? 'User') }}</a>
                                            <a href="#"
                                                class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                                <i class="ki-outline ki-briefcase fs-4 me-1"></i>
                                                {{ $user->detail->jabatan ?? 'Jabatan tidak tersedia' }}</a>
                                            <a href="#"
                                                class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                                <i class="ki-outline ki-tablet fs-4 me-1"></i>
                                                {{ $user->detail->no_hp ?? ($user->detail->no_hp ?? 'User') }}</a>
                                            <a href="#"
                                                class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                                                <i class="ki-outline ki-sms fs-4 me-1"></i>
                                                {{ $user->detail->email ?? ($user->email ?? 'User') }}</a>
                                        </div>
                                        <!--end::Info-->
                                    </div>


                                    <!--end::User-->
                                    <!--begin::Actions-->

                                </div>
                                <!--end::Title-->
                                <!--begin::Stats-->

                                <!--end::Stats-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Details-->
                        <!--begin::Navs-->
                        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                            <!--begin::Nav item-->

                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab"
                                    href="#order_custom">Permintaan Penawaran<span
                                        class="badge badge-light-success ms-2">{{ $customOrdersCount }}</span></a>
                            </li>

                            <!--end::Nav item-->
                            <!--begin::Nav item-->
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab"
                                    href="#order_placed">Pesanan Dibuat <span
                                        class="badge badge-light-success ms-2">{{ $pendingOrders ?? 0 }}</span></a>
                            </li>
                            <!--end::Nav item-->
                            <!--begin::Nav item-->
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab"
                                    href="#payment_pending_page">Pembayaran Tertunda<span
                                        class="badge badge-light-success ms-2">{{ $invoices->where('status', 7)->count() }}</a>
                            </li>

                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab"
                                    href="#payment_paid">Pembayaran Lunas <span
                                        class="badge badge-light-success ms-2">{{ $paidOrdersCount }}</span></a>
                            </li>


                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab"
                                    href="#kirim">Dikirim <span
                                        class="badge badge-light-success ms-2">{{ $shippedOrdersCount }}</span></a>
                            </li>
                            <li class="nav-item mt-2" role="presentation">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5 active" id="history-tab"
                                    data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                                    Pesanan Selesai<span
                                        class="badge badge-light-success ms-2">{{ $completedOrdersCount ?? 0 }}</span>
                                </a>
                            </li>
                            <!--begin::Navs-->
                        </ul>
                    </div>
                </div>
                <!--end::Navbar-->
                <!--begin::Row-->
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="history" role="tabpanel">
                        <div class="row g-5 g-xxl-8">
                            <div class="col-xl-6">
                                @foreach ($completedOrdersHistory as $order)
                                    <div class="card mb-5 mb-xxl-8">
                                        <div class="card-body pb-0">
                                            <div class="d-flex align-items-center mb-5">
                                                <div class="d-flex align-items-center flex-grow-1">
                                                    <div class="symbol symbol-45px me-5">
                                                        @if ($order->items->isNotEmpty() && optional($order->items->first()->produk)->gambar)
                                                            <img src="{{ asset('backend/assets/media/produk/' . $order->items->first()->produk->gambar) }}"
                                                                alt="" />
                                                        @else
                                                            <img src="{{ asset('backend/assets/media/produk/no-image.png') }}"
                                                                alt="No Image" />
                                                        @endif
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <a href="#"
                                                            class="text-gray-900 text-hover-primary fs-6 fw-bold">{{ $order->idtransaksi }}</a>
                                                        <span class="text-gray-500 fw-bold">Selesai pada
                                                            {{ $order->updated_at->format('d M Y, H:i') }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex my-2">
                                                    <span class="badge badge-light-success">Selesai</span>
                                                </div>
                                            </div>
                                            @foreach ($order->items as $item)
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="d-flex">
                                                        <span
                                                            class="text-gray-800 fw-bold">{{ optional($item->produk)->nama_produk ?? 'Produk tidak tersedia' }}</span>
                                                        <span class="text-gray-600 ms-2">|| Qty:
                                                            {{ $item->qty }}</span>
                                                        <span class="text-gray-600 ms-2">|| Jenis:
                                                            {{ data_get(json_decode($item->jenis), 'jenis') }}</span>
                                                        <span class="text-gray-600 ms-2">|| Ukuran:
                                                            {{ data_get(json_decode($item->ukuran), 'nama_ukuran') }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if ($order->status == 3)
                                                <div class="d-flex justify-content-end mt-4">
                                                    <form action="{{ route('id.frontend.orders.diterima', $order->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="ki-outline ki-check-circle fs-4 me-2"></i>Pesanan
                                                            Diterima
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-center">
                                    {{ $completedOrdersHistory->links() }}
                                </div>
                            </div>




                            <!--begin::Col-->

                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!-- Tab for Order Placed -->
                    <div class="tab-pane fade" id="order_placed" role="tabpanel">
                        <div class="row g-5 g-xxl-8">
                            <div class="col-xl-8">
                                @forelse($activeOrders as $order)
                                    <div class="card mb-5 mb-xxl-8 shadow-sm">
                                        <div class="card-header">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px me-5">
                                                    <div class="symbol-label bg-light-primary">
                                                        <i class="ki-outline ki-shopping-cart fs-2 text-primary"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column flex-grow-1">
                                                    <a href="#"
                                                        class="text-gray-900 text-hover-primary fs-5 fw-bold">{{ $order->idtransaksi }}</a>
                                                    <span class="text-gray-500 fw-semibold">Ditempatkan pada
                                                        {{ $order->created_at->format('d M Y, H:i') }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge badge-light-success fs-7 fw-bold me-3">Konfirmasi
                                                        Pesanan</span>
                                                    @if ($order->is_request == 1)
                                                        <span class="badge badge-light-info fs-7 fw-bold">Kustom</span>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold text-gray-800 mb-3">Barang:</h6>
                                                    @foreach ($order->items as $item)
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="symbol symbol-40px me-3">
                                                                @if ($item->produk && $item->produk->gambar)
                                                                    <img src="{{ asset('backend/assets/media/produk/' . $item->produk->gambar) }}"
                                                                        alt="" />
                                                                @else
                                                                    <img src="{{ asset('backend/assets/media/produk/no-image.png') }}"
                                                                        alt="No Image" />
                                                                @endif
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <span
                                                                    class="text-gray-800 fw-bold">{{ $item->produk ? $item->produk->nama_produk : 'Produk tidak tersedia' }}</span>
                                                                <span class="text-gray-600">Qty: {{ $item->qty }} ||
                                                                    Jenis:
                                                                    {{ data_get(json_decode($item->jenis), 'jenis') }} ||
                                                                    Ukuran:
                                                                    {{ data_get(json_decode($item->ukuran), 'nama_ukuran') }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold text-gray-800 mb-3">Info Pengiriman:</h6>
                                                    <p class="text-gray-600 mb-1"><strong>Alamat:</strong>
                                                        {{ $order->address->alamat ?? 'Alamat tidak tersedia' }}</p>
                                                    <p class="text-gray-600 mb-1"><strong>Kurir:</strong>
                                                        {{ $order->shipping_service ?? '-' }}</p>
                                                    <p class="text-gray-600 mb-1"><strong>Estimasi Pengiriman:</strong>
                                                        {{ $order->created_at->addDays(7)->format('d M Y') }}</p>

                                                </div>
                                            </div>
                                            <div class="separator my-4"></div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="text-gray-600">Metode Pembayaran: Transfer Bank</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="card mb-5 mb-xxl-8">
                                        <div class="card-body text-center">
                                            <p class="text-muted">Tidak ada pesanan aktif yang ditemukan.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <div class="col-xl-4">
                                <div class="card mb-5 mb-xxl-8">
                                    <div class="card-header">
                                        <h3 class="card-title">Informasi Pesanan</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-warning d-flex align-items-center p-3">
                                            <i class="ki-outline ki-information-5 fs-2 me-3"></i>
                                            <p class="mb-0 fw-semibold">
                                                Setiap pesanan yang masuk akan ditinjau oleh admin terlebih dahulu.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Tab for Payment Pending -->
                    <!-- Tab for Payment Pending -->
                    <div class="tab-pane fade" id="payment_pending_page" role="tabpanel">
                        <div class="container-fluid px-0">
                            <div class="row g-4 g-xxl-6 align-items-start">
                                <!-- Kolom kiri: daftar transaksi -->
                                <div class="col-xl-8 col-lg-7">
                                    @forelse ($paymentPendingInvoices as $invoice)
                                        <div class="card mb-5 shadow-sm">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px me-5">
                                                        <div class="symbol-label bg-light-primary">
                                                            <i class="ki-outline ki-shopping-cart fs-2 text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <a href="#"
                                                            class="text-gray-900 text-hover-primary fs-5 fw-bold">
                                                            {{ $invoice->transaksi->idtransaksi }}
                                                        </a>
                                                        <div class="text-gray-500 fw-semibold">
                                                            {{ $invoice->transaksi->created_at->format('d M Y, H:i') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="badge badge-light-warning fs-7 fw-bold me-2">Tertunda</span>
                                                    @if ($invoice->transaksi->is_request == 1)
                                                        <span class="badge badge-light-info fs-7 fw-bold">Kustom</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <h6 class="fw-bold text-gray-800 mb-3">Barang:</h6>
                                                @foreach ($invoice->transaksi->items as $item)
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="symbol symbol-40px me-3">
                                                            <img src="{{ $item->produk && $item->produk->gambar
                                                                ? asset('backend/assets/media/produk/' . $item->produk->gambar)
                                                                : asset('backend/assets/media/produk/no-image.png') }}"
                                                                alt="Produk" />
                                                        </div>
                                                        <div>
                                                            <span class="fw-bold text-gray-800 d-block">
                                                                {{ $item->produk->nama_produk ?? 'Produk tidak tersedia' }}\
                                                            </span>
                                                            <small class="text-gray-600">
                                                                Qty: {{ $item->qty }} | Jenis:
                                                                {{ data_get(json_decode($item->jenis), 'jenis') }} |
                                                                Ukuran:
                                                                {{ data_get(json_decode($item->ukuran), 'nama_ukuran') }}\
                                                            </small>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                <div class="d-flex justify-content-between mt-4">
                                                    @if ($invoice->kode_inv)
                                                        <div class="d-flex gap-2">
                                                            <a href="{{ route('id.customer.invoice.show', $invoice->id) }}"
                                                                class="btn btn-primary btn-sm" target="_blank">
                                                                <i class="ki-outline ki-eye fs-4 me-2"></i>Lihat Faktur
                                                            </a>

                                                            @if ($invoice->bukti_bayar)
                                                                <span class="badge badge-light-success align-self-center">
                                                                    <i
                                                                        class="ki-outline ki-check-circle fs-4 me-1"></i>Pembayaran
                                                                    Diunggah
                                                                </span>
                                                            @else
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#uploadModal{{ $invoice->id }}">
                                                                    <i
                                                                        class="ki-outline ki-cloud-upload fs-4 me-2"></i>Unggah
                                                                    Pembayaran
                                                                </button>

                                                                <!-- Modal -->
                                                                <div class="modal fade"
                                                                    id="uploadModal{{ $invoice->id }}" tabindex="-1"
                                                                    aria-labelledby="uploadModalLabel{{ $invoice->id }}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="uploadModalLabel{{ $invoice->id }}">
                                                                                    Unggah Bukti Pembayaran</h5>
                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"
                                                                                    aria-label="Close"></button>
                                                                            </div>
                                                                            <form
                                                                                action="{{ route('id.customer.upload.payment') }}"
                                                                                method="POST"
                                                                                enctype="multipart/form-data">
                                                                                @csrf
                                                                                <div class="modal-body">
                                                                                    <input type="hidden"
                                                                                        name="invoice_id"
                                                                                        value="{{ $invoice->id }}">
                                                                                    <div class="mb-3">
                                                                                        <label for="payment_proof"
                                                                                            class="form-label">File Bukti
                                                                                            Pembayaran</label>
                                                                                        <input class="form-control"
                                                                                            type="file"
                                                                                            id="payment_proof"
                                                                                            name="payment_proof" required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-secondary"
                                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary">Unggah</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="card mb-5">
                                            <div class="card-body text-center text-muted">
                                                Tidak ada transaksi pembayaran yang tertunda.
                                            </div>
                                        </div>
                                    @endforelse

                                    <div class="d-flex justify-content-center">
                                        {{ $paymentPendingInvoices->links() }}
                                    </div>
                                </div>

                                <!-- Kolom kanan: informasi pembayaran -->
                                <div class="col-xl-4 col-lg-5">
                                    <div class="card sticky-top" style="top: 90px;">
                                        <div class="card-header">
                                            <h3 class="card-title mb-0">Informasi Pembayaran</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-warning mb-4">
                                                <h4 class="alert-heading">Bayar Sekarang</h4>
                                                <p>Jika pembayaran tidak dilakukan dalam waktu 24 jam, pesanan akan otomatis
                                                    dibatalkan.</p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="payment_paid" role="tabpanel">
                        <div class="row g-5 g-xxl-8">
                            <div class="col-xl-6">
                                @forelse ($paymentPaidInvoices as $invoice)
                                    <div class="card mb-5 mb-xxl-8">
                                        <div class="card-body pb-0">
                                            <div class="d-flex align-items-center mb-5">
                                                <div class="d-flex align-items-center flex-grow-1">
                                                    <div class="symbol symbol-45px me-5">
                                                        @if ($invoice->transaksi->is_request == 1)
                                                            {{-- Jika transaksi bertipe Request, tampilkan gambar blank.png --}}\
                                                            <img src="{{ asset('backend/assets/media/produk/blank.png') }}"
                                                                alt="Blank Image" />
                                                        @elseif ($invoice->transaksi->items->isNotEmpty() && optional($invoice->transaksi->items->first()->produk)->gambar)
                                                            {{-- Jika ada produk dan memiliki gambar --}}\
                                                            <img src="{{ asset('backend/assets/media/produk/' . $invoice->transaksi->items->first()->produk->gambar) }}"
                                                                alt="Produk" />
                                                        @else
                                                            {{-- Jika tidak ada gambar --}}\
                                                            <img src="{{ asset('backend/assets/media/produk/no-image.png') }}"
                                                                alt="No Image" />
                                                        @endif
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <a href="#"
                                                            class="text-gray-900 text-hover-primary fs-6 fw-bold">{{ $invoice->transaksi->idtransaksi }}</a>
                                                        <span class="text-gray-500 fw-bold">Dibayar pada
                                                            {{ $invoice->updated_at->format('d M Y, H:i') }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex my-2">
                                                    <span class="badge badge-light-success">Pembayaran Lunas</span>
                                                </div>
                                            </div>
                                            @foreach ($invoice->transaksi->items as $item)
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="d-flex">
                                                        <span
                                                            class="text-gray-800 fw-bold">{{ optional($item->produk)->nama_produk ?? 'Produk tidak tersedia' }}</span>
                                                        <span class="text-gray-600 ms-2">|| Qty:
                                                            {{ $item->qty }}</span>
                                                        <span class="text-gray-600 ms-2">|| Jenis:
                                                            {{ data_get(json_decode($item->jenis), 'jenis') }}</span>
                                                        <span class="text-gray-600 ms-2">|| Ukuran:
                                                            {{ data_get(json_decode($item->ukuran), 'nama_ukuran') }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="d-flex justify-content-between mt-2">
                                                @if ($invoice->kode_inv)
                                                    <a href="{{ route('id.customer.invoice.show', $invoice->id) }}"
                                                        class="btn btn-primary btn-sm" target="_blank">
                                                        <i class="ki-outline ki-eye fs-4 me-2"></i>Lihat Faktur
                                                    </a>
                                                @endif
                                                @if ($invoice->faktur)
                                                    <a href="{{ asset('/' . $invoice->faktur) }}"
                                                        class="btn btn-success btn-sm" target="_blank">
                                                        <i class="ki-outline ki-document fs-4 me-2"></i>Lihat Faktur Pajak
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="card mb-5 mb-xxl-8">
                                        <div class="card-body text-center">
                                            <p class="text-muted">Tidak ada pesanan yang sudah dibayar ditemukan.</p>
                                        </div>
                                    </div>
                                @endforelse
                                <div class="d-flex justify-content-center">
                                    {{ $paymentPaidInvoices->appends(request()->except('payment_paid_page'))->links() }}
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-5 mb-xxl-8">
                                    <div class="card-body">
                                        <div class="alert alert-success">
                                            <h4 class="alert-heading">Pembayaran Berhasil</h4>
                                            <p>Pembayaran untuk pesanan ini telah berhasil dikonfirmasi. Pesanan Anda akan
                                                segera diproses.</p>
                                            <hr>
                                            <p class="mb-0">Untuk informasi lebih lanjut, silakan hubungi layanan
                                                pelanggan kami.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tab for Order Custom -->
                    <div class="tab-pane fade" id="order_custom" role="tabpanel">
                        <div class="row g-5 g-xxl-8">
                            <div class="col-xl-8">
                                @forelse($customOrders as $order)
                                    <div class="card mb-5 mb-xxl-8 shadow-sm">
                                        <div class="card-header">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px me-5">
                                                    <div class="symbol-label bg-light-primary">
                                                        <i class="ki-outline ki-file-text fs-2 text-primary"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column flex-grow-1">
                                                    <a href="#"
                                                        class="text-gray-900 text-hover-primary fs-5 fw-bold">{{ $order->request_id }}</a>
                                                    <span class="text-gray-500 fw-semibold">Dipesan pada
                                                        {{ $order->created_at->format('d M Y, H:i') }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge badge-light-info fs-7 fw-bold">Permintaan untuk
                                                        Penawaran</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-gray-800 fw-bold">Subjek: <span
                                                    class="fw-normal">{{ $order->subject }}</span></p>
                                            <p class="text-gray-800 fw-bold">Pesan: <span
                                                    class="fw-normal">{{ $order->message }}</span></p>
                                            @if ($order->file_path)
                                                <a href="{{ asset('storage/' . $order->file_path) }}" target="_blank"\
                                                    class="btn btn-sm btn-light-primary">Lihat Lampiran</a>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="text-center text-gray-600">Anda belum memiliki pesanan khusus.</p>
                                        </div>
                                    </div>
                                @endforelse
                                <div class="d-flex justify-content-center">
                                    {{ $customOrders->links() }}
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card mb-5 mb-xxl-8">
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <h4 class="alert-heading">Informasi Pesanan Khusus</h4>
                                            <p>Pesanan khusus adalah permintaan khusus untuk produk yang tidak tersedia di
                                                katalog
                                                standar kami. Pesanan ini memerlukan persetujuan dan mungkin memerlukan
                                                waktu lebih lama untuk diproses.</p>
                                            <hr>
                                            <p class="mb-0">Untuk informasi lebih lanjut, hubungi layanan pelanggan kami.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tab for Kirim -->
                    <div class="tab-pane fade" id="kirim" role="tabpanel">
                        <div class="row g-5 g-xxl-8">
                            <div class="col-xl-6">
                                @forelse ($shippedOrders as $order)
                                    <div class="card mb-5 mb-xxl-8">
                                        <div class="card-body pb-0">
                                            <div class="d-flex align-items-center mb-5">
                                                <div class="d-flex align-items-center flex-grow-1">
                                                    <div class="symbol symbol-45px me-5">
                                                        @if ($order->items->isNotEmpty() && optional($order->items->first()->produk)->gambar)
                                                            <img src="{{ asset('backend/assets/media/produk/' . $order->items->first()->produk->gambar) }}"
                                                                alt="" />
                                                        @else
                                                            <img src="{{ asset('backend/assets/media/produk/no-image.png') }}"
                                                                alt="No Image" />
                                                        @endif
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <a href="#"
                                                            class="text-gray-900 text-hover-primary fs-6 fw-bold">{{ $order->idtransaksi }}</a>
                                                        <span class="text-gray-500 fw-bold">Dikirim pada
                                                            {{ $order->updated_at->format('d M Y, H:i') }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex my-2">
                                                    <span class="badge badge-light-primary">Dikirim</span>
                                                </div>
                                            </div>
                                            @foreach ($order->items as $item)
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="d-flex">
                                                        <span
                                                            class="text-gray-800 fw-bold">{{ optional($item->produk)->nama_produk ?? 'Produk tidak tersedia' }}</span>
                                                        <span class="text-gray-600 ms-2">|| Qty:
                                                            {{ $item->qty }}</span>
                                                        <span class="text-gray-600 ms-2">|| Jenis:
                                                            {{ data_get(json_decode($item->jenis), 'jenis') }}</span>
                                                        <span class="text-gray-600 ms-2">|| Ukuran:
                                                            {{ data_get(json_decode($item->ukuran), 'nama_ukuran') }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if ($order->status == 3)
                                                <div class="d-flex justify-content-end mt-4">
                                                    <form action="{{ route('id.frontend.orders.diterima', $order->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="ki-outline ki-check-circle fs-4 me-2"></i>Pesanan
                                                            Diterima
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="card mb-5 mb-xxl-8">
                                        <div class="card-body text-center">
                                            <p class="text-muted">Tidak ada pesanan yang dikirim ditemukan.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-5 mb-xxl-8">
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <h4 class="alert-heading">Pesanan Dikirim</h4>
                                            <p>Pesanan Anda saat ini sedang dalam pengiriman. Anda akan menerima notifikasi
                                                setelah pesanan tiba.</p>
                                            <hr>
                                            <p class="mb-0">Untuk informasi lebih lanjut, silakan hubungi layanan
                                                pelanggan kami.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Content container-->
                </div>
            </div>
        </div>
    </div>
@endsection
