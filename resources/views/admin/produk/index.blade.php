@extends('admin.dashboard')

@section('content')
    <!-- Recent Users -->
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
                            @if (Auth::user()->id_priviladges != 4)
                                <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Produk
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="produkTable">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Image</th>
                                    <th class="text-end">Varian</th>
                                    <th class="text-end">Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($produk as $p)
                                    <tr>
                                        <td>{{ $p->kode_produk }}</td>
                                        <td>{{ $p->nama_produk }}</td>
                                        <td>
                                            @if ($p->gambar)
                                                <img src="{{ asset('backend/assets/media/produk/' . $p->gambar) }}"
                                                    alt="{{ $p->nama_produk }}" width="60" height="60"
                                                    style="object-fit: cover; border-radius: 8px;">
                                            @else
                                                <span class="text-muted">Tidak ada gambar</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($p->variants && $p->variants->isNotEmpty())
                                                <table class="table table-sm table-borderless fs-7">
                                                    <thead>
                                                        <tr class="text-muted">
                                                            <th>SKU</th>
                                                            <th>Jenis</th>
                                                            <th>Ukuran</th>
                                                            <th>Dimensi (LxWxH)</th>
                                                            <th>Berat (kg)</th>
                                                            <th class="text-end">Harga</th>
                                                            <th class="text-end">Stok</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($p->variants as $stok)
                                                            <tr>
                                                                <td>{{ $stok->sku ?? '-' }}</td>
                                                                <td>{{ $stok->jenis->jenis ?? '-' }}</td>
                                                                <td>{{ $stok->ukuran->nama_ukuran ?? '-' }}</td>
                                                                <td>
                                                                    @if ($stok->length && $stok->width && $stok->height)
                                                                        {{ $stok->length }} x {{ $stok->width }} x
                                                                        {{ $stok->height }} cm
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>{{ $stok->weight ?? '-' }}</td>
                                                                <td class="text-end">
                                                                    {{ number_format($stok->harga, 2, ',', '.') }}</td>
                                                                <td class="text-end">{{ $stok->stok }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <span class="text-muted">Tidak ada varian</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($p->status_aktif == 1)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>

                                        <td class="text-end">
                                            <a href="{{ route('admin.produk.show', $p->id) }}"
                                                class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.produk.edit', $p->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST"
                                                style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" title="Hapus"
                                                    onclick="return confirm('Hapus produk?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#produkTable').DataTable({
                responsive: true,
                autoWidth: false,
                order: [],
                paging: true,
                pageLength: 10,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });

            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#statusFilter').on('change', function() {
                var status = $(this).val();
                if (status === 'active') {
                    table.column(4).search('Aktif').draw();
                } else if (status === 'inactive') {
                    table.column(4).search('Tidak Aktif').draw();
                } else {
                    table.column(4).search('').draw();
                }
            });
        });
    </script>
@endpush
