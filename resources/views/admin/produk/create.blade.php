@extends('admin.dashboard')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0 fw-bold">Tambah Produk</h4>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Perhatian!</strong> Ada kesalahan input.
                        <ul class="mt-2 mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.produk.store') }}" method="POST" id="product-form"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Hidden fields untuk preserve data saat reload shape (Rulon) --}}
                    <input type="hidden" name="id_cat_hidden" value="{{ request('id_cat') }}">
                    <input type="hidden" name="nama_produk_hidden" value="{{ request('nama_produk') }}">
                    <input type="hidden" name="deskripsi_hidden" value="{{ request('deskripsi') }}">
                    <input type="hidden" name="id_kat_hidden" value="{{ request('id_kat') }}">
                    <input type="hidden" name="merk_hidden" value="{{ request('merk') }}">
                    <input type="hidden" name="tempratur_hidden" value="{{ request('tempratur') }}">
                    <input type="hidden" name="eu1935_hidden" value="{{ request('eu1935') }}">
                    <input type="hidden" name="fda_hidden" value="{{ request('fda') }}">
                    <input type="hidden" name="usp_hidden" value="{{ request('usp') }}">
                    <input type="hidden" name="status_aktif_hidden" value="{{ request('status_aktif') }}">

                    <div class="row">

                        {{-- ============================= KOLOM KIRI ============================= --}}
                        <div class="col-md-6">

                            {{-- Informasi Dasar Produk --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-center fw-bold" style="font-size:17px; margin-top:43px;">
                                    Informasi Dasar Produk
                                </div>
                                <div class="card-body">

                                    {{-- ======================================================= --}}
                                    {{-- PILIH TIPE PRODUK — penentu utama tampilan form         --}}
                                    {{-- ======================================================= --}}
                                    <div class="mb-4 p-3 border border-primary rounded bg-light">
                                        <label class="form-label fw-bold">
                                            <span class="text-danger">*</span> Tipe Produk
                                        </label>
                                        <select name="id_cat" id="categorySelect"
                                            class="form-select form-select-lg border-primary" required>
                                            <option value="">-- Pilih Tipe Produk Terlebih Dahulu --</option>
                                            @foreach ($produkCategory as $cat)
                                                <option value="{{ $cat->id }}"
                                                    data-type="{{ strtolower(str_replace([' ', '3350'], ['_', ''], $cat->category)) }}"
                                                    {{ request('id_cat', old('id_cat')) == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->category }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-text text-primary">
                                            Pilih tipe produk untuk menampilkan inputan yang sesuai.
                                        </div>
                                    </div>

                                    {{-- Kode, SKU, Nama --}}
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Kode Produk</label>
                                            <input type="text" class="form-control" value="{{ $kode }}"
                                                disabled>
                                            <input type="hidden" name="kode_produk" value="{{ $kode }}">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">SKU Produk <span class="text-danger">*</span></label>
                                            <input type="text" name="sku" class="form-control"
                                                value="{{ request('sku', old('sku')) }}" required>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_produk" class="form-control"
                                                value="{{ request('nama_produk', old('nama_produk')) }}" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Weight (kg)</label>
                                            <input type="number" name="weight" class="form-control"
                                                value="{{ old('weight') }}" step="any">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Length (cm)</label>
                                            <input type="number" name="length" class="form-control"
                                                value="{{ old('length') }}" step="any">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Width (cm)</label>
                                            <input type="number" name="width" class="form-control"
                                                value="{{ old('width') }}" step="any">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Height (cm)</label>
                                            <input type="number" name="height" class="form-control"
                                                value="{{ old('height') }}" step="any">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="3">{{ request('deskripsi', old('deskripsi')) }}</textarea>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Kategori Produk</label>
                                            <select name="id_kat" class="form-select">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach ($kategori as $kat)
                                                    <option value="{{ $kat->id }}"
                                                        {{ request('id_kat', old('id_kat')) == $kat->id ? 'selected' : '' }}>
                                                        {{ $kat->kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Merk</label>
                                            <input type="text" name="merk" class="form-control"
                                                value="{{ request('merk', old('merk')) }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Temperature Range</label>
                                        <input type="text" name="tempratur" class="form-control"
                                            value="{{ request('tempratur', old('tempratur')) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- ============================================================ --}}
                            {{-- [RULON] Varian Shape, Ukuran, Stok & Harga                 --}}
                            {{-- ============================================================ --}}
                            <div id="section-rulon-varian" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold" style="font-size:17px; margin-top:43px;">
                                    Varian Stok & Harga (Rulon)
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Pilih Shape <span
                                                class="text-danger">*</span></label>
                                        <select name="shape_id" id="shapeSelect" class="form-select">
                                            <option value="">-- Pilih Shape --</option>
                                            @foreach ($shapes as $shape)
                                                <option value="{{ $shape->id }}"
                                                    {{ request('shape_id') == $shape->id ? 'selected' : '' }}>
                                                    {{ $shape->jenis }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="varian-details">
                                        @if ($ukurans->count() > 0)
                                            <hr>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Pilih Ukuran <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" name="id_ukuran[]">
                                                    <option value="">-- Pilih Ukuran --</option>
                                                    @foreach ($ukurans as $ukuran)
                                                        <option value="{{ $ukuran->id }}"
                                                            {{ request('id_ukuran') == $ukuran->id ? 'selected' : '' }}>
                                                            {{ $ukuran->nama_ukuran }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Stok</label>
                                                    <input type="number" name="stok_variant[]" class="form-control"
                                                        placeholder="Stok" min="0">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Harga</label>
                                                    <input type="number" name="harga_variant[]" class="form-control"
                                                        placeholder="Harga" min="0">
                                                </div>
                                            </div>
                                        @elseif(request('shape_id'))
                                            <p class="text-muted text-center">Tidak ada ukuran untuk shape ini.</p>
                                        @else
                                            <p class="text-muted text-center small">Pilih shape untuk menampilkan ukuran.
                                            </p>
                                        @endif
                                    </div>
                                    @if (!request('shape_id'))
                                        <div style="display:none;">
                                            <select name="id_ukuran[]">
                                                <option value=""></option>
                                            </select>
                                            <input type="number" name="stok_variant[]" value="">
                                            <input type="number" name="harga_variant[]" value="">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- ============================================================ --}}
                            {{-- [TYGON 3350] Dimensi Tube + Stok & Harga                   --}}
                            {{-- ============================================================ --}}
                            <div id="section-tygon-varian" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold" style="font-size:17px; margin-top:43px;">
                                    Dimensi Tube & Harga (Tygon 3350)
                                </div>
                                <div class="card-body">

                                    {{-- Pilih Kategori Ukuran --}}
                                    <div class="mb-3 p-3 border border-info rounded bg-light">
                                        <label class="form-label fw-bold">
                                            <span class="text-danger">*</span> Kategori Ukuran
                                        </label>
                                        <select name="tygon_size_category" id="tygonSizeCategory"
                                            class="form-select border-info" required>
                                            <option value="">-- Pilih Kategori Ukuran --</option>
                                            <option value="tubing_inventory"
                                                {{ old('tygon_size_category', request('tygon_size_category')) == 'tubing_inventory' ? 'selected' : '' }}>
                                                Tubing Inventory Size (inches)
                                            </option>
                                            <option value="metric"
                                                {{ old('tygon_size_category', request('tygon_size_category')) == 'metric' ? 'selected' : '' }}>
                                                Metric Sizes (mm)
                                            </option>
                                        </select>
                                        <div class="form-text text-info">
                                            Pilih kategori ukuran untuk menentukan satuan dimensi tube.
                                        </div>
                                    </div>

                                    {{-- Label satuan dinamis berdasarkan kategori ukuran --}}
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">
                                                Inner Diameter
                                                <span class="tygon-unit badge bg-secondary ms-1"
                                                    id="label-id-unit">-</span>
                                            </label>
                                            <input type="number" step="0.001" name="inner_diameter"
                                                class="form-control"
                                                value="{{ old('inner_diameter', request('inner_diameter')) }}"
                                                placeholder="e.g. 4.762" id="input-inner-diameter">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">
                                                Outer Diameter
                                                <span class="tygon-unit badge bg-secondary ms-1"
                                                    id="label-od-unit">-</span>
                                            </label>
                                            <input type="number" step="0.001" name="outer_diameter"
                                                class="form-control"
                                                value="{{ old('outer_diameter', request('outer_diameter')) }}"
                                                placeholder="e.g. 6.350" id="input-outer-diameter">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">
                                                Wall Thickness
                                                <span class="tygon-unit badge bg-secondary ms-1"
                                                    id="label-wall-unit">-</span>
                                            </label>
                                            <input type="number" step="0.001" name="wall_thickness"
                                                class="form-control"
                                                value="{{ old('wall_thickness', request('wall_thickness')) }}"
                                                placeholder="e.g. 0.794" id="input-wall-thickness">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Length per Roll
                                                <span class="tygon-unit badge bg-secondary ms-1"
                                                    id="label-len-unit">-</span>
                                            </label>
                                            <input type="number" step="0.01" name="tygon_length"
                                                class="form-control"
                                                value="{{ old('tygon_length', request('tygon_length')) }}"
                                                placeholder="e.g. 50" id="input-tygon-length">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Min Bend Radius
                                                <span class="tygon-unit badge bg-secondary ms-1"
                                                    id="label-bend-unit">-</span>
                                            </label>
                                            <input type="number" step="0.001" name="min_bend_radius"
                                                class="form-control"
                                                value="{{ old('min_bend_radius', request('min_bend_radius')) }}"
                                                placeholder="e.g. 0.125" id="input-min-bend">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Stok</label>
                                            <input type="number" name="tygon_stok" class="form-control"
                                                value="{{ old('tygon_stok') }}" placeholder="0" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Harga</label>
                                            <input type="number" name="tygon_harga" class="form-control"
                                                value="{{ old('tygon_harga') }}" placeholder="0" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ============================================================ --}}
                            {{-- [TOP TAPE] Dimensi Tape + Stok & Harga                     --}}
                            {{-- ============================================================ --}}
                            <div id="section-toptape-varian" class="cat-section card shadow-sm mb-4"
                                style="display:none;">
                                <div class="card-header text-center fw-bold" style="font-size:17px; margin-top:43px;">
                                    Dimensi Tape & Harga (Top Tape)
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Lebar (mm)</label>
                                            <input type="number" step="0.01" name="tape_width" class="form-control"
                                                value="{{ old('tape_width') }}" placeholder="e.g. 25">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tebal (mm)</label>
                                            <input type="number" step="0.001" name="tape_thickness"
                                                class="form-control" value="{{ old('tape_thickness') }}"
                                                placeholder="e.g. 0.1">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Panjang Roll (m)</label>
                                            <input type="number" step="0.01" name="tape_length" class="form-control"
                                                value="{{ old('tape_length') }}" placeholder="e.g. 30">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Warna</label>
                                            <input type="text" name="tape_color" class="form-control"
                                                value="{{ old('tape_color') }}" placeholder="e.g. White">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Adhesive Type</label>
                                            <input type="text" name="tape_adhesive" class="form-control"
                                                value="{{ old('tape_adhesive') }}" placeholder="e.g. Silicone">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Stok</label>
                                            <input type="number" name="tape_stok" class="form-control"
                                                value="{{ old('tape_stok') }}" placeholder="0" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Harga</label>
                                            <input type="number" name="tape_harga" class="form-control"
                                                value="{{ old('tape_harga') }}" placeholder="0" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Sertifikasi (hanya Rulon) --}}
                            <div id="section-sertifikasi" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold" style="font-size:17px; margin-top:43px;">
                                    Sertifikasi
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">EU1935</label>
                                            <select name="eu1935" class="form-select">
                                                <option value="ya"
                                                    {{ request('eu1935', 'ya') == 'ya' ? 'selected' : '' }}>Ya</option>
                                                <option value="tidak"
                                                    {{ request('eu1935') == 'tidak' ? 'selected' : '' }}>Tidak
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">FDA</label>
                                            <select name="fda" class="form-select">
                                                <option value="ya"
                                                    {{ request('fda', 'ya') == 'ya' ? 'selected' : '' }}>Ya</option>
                                                <option value="tidak" {{ request('fda') == 'tidak' ? 'selected' : '' }}>
                                                    Tidak</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">USP Class VI</label>
                                            <select name="usp" class="form-select">
                                                <option value="ya"
                                                    {{ request('usp', 'ya') == 'ya' ? 'selected' : '' }}>Ya</option>
                                                <option value="tidak" {{ request('usp') == 'tidak' ? 'selected' : '' }}>
                                                    Tidak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- end kolom kiri --}}

                        {{-- ============================= KOLOM KANAN ============================= --}}
                        <div class="col-md-6">

                            {{-- ============================================================ --}}
                            {{-- [RULON] Spesifikasi Teknis                                  --}}
                            {{-- ============================================================ --}}
                            <div id="section-rulon-spek" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold" style="font-size:17px; margin-top:43px;">
                                    Spesifikasi Teknis (Rulon)
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Environment</label>
                                            <select name="id_environmant" class="form-select">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($environment as $env)
                                                    <option value="{{ $env->id }}"
                                                        {{ request('id_environmant') == $env->id ? 'selected' : '' }}>
                                                        {{ $env->envi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Pressure</label>
                                            <input type="text" name="pressure" class="form-control"
                                                value="{{ request('pressure') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Shaft Hardness</label>
                                            <input type="text" name="mating" class="form-control"
                                                value="{{ request('mating') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Max PV (MPa×m/s)</label>
                                            <input type="number" step="0.01" name="max_pv" class="form-control"
                                                value="{{ request('max_pv') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Maximum P (MPa)</label>
                                            <input type="text" name="maximum_p" class="form-control"
                                                value="{{ request('maximum_p') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Max V (m/s)</label>
                                            <input type="number" step="0.01" name="max_v" class="form-control"
                                                value="{{ request('max_v') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Friction (static & dynamic)</label>
                                            <input type="text" name="friction" class="form-control"
                                                value="{{ request('friction') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Elongation ASTM D638</label>
                                            <input type="text" name="elongation" class="form-control"
                                                value="{{ request('elongation') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Deformation Under Load</label>
                                            <input type="number" step="0.01" name="deformation" class="form-control"
                                                value="{{ request('deformation') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tensile Strength (MPa)</label>
                                            <input type="number" step="0.01" name="tensile" class="form-control"
                                                value="{{ request('tensile') }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Specific Gravity</label>
                                        <input type="number" step="0.01" name="spesific" class="form-control"
                                            value="{{ request('spesific') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- ============================================================ --}}
                            {{-- [TYGON 3350] Spesifikasi Teknis                             --}}
                            {{-- ============================================================ --}}
                            <div id="section-tygon-spek" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold" style="font-size:17px; margin-top:43px;">
                                    Spesifikasi Teknis (Tygon 3350)
                                </div>
                                <div class="card-body">

                                    {{-- Max. Suggested Working Pressure — 2 suhu --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Max. Suggested Working Pressure (psi)</label>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">
                                                    <i class="bi bi-thermometer-half text-primary"></i> 73°F
                                                </label>
                                                <input type="text" name="tygon_working_pressure_73"
                                                    class="form-control"
                                                    value="{{ old('tygon_working_pressure_73', request('tygon_working_pressure_73')) }}"
                                                    placeholder="e.g. 22">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">
                                                    <i class="bi bi-thermometer-high text-danger"></i> 320°F
                                                </label>
                                                <input type="text" name="tygon_working_pressure_320"
                                                    class="form-control"
                                                    value="{{ old('tygon_working_pressure_320', request('tygon_working_pressure_320')) }}"
                                                    placeholder="e.g. 21">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Vacuum Rating — 2 suhu --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Vacuum Rating (In. of Mercury)</label>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">
                                                    <i class="bi bi-thermometer-half text-primary"></i> 73°F
                                                </label>
                                                <input type="text" name="tygon_vacuum_73" class="form-control"
                                                    value="{{ old('tygon_vacuum_73', request('tygon_vacuum_73')) }}"
                                                    placeholder="e.g. 29.9">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">
                                                    <i class="bi bi-thermometer-high text-danger"></i> 320°F
                                                </label>
                                                <input type="text" name="tygon_vacuum_320" class="form-control"
                                                    value="{{ old('tygon_vacuum_320', request('tygon_vacuum_320')) }}"
                                                    placeholder="e.g. 29.9">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Tensile Strength (MPa)</label>
                                            <input type="number" step="0.01" name="tensile" class="form-control"
                                                value="{{ old('tensile') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Elongation (%)</label>
                                            <input type="text" name="elongation" class="form-control"
                                                value="{{ old('elongation') }}" placeholder="e.g. 350">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Specific Gravity</label>
                                            <input type="number" step="0.01" name="spesific" class="form-control"
                                                value="{{ old('spesific') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Environment</label>
                                            <select name="id_environmant" class="form-select">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($environment as $env)
                                                    <option value="{{ $env->id }}"
                                                        {{ old('id_environmant') == $env->id ? 'selected' : '' }}>
                                                        {{ $env->envi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ============================================================ --}}
                            {{-- [TOP TAPE] Spesifikasi Teknis                               --}}
                            {{-- ============================================================ --}}
                            <div id="section-toptape-spek" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold" style="font-size:17px; margin-top:43px;">
                                    Spesifikasi Teknis (Top Tape)
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Tensile Strength (N/cm)</label>
                                            <input type="number" step="0.01" name="tensile" class="form-control"
                                                value="{{ old('tensile') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Elongation (%)</label>
                                            <input type="text" name="elongation" class="form-control"
                                                value="{{ old('elongation') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Specific Gravity</label>
                                            <input type="number" step="0.01" name="spesific" class="form-control"
                                                value="{{ old('spesific') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Friction Coefficient</label>
                                            <input type="text" name="friction" class="form-control"
                                                value="{{ old('friction') }}" placeholder="e.g. 0.05 - 0.10">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Max Temperature (°C)</label>
                                            <input type="text" name="tape_max_temp" class="form-control"
                                                value="{{ old('tape_max_temp') }}" placeholder="e.g. 260">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Dielectric Strength (V/mil)</label>
                                            <input type="text" name="tape_dielectric" class="form-control"
                                                value="{{ old('tape_dielectric') }}" placeholder="e.g. 3000">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Media & Status (semua kategori) --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-center fw-bold" style="font-size:17px; margin-top:43px;">
                                    Media & Status
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Gambar Produk</label>
                                        <input type="file" name="gambar" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status Aktif</label>
                                        <select name="status_aktif" class="form-select">
                                            <option value="1"
                                                {{ request('status_aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ request('status_aktif') == '0' ? 'selected' : '' }}>
                                                Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- end kolom kanan --}}
                    </div>{{-- end row --}}

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary" id="btn-simpan" disabled>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ==========================================================
            // Mapping: keyword yang ada di data-type  ->  section IDs
            // data-type dibuat dari nama category di-lowercase, spasi → _
            // Contoh: "Tygon 3350" → "tygon_" → match key 'tygon'
            // ==========================================================
            const sectionMap = {
                'rulon': ['section-rulon-varian', 'section-rulon-spek', 'section-sertifikasi'],
                'tygon': ['section-tygon-varian', 'section-tygon-spek'],
                'top_tape': ['section-toptape-varian', 'section-toptape-spek'],
                'tape': ['section-toptape-varian', 'section-toptape-spek'],
            };

            const allSections = [
                'section-rulon-varian', 'section-rulon-spek', 'section-sertifikasi',
                'section-tygon-varian', 'section-tygon-spek',
                'section-toptape-varian', 'section-toptape-spek',
            ];

            const categorySelect = document.getElementById('categorySelect');
            const btnSimpan = document.getElementById('btn-simpan');
            const productForm = document.getElementById('product-form');
            const shapeSelect = document.getElementById('shapeSelect');
            const tygonSizeCat = document.getElementById('tygonSizeCategory');

            // ----------------------------------------------------------
            // Satuan label dinamis untuk Tygon berdasar kategori ukuran
            // ----------------------------------------------------------
            const unitMap = {
                tubing_inventory: {
                    diameter: 'inches',
                    length: 'feet',
                    bend: 'inches',
                },
                metric: {
                    diameter: 'mm',
                    length: 'm',
                    bend: 'mm',
                },
            };

            const placeholderMap = {
                tubing_inventory: {
                    id: 'e.g. 0.1875',
                    od: 'e.g. 0.3750',
                    wall: 'e.g. 0.0625',
                    len: 'e.g. 50',
                    bend: 'e.g. 0.125',
                },
                metric: {
                    id: 'e.g. 4.762',
                    od: 'e.g. 6.350',
                    wall: 'e.g. 0.794',
                    len: 'e.g. 15.24',
                    bend: 'e.g. 25.4',
                },
            };

            function applyTygonUnits(category) {
                const units = unitMap[category] || null;
                const ph = placeholderMap[category] || null;

                const ids = {
                    'label-id-unit': units ? units.diameter : '-',
                    'label-od-unit': units ? units.diameter : '-',
                    'label-wall-unit': units ? units.diameter : '-',
                    'label-len-unit': units ? units.length : '-',
                    'label-bend-unit': units ? units.bend : '-',
                };

                for (const [id, text] of Object.entries(ids)) {
                    const el = document.getElementById(id);
                    if (el) el.textContent = text;
                }

                if (ph) {
                    const map = {
                        'input-inner-diameter': ph.id,
                        'input-outer-diameter': ph.od,
                        'input-wall-thickness': ph.wall,
                        'input-tygon-length': ph.len,
                        'input-min-bend': ph.bend,
                    };
                    for (const [id, placeholder] of Object.entries(map)) {
                        const el = document.getElementById(id);
                        if (el) el.placeholder = placeholder;
                    }
                }
            }

            // Event: kategori ukuran Tygon berubah
            if (tygonSizeCat) {
                tygonSizeCat.addEventListener('change', function() {
                    applyTygonUnits(this.value);
                });

                // Auto-apply saat load jika sudah ada nilai
                if (tygonSizeCat.value) {
                    applyTygonUnits(tygonSizeCat.value);
                }
            }

            // ----------------------------------------------------------
            // Show/hide section berdasar tipe produk
            // ----------------------------------------------------------
            function hideAll() {
                allSections.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.style.display = 'none';
                });
            }

            function applyCategory(dataType) {
                hideAll();
                if (!dataType) {
                    btnSimpan.disabled = true;
                    return;
                }

                let matched = null;
                for (const key in sectionMap) {
                    if (dataType.includes(key)) {
                        matched = key;
                        break;
                    }
                }

                if (matched) {
                    sectionMap[matched].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.style.display = 'block';
                    });
                    btnSimpan.disabled = false;
                } else {
                    btnSimpan.disabled = true;
                }
            }

            // Saat user ganti kategori
            categorySelect.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                const dataType = opt.getAttribute('data-type') || '';
                applyCategory(dataType);
            });

            // Auto-apply saat halaman load (misal setelah reload karena pilih shape)
            if (categorySelect.value) {
                const opt = categorySelect.options[categorySelect.selectedIndex];
                const dataType = opt.getAttribute('data-type') || '';
                applyCategory(dataType);
            }

            // Shape reload (hanya Rulon) — simpan id_cat sebelum submit GET
            if (shapeSelect) {
                shapeSelect.addEventListener('change', function() {
                    const idCat = categorySelect.value;
                    const url = new URL("{{ route('admin.produk.create') }}");
                    url.searchParams.set('id_cat', idCat);
                    url.searchParams.set('shape_id', this.value);
                    url.searchParams.set('sku', document.querySelector('[name="sku"]').value);
                    url.searchParams.set('nama_produk', document.querySelector('[name="nama_produk"]')
                        .value);
                    url.searchParams.set('merk', document.querySelector('[name="merk"]').value);
                    url.searchParams.set('tempratur', document.querySelector('[name="tempratur"]').value);
                    window.location.href = url.toString();
                });
            }
        });
    </script>
@endpush
