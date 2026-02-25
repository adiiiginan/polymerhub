@extends('admin.layout.partials.app')

@section('title', 'List Shipping Process')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            List Shipping Process</h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Pesanan</li>
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
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-12"
                                    placeholder="Cari Pesanan" />
                            </div>
                        </div>

                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="produkTable">


                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Invoice</th>
                                    <th>No Shipp</th>
                                    <th>Customer</th>
                                    <th>Negara</th>
                                    <th>Total</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr class="@if ($invoice->created_at->gt(now()->subMinutes(10))) table-info @endif">
                                        <td>{{ $loop->iteration + $invoices->firstItem() - 1 }}</td>
                                        <td>{{ $invoice->kode_inv }}</td>
                                        <td>-</td>
                                        <td>{{ $invoice->transaksi->user->userDetail->nama ?? '-' }}</td>
                                        <td>{{ $invoice->transaksi?->address?->country?->country_name ?? '-' }}</td>
                                        <td>
                                            {{ $invoice->transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}
                                            {{ number_format($invoice->total, $invoice->transaksi->shipping_currency == 'IDR' ? 0 : 2, ',', '.') }}
                                        </td>
                                        <td data-order="{{ $invoice->created_at->timestamp }}">
                                            {{ $invoice->created_at->format('d-m-Y H:i:s') }}</td>
                                        <td>
                                            <span
                                                class="badge @if ($invoice->status == 8) bg-success @elseif($invoice->status == 7) bg-warning @else bg-danger @endif">
                                                @if ($invoice->status == 8)
                                                    On Process
                                                @elseif($invoice->status == 7)
                                                    Invoice Issued
                                                @else
                                                    Canceled
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.ship.show', $invoice->id) }}"
                                                class="btn btn-primary btn-sm">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $invoices->links() }}
                    </div>
                @endsection
