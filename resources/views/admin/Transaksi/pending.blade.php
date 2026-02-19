@extends('admin.dashboard')

@section('title', 'Transaksi Pending')
<style>
    .btn-md {
        padding: 4px 10px;
        font-size: 13px;
        line-height: 1.2;
    }
</style>
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            Order Confirmation</h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Order Pending</li>
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
                                    placeholder="Cari Transaksi" />
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="transaksiTable">
                            <thead class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Nama Customer</th>
                                    <th>Email</th>
                                    <th>Negara</th>
                                    <th>Total</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($transaksi as $tr)
                                    <tr id="row-{{ $tr->id }}">
                                        <td>{{ $tr->idtransaksi }}</span></td>
                                        <td>{{ $tr->user->userDetail->nama ?? 'N/A' }}</td>
                                        </td>
                                        <td>{{ $tr->user->email ?? '-' }}</td>
                                        <td>
                                            @php
                                                $primaryAddress = $tr->user->addresses->firstWhere('is_primary', 1);
                                            @endphp
                                            {{ $primaryAddress && $primaryAddress->country ? $primaryAddress->country->country_name : 'N/A' }}
                                        </td>
                                        <td>$ {{ number_format($tr->total ?? 0, 2, ',', '.') }}</td>
                                        <td>{{ $tr->created_at->format('d M Y - H:i') }}</td>
                                        <td><span class="badge bg-warning btn-md">Pending</span></td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.transaksi.detail', $tr->id) }}"
                                                class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#declineModal-{{ $tr->id }}">
                                                <i class="fa fa-times"></i> Decline
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                data-bs-target="#approveModal-{{ $tr->id }}">
                                                <i class="fa fa-check"></i> Approve
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade" id="approveModal-{{ $tr->id }}" tabindex="-1"
                                        aria-labelledby="approveModalLabel-{{ $tr->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="approveModalLabel-{{ $tr->id }}">
                                                        Confirm
                                                        Approval</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menyetujui transaksi ini?</p>
                                                    <form action="{{ route('admin.transaksi.approve', $tr->id) }}"
                                                        method="POST" id="approveForm-{{ $tr->id }}"
                                                        class="approve-form">
                                                        @csrf
                                                        <input type="hidden" name="transaction_id"
                                                            value="{{ $tr->id }}">
                                                        <div class="mb-3">
                                                            <label for="kode_po_{{ $tr->id }}"
                                                                class="form-label">Kode
                                                                PO Manual:</label>
                                                            <input type="text"
                                                                class="form-control @error('kode_po') is-invalid @enderror"
                                                                id="kode_po_{{ $tr->id }}" name="kode_po" required
                                                                placeholder="Contoh: SO2024100">
                                                            @error('kode_po')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success"
                                                        form="approveForm-{{ $tr->id }}">
                                                        <i class="fa fa-check"></i> Ya, Setujui
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Decline Modal -->
                                    <div class="modal fade" id="declineModal-{{ $tr->id }}" tabindex="-1"
                                        aria-labelledby="declineModalLabel-{{ $tr->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="declineModalLabel-{{ $tr->id }}">
                                                        Decline
                                                        Transaksi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Anda yakin ingin menolak transaksi ini?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.transaksi.decline', $tr->id) }}"
                                                        method="POST" class="decline-form">
                                                        @csrf
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fa fa-times"></i> Yes, Decline
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-4">
                            {{ $transaksi->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection

<script>
    // Check for success message from session
    @if (session('success'))
        alert('{{ session('success') }}');
        location.reload();
    @endif

    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            var approveModal = new bootstrap.Modal(document.getElementById(
                'approveModal-{{ old('transaction_id') }}'));
            approveModal.show();
        @endif
    });
</script>
