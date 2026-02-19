@extends('admin.layout.partials.app')
@section('title', 'Daftar Invoice')
@section('content')
    <style>
        .image-hover {
            cursor: pointer;
        }

        .image-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .image-modal img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }
    </style>
    <!-- Recent Users -->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            Daftar Invoice</h1>
                        <!--end::Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">
                                <a href="index.html" class="text-muted text-hover-primary">Transaksi</a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">Invoice</li>
                            <!--end::Item-->
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page title-->
                    <!--begin::Actions-->

                    <!--end::Actions-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-12"
                                    placeholder="Cari Produk" />
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Transaksi</th>
                                    <th>Kode Invoice</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Bukti</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $index => $invoice)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ optional($invoice->transaksi)->idtransaksi ?? '-' }}</td>
                                        <td>{{ $invoice->kode_inv }}</td>
                                        <td>{{ optional($invoice->transaksi?->user?->userDetail)->nama ?? '-' }}</td>
                                        <td>
                                            @if (optional($invoice->transaksi)->shipping_currency == 'IDR')
                                                Rp
                                            @else
                                                $
                                            @endif
                                            {{ number_format($invoice->transaksi?->total ?: $invoice->transaksi?->details?->sum(fn($d) => $d->harga * $d->qty), 2, ',', '.') }}
                                        </td>
                                        <td><span
                                                class="badge bg-primary">{{ $invoice->statusRelasi->status ?? 'Unknown' }}</span>
                                        </td>
                                        <td>{{ $invoice->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            @if ($invoice->bukti_bayar)
                                                <img src="{{ asset($invoice->bukti_bayar) }}" alt="Bukti Bayar"
                                                    width="100" height="100" style="object-fit: cover;"
                                                    class="image-hover" data-image="{{ asset($invoice->bukti_bayar) }}">
                                            @else
                                                Tidak ada bukti
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.transaksi.invoice_show', $invoice->id) }}"
                                                class="btn btn-sm btn-info" target="blank"> <i class="bi bi-eye"></i> Lihat
                                            </a>

                                            @if ($invoice->status == 7 || $invoice->status == 9)
                                                <form action="{{ route('admin.transaksi.paid', $invoice->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Anda yakin ingin menandai invoice ini sebagai lunas?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success"> <i
                                                            class="bi bi-check"></i> Paid </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-4">
                            {{-- Pagination links removed because $invoices is a collection, not a paginator --}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Image Modal -->
            <div class="image-modal" id="imageModal">
                <img id="modalImage" src="" alt="Bukti Bayar">
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const images = document.querySelectorAll('.image-hover');
                    const modal = document.getElementById('imageModal');
                    const modalImage = document.getElementById('modalImage');

                    images.forEach(img => {
                        img.addEventListener('mouseenter', function() {
                            const imageSrc = this.getAttribute('data-image');
                            modalImage.src = imageSrc;
                            modal.style.display = 'flex';
                        });
                    });

                    modal.addEventListener('mouseleave', function() {
                        modal.style.display = 'none';
                    });
                });
            </script>
        @endsection
