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
                            Daftar Produk</h1>
                        <!--end::Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">
                                <a href="index.html" class="text-muted text-hover-primary">Home</a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">Daftar Produk</li>
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
        <!--div class="d-flex flex-column flex-column-fluid">
                                                                                                                                                                                            begin::Toolba
                                                                                                                                                                                                <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
                                                                                                                                                                                                    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                                                                                                                                                                                                        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                                                                                                                                                                                                            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                                                                                                                                                                                                                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                                                                                                                                                                                                                    Daftar Produk</h1>
                                                                                                                                                                                                                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                                                                                                                                                                                                    <li class="breadcrumb-item text-muted">
                                                                                                                                                                                                                        <a href="" class="text-muted text-hover-primary">Home</a>
                                                                                                                                                                                                                    </li>
                                                                                                                                                                                                                    <li class="breadcrumb-item">
                                                                                                                                                                                                                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                                                                                                                                                                                                    </li>
                                                                                                                                                                                                                    <li class="breadcrumb-item text-muted">Produk</li>
                                                                                                                                                                                                                </ul>
                                                                                                                                                                                                            </div>
                                                                                                                                                                                                            <div class="d-flex align-items-center gap-2 gap-lg-3">
                                                                                                                                                                                                                <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">
                                                                                                                                                                                                                    <i class="fas fa-plus"></i> Tambah Produk
                                                                                                                                                                                                                </a>
                                                                                                                                                                                                            </div>
                                                                                                                                                                                                        </div>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                </div>
                                                                                                                                                                                                end::Toolbar-->

        <!--begin::Content-->
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
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <div class="w-100 mw-150px">
                                <select class="form-select form-select-solid" id="statusFilter">
                                    <option value="">Semua Status</option>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
                                </select>
                            </div>
                            <h1>Edit Stok Opname</h1>

                            <form action="{{ route('admin.stok-opname.update', $stokOpname->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="kode_opname">Kode Opname</label>
                                    <input type="text" name="kode_opname" class="form-control"
                                        value="{{ $stokOpname->kode_opname }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="datetime-local" name="tanggal" class="form-control"
                                        value="{{ date('Y-m-d\TH:i', strtotime($stokOpname->tanggal)) }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="iduser">Petugas</label>
                                    <select class="form-control" disabled>
                                        @foreach ($users as $user)
                                            <option {{ $stokOpname->iduser == $user->id ? 'selected' : '' }}>
                                                {{ optional($user->userDetail)->nama ?? $user->username }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="iduser" value="{{ $stokOpname->iduser }}">
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <input type="text" name="status" class="form-control"
                                        value="{{ $stokOpname->status }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea name="keterangan" class="form-control">{{ $stokOpname->keterangan }}</textarea>
                                </div>

                                <hr>

                                <h3>Detail Stok Opname</h3>
                                <table class="table" id="details-table">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Stok Sistem</th>
                                            <th>Stok Fisik</th>
                                            <th>Catatan</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stokOpname->details as $index => $detail)
                                            <tr>
                                                <td>
                                                    <select name="details[{{ $index }}][produk_stok_id]"
                                                        class="form-control">
                                                        <option value="">Pilih Produk</option>
                                                        @foreach ($productStoks as $stok)
                                                            <option value="{{ $stok->id }}"
                                                                {{ $detail->produk_stok_id == $stok->id ? 'selected' : '' }}>
                                                                {{ $stok->produk->nama_produk }} -
                                                                {{ $stok->ukuran->nama_ukuran }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="number" name="details[{{ $index }}][stok_sistem]"
                                                        class="form-control stok-sistem" value="{{ $detail->stok_sistem }}"
                                                        required readonly>
                                                </td>
                                                <td><input type="number" name="details[{{ $index }}][stok_fisik]"
                                                        class="form-control" value="{{ $detail->stok_fisik }}" required>
                                                </td>
                                                <td><input type="text" name="details[{{ $index }}][catatan]"
                                                        class="form-control" value="{{ $detail->catatan }}"></td>
                                                <td><button type="button"
                                                        class="btn btn-danger remove-detail-row">Hapus</button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" id="add-detail-row" class="btn btn-primary">Tambah Produk</button>

                                <hr>

                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                            </form>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                let detailRowIndex = {{ count($stokOpname->details) }};

                                document.getElementById('add-detail-row').addEventListener('click', function() {
                                    const tableBody = document.querySelector('#details-table tbody');
                                    const newRow = document.createElement('tr');

                                    newRow.innerHTML = `
                    <td>
                        <select name="details[${detailRowIndex}][produk_stok_id]" class="form-control">
                            <option value="">Pilih Produk</option>
                            @foreach ($productStoks as $stok)
                                <option value="{{ $stok->id }}">{{ $stok->produk->nama_produk }} - {{ $stok->ukuran->nama_ukuran }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="details[${detailRowIndex}][stok_sistem]" class="form-control stok-sistem" required readonly></td>
                    <td><input type="number" name="details[${detailRowIndex}][stok_fisik]" class="form-control" required></td>
                    <td><input type="text" name="details[${detailRowIndex}][catatan]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger remove-detail-row">Hapus</button></td>
                `;

                                    tableBody.appendChild(newRow);
                                    detailRowIndex++;
                                });

                                document.querySelector('#details-table').addEventListener('click', function(e) {
                                    if (e.target && e.target.classList.contains('remove-detail-row')) {
                                        e.target.closest('tr').remove();
                                    }
                                });

                                document.querySelector('#details-table').addEventListener('change', function(e) {
                                    if (e.target && e.target.name.endsWith('[produk_stok_id]')) {
                                        const produkStokId = e.target.value;
                                        const stokSistemInput = e.target.closest('tr').querySelector('.stok-sistem');

                                        if (produkStokId) {
                                            fetch(`/admin/stok-opname/get-stok/${produkStokId}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    stokSistemInput.value = data.stok;
                                                });
                                        } else {
                                            stokSistemInput.value = '';
                                        }
                                    }
                                });
                            });
                        </script>
                    @endsection
