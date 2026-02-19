@extends('admin.dashboard')

@section('title', 'Detail Pesanan')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            Detail Pesanan</h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('admin.ship.index') }}" class="text-muted text-hover-primary">Pesanan On
                                    Proses</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Detail Pesanan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Order #{{ $invoice->kode_inv }} @if ($invoice->transaksi && $invoice->transaksi->is_request == 1)
                                    <span class="badge badge-warning">Request</span>
                                @endif
                            </h2>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ route('admin.ship.packing_slip', $invoice->id) }}" class="btn btn-info me-2">Packing
                                Slip</a>
                            @if ($invoice->status == 8)
                                <form method="POST" action="{{ route('admin.ship.shipping', $invoice->id) }}"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($invoice->no_resi && $invoice->jasa_ekspedisi)
                            <div class="mb-5">
                                <h4>Informasi Pengiriman</h4>
                                <p>No. Resi: {{ $invoice->no_resi }}</p>
                                <p>Jasa Ekspedisi: {{ $invoice->jasa_ekspedisi }}</p>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-175px">Gambar</th>
                                        <th class="min-w-175px">Produk</th>

                                        <th class="min-w-70px text-end">Jumlah</th>
                                        <th class="min-w-100px text-end">Harga Satuan</th>
                                        <th class="min-w-100px text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($invoice->transaksi->details as $detail)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="#" class="symbol symbol-200px">
                                                        <span class="symbol-label"
                                                            style="background-image: url('{{ asset('backend/assets/media/produk/' . $detail->produk->gambar) }}')"></span>
                                                    </a>

                                                </div>

                                            </td>
                                            <td>
                                                <div class="ms-5">
                                                    <a href="#"
                                                        class="fw-bold text-gray-600 text-hover-primary">{{ $detail->produk->nama_produk }}</a>
                                                    <div class="text-sm text-gray-500">
                                                        @if ($detail->jenis)
                                                            <p>Shape: {{ $detail->jenis->jenis }}</p>
                                                        @endif
                                                        @if ($detail->ukuran)
                                                            <p>Ukuran: {{ $detail->ukuran->nama_ukuran }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-end">{{ $detail->qty }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->harga, 2, ',', '.') }}</td>
                                            <td class="text-end">Rp
                                                {{ number_format($detail->harga * $detail->qty, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4" class="text-end">Subtotal</td>
                                        <td class="text-end">$
                                            {{ number_format($invoice->total, 2, ',', '.') }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="4" class="fs-3 text-gray-900 text-end">Grand Total</td>
                                        <td class="text-gray-900 fs-3 fw-bolder text-end">Rp
                                            {{ number_format($invoice->total + $invoice->total * 0.11, 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!--begin::Modal - Success-->
    <div class="modal fade" id="kt_modal_success" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ session('success') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Success-->
@endsection

@section('scripts')
    @if (session('success'))
        <script>
            var successModal = new bootstrap.Modal(document.getElementById('kt_modal_success'));
            successModal.show();
        </script>
    @endif
@endsection
