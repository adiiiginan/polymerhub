@extends('admin.layout.partials.app')

@section('title', 'Invoice Lunas')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            Invoice Lunas </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Invoice Lunas</li>
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
                        @if (session('success'))
                            <div class="alert alert-success w-100">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-12"
                                    placeholder="Cari Invoice" />
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <form action="{{ route('admin.transaksi.invoice_paid') }}" method="GET"
                                class="d-flex align-items-center">
                                <div class="me-3">
                                    <select name="produk_id" class="form-select form-select-solid">
                                        <option value="">Semua Produk</option>
                                        @foreach ($produks as $produk)
                                            <option value="{{ $produk->id }}"
                                                {{ $produk_id == $produk->id ? 'selected' : '' }}>
                                                {{ $produk->nama_produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="{{ route('admin.transaksi.invoice_paid') }}" class="btn btn-secondary">Reset</a>
                            </form>
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-body pt-0">
                        <table id="invoiceTable" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Invoice</th>
                                    <th>Customer</th>
                                    <th>Negara</th>
                                    <th>Total Produk</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $key => $invoice)
                                    <tr>
                                        <td>{{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}
                                        </td>
                                        <td>{{ $invoice->kode_inv }}</td>
                                        <td>{{ $invoice->transaksi?->user?->userDetail?->nama ?? '-' }}</td>
                                        <td>{{ $invoice->transaksi?->address?->country?->country_name ?? '-' }}</td>}}
                                        </td>

                                        <td>{{ $invoice->transaksi?->details->sum('qty') ?? 0 }}</td>
                                        <td>
                                            {{ $invoice->transaksi?->shipping_currency == 'IDR' ? 'Rp' : '$' }}
                                            {{ number_format($invoice->transaksi?->total ?: $invoice->transaksi?->details?->sum(fn($d) => $d->harga * $d->qty), $invoice->transaksi?->shipping_currency == 'IDR' ? 0 : 2, ',', '.') }}
                                        </td>
                                        <td>
                                            @if ($invoice->status == 8)
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </td>
                                        <td>{{ $invoice->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.transaksi.invoice_show', $invoice->id) }}"
                                                    class="btn btn-sm btn-info"target="_blank">
                                                    <i class="bi bi-receipt"></i> Lihat Invoice
                                                </a>

                                                @if ($invoice->faktur)
                                                    <a href="{{ asset($invoice->faktur) }}" class="btn btn-success btn-sm"
                                                        target="_blank">
                                                        <i class="ki-outline ki-document fs-4 me-2"></i>Lihat Faktur Pajak
                                                    </a>

                                                    <button type="button" class="btn btn-sm btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#uploadPajakModal{{ $invoice->id }}">
                                                        <i class="bi bi-pencil-square"></i> Ganti Faktur
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#uploadPajakModal{{ $invoice->id }}">
                                                        <i class="bi bi-upload"></i> Upload Faktur
                                                    </button>
                                                @endif
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @foreach ($invoices as $invoice)
                        <div class="modal fade" id="uploadPajakModal{{ $invoice->id }}" tabindex="-1"
                            aria-labelledby="uploadPajakModalLabel{{ $invoice->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="uploadPajakModalLabel{{ $invoice->id }}">
                                            @if ($invoice->faktur)
                                                Ganti Faktur Pajak untuk {{ $invoice->kode_inv }}
                                            @else
                                                Upload Faktur Pajak untuk {{ $invoice->kode_inv }}
                                            @endif
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.invoices.upload_pajak', $invoice->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="faktur" class="form-label">File Faktur (PDF, JPG, PNG)
                                                </label>
                                                <input type="file" name="faktur" id="faktur" class="form-control"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="card-footer">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
