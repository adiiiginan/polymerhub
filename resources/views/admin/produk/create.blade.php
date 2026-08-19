@extends('admin.dashboard')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white text-center py-3">
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

                    <div class="row">

                        {{-- ============================= KOLOM KIRI ============================= --}}
                        <div class="col-md-6">

                            {{-- Informasi Dasar Produk --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-center fw-bold py-3">
                                    Informasi Dasar Produk
                                </div>
                                <div class="card-body">

                                    {{-- Pilih Tipe Produk --}}
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
                                                    {{ old('id_cat') == $cat->id ? 'selected' : '' }}>
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
                                            <input type="text" class="form-control" value="{{ $kode }}" disabled>
                                            <input type="hidden" name="kode_produk" value="{{ $kode }}">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">SKU Produk <span class="text-danger">*</span></label>
                                            <input type="text" name="sku" class="form-control"
                                                value="{{ old('sku') }}" required>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_produk" class="form-control"
                                                value="{{ old('nama_produk') }}" required>
                                        </div>
                                    </div>

                                    {{-- Dimensi --}}
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

                                    {{-- Deskripsi --}}
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                                    </div>

                                    {{-- Kategori & Merk --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Kategori Produk</label>
                                            <select name="id_kat" class="form-select">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach ($kategori as $kat)
                                                    <option value="{{ $kat->id }}"
                                                        {{ old('id_kat') == $kat->id ? 'selected' : '' }}>
                                                        {{ $kat->kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Merk</label>
                                            <input type="text" name="merk" class="form-control"
                                                value="{{ old('merk') }}">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- ============================================================ --}}
                            {{-- [RULON] Varian Shape, Ukuran, Stok & Harga                 --}}
                            {{-- ============================================================ --}}
                            <div id="section-rulon-varian" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold py-3">
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
                                </div>
                            </div>

                            {{-- ============================================================ --}}
                            {{-- [SG-25] Varian Ukuran, Stok & Harga                        --}}
                            {{-- ============================================================ --}}
                            <div id="section-sg-25-varian" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold py-3">
                                    Varian Stok & Harga (SG-25)
                                </div>
                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label fw-bold mb-0">
                                            Pilih Ukuran & Isi Stok/Harga <span class="text-danger">*</span>
                                        </label>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            id="btn-add-sg25-varian">
                                            <i class="bi bi-plus-circle me-1"></i> Tambah Ukuran
                                        </button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle" id="sg25-varian-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Ukuran <span class="text-danger">*</span></th>
                                                    <th>Stok <span class="text-danger">*</span></th>
                                                    <th style="min-width:140px;">Harga (Rp) <span
                                                            class="text-danger">*</span></th>
                                                    <th style="width:50px;" class="text-center">Hapus</th>
                                                </tr>
                                            </thead>
                                            <tbody id="sg25-varian-body">

                                                {{-- Baris pertama — tidak bisa dihapus --}}
                                                <tr class="sg25-varian-row">
                                                    <td>
                                                        <select name="sg25_ukuran_id[]" class="form-select">
                                                            <option value="">-- Pilih Ukuran --</option>
                                                            @foreach ($ukuranSg25 as $ukuran)
                                                                <option value="{{ $ukuran->id }}"
                                                                    {{ old('sg25_ukuran_id.0') == $ukuran->id ? 'selected' : '' }}>
                                                                    {{ $ukuran->nama_ukuran }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="sg25_stok[]" class="form-control"
                                                            placeholder="0" min="0"
                                                            value="{{ old('sg25_stok.0') }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="sg25_harga[]" class="form-control"
                                                            placeholder="0" min="0"
                                                            value="{{ old('sg25_harga.0') }}">
                                                    </td>
                                                    <td class="text-center text-muted">
                                                        <span title="Baris pertama tidak bisa dihapus">—</span>
                                                    </td>
                                                </tr>

                                                {{-- Restore baris tambahan jika ada validasi error --}}
                                                @if (old('sg25_ukuran_id'))
                                                    @foreach (old('sg25_ukuran_id') as $i => $uid)
                                                        @if ($i > 0)
                                                            <tr class="sg25-varian-row">
                                                                <td>
                                                                    <select name="sg25_ukuran_id[]" class="form-select">
                                                                        <option value="">-- Pilih Ukuran --</option>
                                                                        @foreach ($ukuranSg25 as $ukuran)
                                                                            <option value="{{ $ukuran->id }}"
                                                                                {{ $uid == $ukuran->id ? 'selected' : '' }}>
                                                                                {{ $ukuran->nama_ukuran }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="sg25_stok[]"
                                                                        class="form-control" placeholder="0"
                                                                        min="0"
                                                                        value="{{ old('sg25_stok.' . $i) }}">
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="sg25_harga[]"
                                                                        class="form-control" placeholder="0"
                                                                        min="0"
                                                                        value="{{ old('sg25_harga.' . $i) }}">
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button"
                                                                        class="btn btn-outline-danger btn-sm btn-remove-sg25"
                                                                        title="Hapus baris ini">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="text-muted small mt-1">
                                        <i class="bi bi-info-circle"></i>
                                        Tambahkan satu baris per ukuran. Pilih ukuran yang tersedia dari daftar.
                                    </div>

                                </div>
                            </div>

                            {{-- ============================================================ --}}
                            {{-- [TYGON 3350] Dimensi Tube + Stok & Harga                   --}}
                            {{-- ============================================================ --}}
                            <div id="section-tygon-varian" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold py-3">
                                    Dimensi Tube & Harga (Tygon 3350)
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 p-3 border border-info rounded bg-light">
                                        <label class="form-label fw-bold">
                                            <span class="text-danger">*</span> Kategori Ukuran
                                        </label>
                                        <select name="tygon_size_category" id="tygonSizeCategory"
                                            class="form-select border-info">
                                            <option value="">-- Pilih Kategori Ukuran --</option>
                                            @foreach ($jenis as $item)
                                                <option value="{{ $item->id }}"
                                                    data-jenis="{{ strtolower($item->jenis) }}"
                                                    {{ old('tygon_size_category') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->jenis }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Inner Diameter
                                                <span class="tygon-unit badge bg-secondary ms-1"
                                                    id="label-id-unit">-</span>
                                            </label>
                                            <input type="text" name="inner_diameter" class="form-control"
                                                value="{{ old('inner_diameter') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Outer Diameter
                                                <span class="tygon-unit badge bg-secondary ms-1"
                                                    id="label-od-unit">-</span>
                                            </label>
                                            <input type="text" name="outer_diameter" class="form-control"
                                                value="{{ old('outer_diameter') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Wall Thickness
                                                <span class="tygon-unit badge bg-secondary ms-1"
                                                    id="label-wall-unit">-</span>
                                            </label>
                                            <input type="text" name="wall_thickness" class="form-control"
                                                value="{{ old('wall_thickness') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Length per Roll
                                                <span class="tygon-unit badge bg-secondary ms-1"
                                                    id="label-len-unit">-</span>
                                            </label>
                                            <input type="number" step="0.01" name="tygon_length"
                                                class="form-control" value="{{ old('tygon_length') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Min Bend Radius
                                                <span class="tygon-unit badge bg-secondary ms-1"
                                                    id="label-bend-unit">-</span>
                                            </label>
                                            <input type="text" name="min_bend_radius" class="form-control"
                                                value="{{ old('min_bend_radius') }}">
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

                            {{-- Sertifikasi (hanya Rulon) --}}
                            <div id="section-sertifikasi" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold py-3">
                                    Sertifikasi
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">EU1935</label>
                                            <select name="eu1935" class="form-select">
                                                <option value="ya"
                                                    {{ old('eu1935', 'ya') == 'ya' ? 'selected' : '' }}>Ya</option>
                                                <option value="tidak" {{ old('eu1935') == 'tidak' ? 'selected' : '' }}>
                                                    Tidak</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">FDA</label>
                                            <select name="fda" class="form-select">
                                                <option value="ya" {{ old('fda', 'ya') == 'ya' ? 'selected' : '' }}>Ya
                                                </option>
                                                <option value="tidak" {{ old('fda') == 'tidak' ? 'selected' : '' }}>Tidak
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">USP Class VI</label>
                                            <select name="usp" class="form-select">
                                                <option value="ya" {{ old('usp', 'ya') == 'ya' ? 'selected' : '' }}>Ya
                                                </option>
                                                <option value="tidak" {{ old('usp') == 'tidak' ? 'selected' : '' }}>Tidak
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- end kolom kiri --}}

                        {{-- ============================= KOLOM KANAN ============================= --}}
                        <div class="col-md-6">

                            {{-- [RULON] Spesifikasi Teknis --}}
                            <div id="section-rulon-spek" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold py-3">
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
                                                        {{ old('id_environmant') == $env->id ? 'selected' : '' }}>
                                                        {{ $env->envi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Pressure</label>
                                            <input type="text" name="pressure" class="form-control"
                                                value="{{ old('pressure') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Shaft Hardness</label>
                                            <input type="text" name="mating" class="form-control"
                                                value="{{ old('mating') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Max PV (MPa×m/s)</label>
                                            <input type="number" step="0.01" name="max_pv" class="form-control"
                                                value="{{ old('max_pv') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Maximum P (MPa)</label>
                                            <input type="text" name="maximum_p" class="form-control"
                                                value="{{ old('maximum_p') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Max V (m/s)</label>
                                            <input type="number" step="0.01" name="max_v" class="form-control"
                                                value="{{ old('max_v') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Friction (static & dynamic)</label>
                                            <input type="text" name="friction" class="form-control"
                                                value="{{ old('friction') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Elongation ASTM D638</label>
                                            <input type="text" name="elongation" class="form-control"
                                                value="{{ old('elongation') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Deformation Under Load</label>
                                            <input type="number" step="0.01" name="deformation" class="form-control"
                                                value="{{ old('deformation') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tensile Strength (MPa)</label>
                                            <input type="number" step="0.01" name="tensile" class="form-control"
                                                value="{{ old('tensile') }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Specific Gravity</label>
                                        <input type="number" step="0.01" name="spesific" class="form-control"
                                            value="{{ old('spesific') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- [TYGON 3350] Spesifikasi Teknis --}}
                            <div id="section-tygon-spek" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold py-3">
                                    Spesifikasi Teknis (Tygon 3350)
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Max. Suggested Working Pressure (psi)</label>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">73°F</label>
                                                <input type="text" name="tygon_working_pressure_73"
                                                    class="form-control" value="{{ old('tygon_working_pressure_73') }}"
                                                    placeholder="e.g. 22">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">320°F</label>
                                                <input type="text" name="tygon_working_pressure_320"
                                                    class="form-control" value="{{ old('tygon_working_pressure_320') }}"
                                                    placeholder="e.g. 21">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Vacuum Rating (In. of Mercury)</label>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">73°F</label>
                                                <input type="text" name="tygon_vacuum_73" class="form-control"
                                                    value="{{ old('tygon_vacuum_73') }}" placeholder="e.g. 29.9">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">320°F</label>
                                                <input type="text" name="tygon_vacuum_320" class="form-control"
                                                    value="{{ old('tygon_vacuum_320') }}" placeholder="e.g. 29.9">
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                            {{-- Media & Status — selalu tampil --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-center fw-bold py-3">
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
                                                {{ old('status_aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>
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

            // ── Peta section per tipe produk ─────────────────────────────────
            const sectionMap = {
                'rulon': [
                    'section-rulon-varian',
                    'section-rulon-spek',
                    'section-sertifikasi',
                ],
                'tygon': [
                    'section-tygon-varian',
                    'section-tygon-spek',
                ],
                'sg-25': [
                    'section-sg-25-varian',
                ],
            };

            const allSections = [
                'section-rulon-varian',
                'section-rulon-spek',
                'section-sertifikasi',
                'section-tygon-varian',
                'section-tygon-spek',
                'section-sg-25-varian',
            ];

            const categorySelect = document.getElementById('categorySelect');
            const btnSimpan = document.getElementById('btn-simpan');
            const shapeSelect = document.getElementById('shapeSelect');
            const tygonSizeCat = document.getElementById('tygonSizeCategory');

            // ── Tygon: update label unit ──────────────────────────────────────
            function getUnitsFromJenis(jenisText) {
                const lower = (jenisText || '').toLowerCase();
                if (lower.includes('metric'))
                    return {
                        diameter: 'mm',
                        length: 'm'
                    };
                if (lower.includes('inch') || lower.includes('imperial') || lower.includes('tubing'))
                    return {
                        diameter: 'inches',
                        length: 'feet'
                    };
                return {
                    diameter: '-',
                    length: '-'
                };
            }

            function applyTygonUnits(jenisText) {
                const units = getUnitsFromJenis(jenisText);
                ['label-id-unit', 'label-od-unit', 'label-wall-unit', 'label-bend-unit'].forEach(function(id) {
                    const el = document.getElementById(id);
                    if (el) el.textContent = units.diameter;
                });
                const lenEl = document.getElementById('label-len-unit');
                if (lenEl) lenEl.textContent = units.length;
            }

            if (tygonSizeCat) {
                tygonSizeCat.addEventListener('change', function() {
                    const opt = this.options[this.selectedIndex];
                    applyTygonUnits(opt ? opt.getAttribute('data-jenis') : '');
                });
                if (tygonSizeCat.value) {
                    const opt = tygonSizeCat.options[tygonSizeCat.selectedIndex];
                    applyTygonUnits(opt ? opt.getAttribute('data-jenis') : '');
                }
            }

            // ── Show / hide sections ──────────────────────────────────────────
            function hideAll() {
                allSections.forEach(function(id) {
                    const el = document.getElementById(id);
                    if (el) el.style.display = 'none';
                });
            }

            function resolveType(dataType) {
                if (!dataType) return null;

                // Exact match dulu
                if (sectionMap[dataType]) return dataType;

                // Fallback: partial match
                for (const key in sectionMap) {
                    if (dataType.includes(key)) return key;
                }
                return null;
            }

            function applyCategory(dataType) {
                hideAll();
                const matched = resolveType(dataType);

                if (matched) {
                    sectionMap[matched].forEach(function(id) {
                        const el = document.getElementById(id);
                        if (el) el.style.display = 'block';
                    });
                    btnSimpan.disabled = false;
                } else {
                    btnSimpan.disabled = true;
                }
            }

            // Jalankan saat halaman load (untuk kasus old() / validasi error)
            if (categorySelect) {
                categorySelect.addEventListener('change', function() {
                    const opt = this.options[this.selectedIndex];
                    applyCategory(opt ? opt.getAttribute('data-type') : '');
                });

                if (categorySelect.value) {
                    const opt = categorySelect.options[categorySelect.selectedIndex];
                    applyCategory(opt ? opt.getAttribute('data-type') : '');
                }
            }

            // ── Shape reload (Rulon) ──────────────────────────────────────────
            if (shapeSelect) {
                shapeSelect.addEventListener('change', function() {
                    const url = new URL("{{ route('admin.produk.create') }}");
                    url.searchParams.set('id_cat', categorySelect.value);
                    url.searchParams.set('shape_id', this.value);
                    url.searchParams.set('sku', document.querySelector('[name="sku"]').value || '');
                    url.searchParams.set('nama_produk', document.querySelector('[name="nama_produk"]')
                        .value || '');
                    url.searchParams.set('merk', document.querySelector('[name="merk"]').value || '');
                    window.location.href = url.toString();
                });
            }

            // ── SG-25 multi-varian: tambah baris ─────────────────────────────
            const btnAddSg25 = document.getElementById('btn-add-sg25-varian');
            const sg25Body = document.getElementById('sg25-varian-body');

            // Opsi ukuran SG-25 dirender dari PHP agar aman
            const sg25Options = [
                '<option value="">-- Pilih Ukuran --</option>',
                @foreach ($ukuranSg25 as $ukuran)
                    '<option value="{{ $ukuran->id }}">{{ $ukuran->nama_ukuran }}</option>',
                @endforeach
            ].join('');

            if (btnAddSg25 && sg25Body) {
                btnAddSg25.addEventListener('click', function() {
                    const tr = document.createElement('tr');
                    tr.classList.add('sg25-varian-row');
                    tr.innerHTML =
                        '<td><select name="sg25_ukuran_id[]" class="form-select">' + sg25Options +
                        '</select></td>' +
                        '<td><input type="number" name="sg25_stok[]" class="form-control" placeholder="0" min="0"></td>' +
                        '<td><input type="number" name="sg25_harga[]" class="form-control" placeholder="0" min="0"></td>' +
                        '<td class="text-center">' +
                        '<button type="button" class="btn btn-outline-danger btn-sm btn-remove-sg25" title="Hapus baris ini">' +
                        '<i class="bi bi-trash"></i>' +
                        '</button>' +
                        '</td>';
                    sg25Body.appendChild(tr);
                    tr.querySelector('select').focus();
                });

                // ── SG-25: hapus baris (event delegation) ───────────────────
                sg25Body.addEventListener('click', function(e) {
                    const btn = e.target.closest('.btn-remove-sg25');
                    if (btn) btn.closest('tr').remove();
                });
            }

        });
    </script>
@endpush
