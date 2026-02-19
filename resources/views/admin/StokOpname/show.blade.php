@extends('Admin.Layout.partials.app')

@section('content')
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
                            Detail Stok Opname</h1>
                        <!--end::Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('admin.stok-opname.index') }}" class="text-muted text-hover-primary">Stok
                                    Opname</a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">Detail</li>
                            <!--end::Item-->
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page title-->
                    <!--begin::Actions-->
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        @if ($stokOpname->status === 'draft')
                            <a href="{{ route('admin.stok-opname.adjustment', $stokOpname->id) }}" class="btn btn-warning"
                                onclick="return confirm('Apakah Anda yakin ingin melakukan adjustment untuk stok opname ini?')">
                                Adjustment
                            </a>
                            <form action="{{ route('admin.stok-opname.approve', $stokOpname->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Apakah Anda yakin ingin menyetujui stok opname ini?')">
                                    Approve
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.stok-opname.index') }}" class="btn btn-primary">Kembali</a>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <h3>Informasi Stok Opname</h3>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Kode Opname</label>
                                    <p class="form-control-plaintext">{{ $stokOpname->kode_opname }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Tanggal Mulai</label>
                                    <p class="form-control-plaintext">{{ $stokOpname->tanggal_mulai }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Tanggal Selesai</label>
                                    <p class="form-control-plaintext">{{ $stokOpname->tanggal_selesai }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Petugas</label>
                                    <p class="form-control-plaintext">
                                        {{ $stokOpname->details->first()->opname->user->userDetail->nama ?? ($stokOpname->details->first()->opname->user->username ?? 'N/A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <p class="form-control-plaintext">
                                        @if ($stokOpname->status == 'draft')
                                            <span class="badge badge-light-warning">Draft</span>
                                        @elseif ($stokOpname->status == 'adjusted')
                                            <span class="badge badge-light-primary">Adjusted</span>
                                        @elseif ($stokOpname->status == 'approved')
                                            <span class="badge badge-light-success">Approved</span>
                                        @else
                                            <span class="badge badge-light-info">{{ ucfirst($stokOpname->status) }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Keterangan</label>
                                    <p class="form-control-plaintext">{{ $stokOpname->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-flush mt-5">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <h3>Detail Stok Opname</h3>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Produk</th>
                                    <th class="text-end">Stok Sistem</th>
                                    <th class="text-end">Stok Fisik</th>
                                    <th class="text-end">Selisih</th>
                                    <th>Catatan</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($stokOpname->details as $index => $detail)
                                    <tr>
                                        <td>{{ $detail->produkStok->produk->nama_produk ?? 'N/A' }} -
                                            {{ $detail->produkStok->ukuran->nama_ukuran ?? 'N/A' }}</td>
                                        <td class="text-end">{{ $detail->stok_sistem }}</td>
                                        <td class="text-end">{{ $detail->stok_fisik }}</td>
                                        <td class="text-end">{{ $detail->selisih }}</td>
                                        <td>{{ $detail->catatan }}</td>
                                        <td class="text-end">
                                            @if ($detail->selisih != 0 && $stokOpname->status != 'approved')
                                                <a href="{{ route('admin.stok-opname.sesuaikan', ['id' => $stokOpname->id, 'detailId' => $detail->id]) }}"
                                                    class="btn btn-primary btn-sm">Sesuaikan</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ session('success') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#detailTable').DataTable({
                responsive: true,
                autoWidth: false,
                paging: true,
                pageLength: 10,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
    </script>
@endpush

@section('scripts')
    <script>
        $(document).ready(function() {
            // Show modal with success message
            @if (session('success'))
                var successModal = new bootstrap.Modal(document.getElementById('successModal'), {});
                successModal.show();
            @endif
        });
    </script>
@endsection
