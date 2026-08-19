@extends('admin.dashboard')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">

        {{-- Toolbar --}}
        <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            Detail Produk
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('admin.produk.index') }}" class="text-muted text-hover-primary">Daftar
                                    Produk</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Detail Produk</li>
                        </ul>
                    </div>
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-sm btn-light">
                            <i class="ki-outline ki-arrow-left fs-4 me-1"></i> Kembali
                        </a>
                        <a href="{{ route('admin.produk.edit', $produk->id) }}" class="btn btn-sm btn-primary">
                            <i class="ki-outline ki-pencil fs-4 me-1"></i> Edit Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">

                <div class="d-flex flex-column flex-lg-row gap-7 gap-lg-10">

                    {{-- ===== KOLOM KIRI (Sidebar) ===== --}}
                    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">

                        {{-- Thumbnail --}}
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Gambar Produk</h2>
                                </div>
                            </div>
                            <div class="card-body text-center pt-0">
                                @if ($produk->gambar)
                                    <img src="{{ asset('backend/assets/media/produk/' . $produk->gambar) }}"
                                        alt="{{ $produk->nama_produk }}" class="img-fluid rounded"
                                        style="max-height: 220px; object-fit: cover;">
                                @else
                                    <div class="d-flex flex-center w-150px h-150px rounded bg-light mx-auto">
                                        <i class="ki-outline ki-picture fs-3x text-gray-400"></i>
                                    </div>
                                    <div class="text-muted fs-7 mt-2">Tidak ada gambar</div>
                                @endif
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Status</h2>
                                </div>
                                <div class="card-toolbar">
                                    <div
                                        class="rounded-circle w-15px h-15px
                                        {{ $produk->status_aktif ? 'bg-success' : 'bg-danger' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                @if ($produk->status_aktif)
                                    <span class="badge badge-light-success fs-7 fw-bold px-4 py-2">Aktif</span>
                                @else
                                    <span class="badge badge-light-danger fs-7 fw-bold px-4 py-2">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>

                        {{-- Info Dasar --}}
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Informasi Dasar</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <table class="table table-borderless fs-7 gy-2">
                                    <tr>
                                        <td class="text-muted fw-semibold w-50">Kode Produk</td>
                                        <td class="fw-bold text-gray-800">{{ $produk->kode_produk ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted fw-semibold">SKU</td>
                                        <td class="fw-bold text-gray-800">{{ $produk->sku ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted fw-semibold">Merk</td>
                                        <td class="fw-bold text-gray-800">{{ $produk->merk ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted fw-semibold">Kategori</td>
                                        <td class="fw-bold text-gray-800">{{ $produk->kategori->kategori ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted fw-semibold">Tipe</td>
                                        <td class="fw-bold text-gray-800">{{ $produk->category->category ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        {{-- Sertifikasi (SG-25) --}}
                        @if ($produk->eu1935 || $produk->fda || $produk->usp)
                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Sertifikasi</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0 d-flex flex-wrap gap-2">
                                    @if ($produk->eu1935)
                                        <span
                                            class="badge {{ strtolower($produk->eu1935) == 'ya' ? 'badge-light-success' : 'badge-light-secondary' }} fs-7 px-3 py-2">
                                            EU1935: {{ $produk->eu1935 }}
                                        </span>
                                    @endif
                                    @if ($produk->fda)
                                        <span
                                            class="badge {{ strtolower($produk->fda) == 'ya' ? 'badge-light-success' : 'badge-light-secondary' }} fs-7 px-3 py-2">
                                            FDA: {{ $produk->fda }}
                                        </span>
                                    @endif
                                    @if ($produk->usp)
                                        <span
                                            class="badge {{ strtolower($produk->usp) == 'ya' ? 'badge-light-success' : 'badge-light-secondary' }} fs-7 px-3 py-2">
                                            USP: {{ $produk->usp }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                    {{-- ===== END KOLOM KIRI ===== --}}

                    {{-- ===== KOLOM KANAN (Main) ===== --}}
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">

                        {{-- Nama & Deskripsi --}}
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>{{ $produk->nama_produk }}</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                @if ($produk->deskripsi)
                                    <div class="text-gray-700 fs-6">{!! nl2br(e($produk->deskripsi)) !!}</div>
                                @else
                                    <div class="text-muted fs-7">Tidak ada deskripsi.</div>
                                @endif
                            </div>
                        </div>

                        {{-- Varian / Stok & Harga --}}
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Stok & Harga</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                @if ($produk->variants && $produk->variants->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-muted bg-light">
                                                    <th class="ps-4 min-w-125px rounded-start">Jenis / Shape</th>
                                                    <th class="min-w-125px">Ukuran</th>
                                                    <th class="min-w-100px text-end">Stok</th>
                                                    <th class="min-w-150px text-end rounded-end pe-4">Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($produk->variants as $variant)
                                                    <tr>
                                                        <td class="ps-4">
                                                            <span class="text-gray-800 fw-bold fs-6">
                                                                {{ optional($variant->jenis)->jenis ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-700 fw-semibold fs-6">
                                                                {{ optional($variant->ukuran)->nama_ukuran ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end">
                                                            <span
                                                                class="badge {{ $variant->stok > 0 ? 'badge-light-success' : 'badge-light-danger' }} fs-7">
                                                                {{ number_format($variant->stok) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end pe-4">
                                                            <span class="text-gray-800 fw-bold fs-6">
                                                                Rp {{ number_format($variant->harga, 0, ',', '.') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-10">
                                        <i class="ki-outline ki-package fs-3x text-gray-300 mb-3"></i>
                                        <div class="text-muted fs-6">Belum ada data stok/varian.</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Dimensi / Shipping --}}
                        @php $firstVariant = $produk->variants->first(); @endphp
                        @if ($firstVariant && ($firstVariant->weight || $firstVariant->length || $firstVariant->width || $firstVariant->height))
                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Dimensi & Pengiriman</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row g-5">
                                        <div class="col-md-3 text-center">
                                            <div class="border rounded p-4">
                                                <div class="fs-2hx fw-bold text-gray-800">
                                                    {{ $firstVariant->weight ?? '-' }}</div>
                                                <div class="text-muted fs-7 mt-1">Berat (kg)</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="border rounded p-4">
                                                <div class="fs-2hx fw-bold text-gray-800">
                                                    {{ $firstVariant->length ?? '-' }}</div>
                                                <div class="text-muted fs-7 mt-1">Panjang (cm)</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="border rounded p-4">
                                                <div class="fs-2hx fw-bold text-gray-800">
                                                    {{ $firstVariant->width ?? '-' }}</div>
                                                <div class="text-muted fs-7 mt-1">Lebar (cm)</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="border rounded p-4">
                                                <div class="fs-2hx fw-bold text-gray-800">
                                                    {{ $firstVariant->height ?? '-' }}</div>
                                                <div class="text-muted fs-7 mt-1">Tinggi (cm)</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Spesifikasi Mekanis --}}
                        @if ($produk->tensile || $produk->elongation || $produk->spesific || $produk->friction)
                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Spesifikasi Mekanis</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row g-5">
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-2">
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Tensile Strength</span>
                                                    <span class="fw-bold text-gray-800 fs-7">{{ $produk->tensile ?? '-' }}
                                                        MPa</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Elongation</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->elongation ?? '-' }}
                                                        %</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-2">
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Specific Gravity</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->spesific ?? '-' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Coeff. of Friction</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->friction ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Spesifikasi Rulon --}}
                        @if ($produk->pressure || $produk->mating || $produk->max_pv || $produk->max_v)
                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Spesifikasi Rulon</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row g-5">
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-2">
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Environment</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ optional($produk->envi)->nama ?? '-' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Pressure</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->pressure ?? '-' }}
                                                        psi</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Mating Surface</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->mating ?? '-' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Deformation</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->deformation ?? '-' }}
                                                        %</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-2">
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Max PV</span>
                                                    <span class="fw-bold text-gray-800 fs-7">{{ $produk->max_pv ?? '-' }}
                                                        psi-fpm</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Maximum P</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->maximum_p ?? '-' }}
                                                        psi</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Max V</span>
                                                    <span class="fw-bold text-gray-800 fs-7">{{ $produk->max_v ?? '-' }}
                                                        fpm</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Spesifikasi Tygon --}}
                        @if ($produk->inner_diameter || $produk->outer_diameter || $produk->wall_thickness)
                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Spesifikasi Tygon</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row g-5">
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-2">
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Inner Diameter</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->inner_diameter ?? '-' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Outer Diameter</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->outer_diameter ?? '-' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Wall Thickness</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->wall_thickness ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-2">
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Min. Bend Radius</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->min_bend_radius ?? '-' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Length</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->tygon_length ?? '-' }}
                                                        ft</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Working Pressure 73°F</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->tygon_working_pressure_73 ?? '-' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="text-muted fw-semibold fs-7">Working Pressure 320°F</span>
                                                    <span
                                                        class="fw-bold text-gray-800 fs-7">{{ $produk->tygon_working_pressure_320 ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Tombol Aksi Bawah --}}
                        <div class="d-flex justify-content-end gap-3 mb-7">
                            <a href="{{ route('admin.produk.index') }}" class="btn btn-light">
                                <i class="ki-outline ki-arrow-left fs-4 me-1"></i> Kembali
                            </a>
                            <a href="{{ route('admin.produk.edit', $produk->id) }}" class="btn btn-primary">
                                <i class="ki-outline ki-pencil fs-4 me-1"></i> Edit Produk
                            </a>
                        </div>

                    </div>
                    {{-- ===== END KOLOM KANAN ===== --}}

                </div>

            </div>
        </div>

    </div>
@endsection
