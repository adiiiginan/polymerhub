@extends('admin.dashboard')

@section('title', 'Purchase Order')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            Order Accepted</h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Order</li>
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
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-12"
                                    placeholder="Cari Transaksi" />
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="transaksiTable">
                            <thead class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <tr>
                                    <th>No</th>
                                    <th>ID Transaksi</th>
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th>Total</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($transaksi as $key => $tr)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $tr->idtransaksi }}</span></td>
                                        <td>{{ $tr->user->userDetail->nama ?? 'N/A' }}</td>
                                        <td>{{ $tr->user->email ?? '-' }}</td>
                                        <td>
                                            $
                                            {{ number_format($tr->total ?: $tr->details->sum(fn($d) => $d->harga * $d->qty), 2, ',', '.') }}
                                        </td>
                                        <td>{{ $tr->created_at->format('d M Y - H:i') }}</td>
                                        <td>
                                            @if ($tr->status == 3)
                                                <span class="badge bg-success">Shipped</span>
                                            @elseif($tr->status == 4)
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($tr->status == 5)
                                                <span class="badge bg-success">Cancelled</span>
                                            @elseif($tr->status == 6)
                                                <span class="badge bg-success">On Procces</span>
                                            @elseif($tr->status == 7)
                                                <span class="badge bg-success">Invoice Issued</span>
                                            @elseif($tr->status == 8)
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($tr->status == 9)
                                                <span class="badge bg-success">Unpaid</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.Transaksi.detailpo', $tr->id) }}" target="_blank"
                                                class="btn btn-sm btn-light">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if (!$tr->invoice || !$tr->invoice->kode_inv)
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#invoiceModal-{{ $tr->id }}">
                                                    <i class="fas fa-file-invoice"></i> Input Invoice
                                                </button>
                                            @else
                                                <a href="{{ route('admin.transaksi.invoice_show', $tr->invoice->id) }}"
                                                    target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detail Invoice
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Invoice Modal -->
                                    <div class="modal fade" id="invoiceModal-{{ $tr->id }}" tabindex="-1"
                                        aria-labelledby="invoiceModalLabel-{{ $tr->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="invoiceModalLabel-{{ $tr->id }}">
                                                        Input
                                                        Invoice Code</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.invoice.updateStatus', $tr->id) }}"
                                                    method="POST" class="invoice-form">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="kode_inv_{{ $tr->id }}"
                                                                class="form-label">Kode
                                                                Invoice:</label>
                                                            <input type="text" class="form-control"
                                                                id="kode_inv_{{ $tr->id }}" name="kode_inv" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const table = $('#transaksiTable').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });

            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
@endpush
