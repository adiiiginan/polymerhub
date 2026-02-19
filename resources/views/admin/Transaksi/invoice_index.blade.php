@extends('Admin.dashboard')

@section('title', 'Purchase Order')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            Invoice </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('Admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Invoice</li>
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
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $key => $invoice)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>{{ $invoice->kode_inv }}</td>
                                        <td>{{ $invoice->transaksi->user->user_detail->nama ?? '-' }}</td>
                                        <td>Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($invoice->status == 7)
                                                <span class="badge bg-primary">Issued</span>
                                            @elseif ($invoice->status == 9)
                                                <span class="badge bg-warning">Unpaid</span>
                                            @elseif ($invoice->status == 8)
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </td>
                                        <td>{{ $invoice->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.transaksi.invoice_show', $invoice->id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="bi bi-receipt"></i> Lihat Invoice
                                            </a>
                                            @if ($invoice->faktur)
                                                <a href="{{ route('admin.invoices.show_pajak', $invoice->id) }}"
                                                    target="_blank" class="btn btn-primary btn-sm">
                                                    <i class="ki-outline ki-eye fs-4 me-2"></i>Lihat Faktur Pajak
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-sm btn-secondary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#uploadModal{{ $invoice->id }}">
                                                    <i class="bi bi-upload"></i> Upload Faktur Pajak
                                                </button>
                                            @endif

                                            <!-- Upload/View Modal -->
                                            <div class="modal fade" id="uploadModal{{ $invoice->id }}" tabindex="-1"
                                                aria-labelledby="uploadModalLabel{{ $invoice->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="uploadModalLabel{{ $invoice->id }}">
                                                                @if ($invoice->faktur)
                                                                    Faktur Pajak
                                                                @else
                                                                    Upload Faktur Pajak
                                                                @endif
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        @if ($invoice->faktur)
                                                            <div class="modal-body">
                                                                <p>Faktur pajak sudah diunggah. Klik tombol di bawah untuk
                                                                    melihat.</p>
                                                                <a href="{{ route('admin.invoices.show_pajak', $invoice->id) }}"
                                                                    target="_blank" class="btn btn-primary w-100">
                                                                    <i class="bi bi-file-earmark-pdf"></i> Lihat File
                                                                </a>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        @else
                                                            <form
                                                                action="{{ route('admin.invoice.upload_pajak', $invoice->id) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="faktur-{{ $invoice->id }}"
                                                                            class="form-label">Pilih file
                                                                            PDF</label>
                                                                        <input class="form-control" type="file"
                                                                            name="faktur" id="faktur-{{ $invoice->id }}"
                                                                            accept=".pdf" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Upload</button>
                                                                </div>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                data-bs-target="#paidModal{{ $invoice->id }}">
                                                <i class="bi bi-check-circle-fill"></i> Paid
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="paidModal{{ $invoice->id }}" tabindex="-1"
                                                aria-labelledby="paidModalLabel{{ $invoice->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="paidModalLabel{{ $invoice->id }}">
                                                                Konfirmasi Pembayaran</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menandai invoice ini sebagai lunas?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('admin.invoice.updateStatus', $invoice->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                <input type="hidden" name="status" value="8">
                                                                <button type="submit"
                                                                    class="btn btn-success">Yakin</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#invoiceTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });

            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
@endpush
