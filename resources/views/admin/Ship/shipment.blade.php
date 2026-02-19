@extends('admin.layout.partials.app')

@section('title', 'Daftar Pengiriman Selesai')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            Daftar Pengiriman Selesai</h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Pengiriman</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Pengiriman Selesai</h3>
                    </div>
                    <div class="card-body">
                        <table id="kt_datatable_example_5"
                            class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
                            <thead>
                                <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                    <th>No</th>
                                    <th>No. Invoice</th>
                                    <th>No. Shipp</th>
                                    <th>Nama Customer</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $loop->iteration + $invoices->firstItem() - 1 }}</td>
                                        <td>{{ $invoice->kode_inv }}</td>
                                        <td>{{ $invoice->kode_ship ?? 'N/A' }}</td>
                                        <td>{{ $invoice->transaksi->user->user_detail->nama ?? 'N/A' }}</td>
                                        <td data-order="{{ $invoice->created_at ? $invoice->created_at->timestamp : 0 }}">
                                            {{ $invoice->created_at ? $invoice->created_at->format('d-m-Y H:i') : 'N/A' }}
                                        </td>
                                        <td>
                                            @if ($invoice->status == 3)
                                                <span class="badge badge-light-success">Siap Kirim</span>
                                            @elseif ($invoice->status == 9)
                                                <span class="badge badge-light-primary">Terkirim</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if (auth()->user()->id_priviladges == 7)
                                                @if ($invoice->proses)
                                                    <a href="{{ route('admin.ship.print_surat_jalan_dan_resi', ['id' => $invoice->proses->id]) }}"
                                                        target="_blank" class="btn btn-sm btn-primary">Print Dokumen</a>
                                                @endif
                                            @endif
                                            @if ($invoice->no_resi)
                                                <span class="badge badge-success">Sudah Update Resi</span>
                                            @else
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#updateResiModal{{ $invoice->id }}">Input Resi</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>

                <!-- Modal for updating resi -->
                @foreach ($invoices as $invoice)
                    <div class="modal fade" id="updateResiModal{{ $invoice->id }}" tabindex="-1"
                        aria-labelledby="updateResiModalLabel{{ $invoice->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateResiModalLabel{{ $invoice->id }}">Update Resi -
                                        {{ $invoice->kode_inv ?? 'N/A' }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.ship.update_resi', $invoice->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="no_resi{{ $invoice->id }}" class="form-label">No Resi</label>
                                            <input type="text" class="form-control" id="no_resi{{ $invoice->id }}"
                                                name="no_resi" value="{{ $invoice->no_resi }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="jasa_ekspedisi{{ $invoice->id }}" class="form-label">Jasa
                                                Ekspedisi</label>
                                            <input type="text" class="form-control"
                                                id="jasa_ekspedisi{{ $invoice->id }}" name="jasa_ekspedisi"
                                                value="{{ $invoice->expedisi }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update Resi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection
