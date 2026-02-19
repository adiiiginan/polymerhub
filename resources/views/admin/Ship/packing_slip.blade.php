@extends('admin.dashboard')

@section('title', 'Packing Slip')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            Packing Slip</h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('admin.ship.index') }}" class="text-muted text-hover-primary">Pesanan On
                                    Proses</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Packing Slip</li>
                        </ul>
                    </div>
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="#" class="btn btn-sm fw-bold btn-primary" onclick="window.print();"><i
                                class="fa fa-print"></i> Cetak</a>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card card-flush">
                    <div class="card-body pt-0">
                        <div class="row mt-5">
                            <div class="col-6">
                                <h4>Dikirim kepada:</h4>
                                <p>
                                    <strong>{{ $invoice->transaksi->user->name }}</strong><br>
                                    {{ $invoice->transaksi->user->userDetail->alamat ?? '-' }}<br>
                                    {{ $invoice->transaksi->user->userDetail->kota ?? '-' }},
                                    {{ $invoice->transaksi->user->userDetail->kode_pos ?? '-' }}<br>
                                    {{ $invoice->transaksi->user->userDetail->negara->nama_negara ?? '-' }}
                                </p>
                            </div>
                            <div class="col-6 text-end">
                                <h4>Order #{{ $invoice->kode_inv }}</h4>
                                <p>Tanggal Order: {{ $invoice->created_at->format('d F Y') }}</p>
                            </div>
                        </div>

                        <div class="table-responsive mt-5">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">Gambar</th>
                                        <th class="min-w-175px">Produk</th>
                                        <th class="min-w-70px text-end">Jumlah</th>
                                        <th class="min-w-100px text-center">Check</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($invoice->transaksi->details as $detail)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="#" class="symbol symbol-100px">
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
                                            <td class="text-end fs-1">{{ $detail->qty }}</td>
                                            <td class="text-center">
                                                <div class="form-check form-check-custom form-check-solid form-check-lg">
                                                    <input class="form-check-input" type="checkbox" value="" />
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
