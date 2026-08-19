@extends('admin.dashboard')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white text-center py-3">
                <h4 class="mb-0 fw-bold">Edit Produk: {{ $produk->nama_produk }}</h4>
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

                <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" id="product-form"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- ============================= KOLOM KIRI ============================= --}}
                        <div class="col-md-6">

                            {{-- Informasi Dasar Produk --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-center fw-bold py-3">
                                    Informasi Dasar Produk
                                </div>
                                <div class="card-body">

                                    {{-- Tipe Produk (disabled) --}}
                                    <div class="mb-4 p-3 border border-secondary rounded bg-light">
                                        <label class="form-label fw-bold">Tipe Produk</label>
                                        <input type="text" class="form-control"
                                            value="{{ $selectedCat->category ?? 'Tidak ada kategori' }}" disabled>
                                        <div class="form-text">
                                            Tipe produk tidak dapat diubah setelah dibuat.
                                        </div>
                                        <input type="hidden" name="id_cat" value="{{ $produk->id_cat }}">
                                    </div>

                                    {{-- Kode, SKU, Nama --}}
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Kode Produk</label>
                                            <input type="text" class="form-control" value="{{ $produk->kode_produk }}"
                                                disabled>
                                            <input type="hidden" name="kode_produk" value="{{ $produk->kode_produk }}">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">SKU Produk <span class="text-danger">*</span></label>
                                            <input type="text" name="sku" class="form-control"
                                                value="{{ old('sku', $produk->sku) }}" required>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_produk" class="form-control"
                                                value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                                        </div>
                                    </div>

                                    {{-- Dimensi --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Weight (kg)</label>
                                            <input type="number" name="weight" class="form-control"
                                                value="{{ old('weight', optional($firstStock)->weight) }}" step="any">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Length (cm)</label>
                                            <input type="number" name="length" class="form-control"
                                                value="{{ old('length', optional($firstStock)->length) }}" step="any">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Width (cm)</label>
                                            <input type="number" name="width" class="form-control"
                                                value="{{ old('width', optional($firstStock)->width) }}" step="any">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Height (cm)</label>
                                            <input type="number" name="height" class="form-control"
                                                value="{{ old('height', optional($firstStock)->height) }}" step="any">
                                        </div>
                                    </div>

                                    {{-- Deskripsi --}}
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                                    </div>

                                    {{-- Kategori & Merk --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Kategori Produk</label>
                                            <select name="id_kat" class="form-select">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach ($kategori as $kat)
                                                    <option value="{{ $kat->id }}"
                                                        {{ old('id_kat', $produk->id_kat) == $kat->id ? 'selected' : '' }}>
                                                        {{ $kat->kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Merk</label>
                                            <input type="text" name="merk" class="form-control"
                                                value="{{ old('merk', $produk->merk) }}">
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
                                        <label class="form-label fw-bold">Shape <span class="text-danger">*</span></label>
                                        <select name="shape_id" id="shapeSelect" class="form-select">
                                            <option value="">-- Pilih Shape --</option>
                                            @php
                                                $selectedShapeId = old('shape_id', optional($firstStock)->id_jenis);
                                            @endphp
                                            @foreach ($shapes as $shape)
                                                <option value="{{ $shape->id }}"
                                                    {{ $selectedShapeId == $shape->id ? 'selected' : '' }}>
                                                    {{ $shape->jenis }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="varian-container">
                                        @foreach ($produk->variants as $index => $stok)
                                            <div class="varian-item mb-3 p-3 border rounded">
                                                <input type="hidden" name="variants[{{ $index }}][stok_id]"
                                                    value="{{ $stok->id }}">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label class="form-label">Ukuran</label>
                                                        <select name="id_ukuran[]" class="form-select">
                                                            <option value="">-- Pilih Ukuran --</option>
                                                            @foreach ($ukurans as $ukuran)
                                                                <option value="{{ $ukuran->id }}"
                                                                    {{ $stok->id_ukuran == $ukuran->id ? 'selected' : '' }}>
                                                                    {{ $ukuran->nama_ukuran }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Stok</label>
                                                        <input type="number" name="stok_variant[]" class="form-control"
                                                            placeholder="Stok" min="0"
                                                            value="{{ $stok->stok }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Harga</label>
                                                        <input type="number" name="harga_variant[]" class="form-control"
                                                            placeholder="Harga" min="0"
                                                            value="{{ $stok->harga }}">
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-variant">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <template id="rulon-variant-template">
                                        <div class="varian-item mb-3 p-3 border rounded">
                                            <input type="hidden" name="variants[__INDEX__][stok_id]" value="">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label class="form-label">Ukuran</label>
                                                    <select name="id_ukuran[]" class="form-select">
                                                        <option value="">-- Pilih Ukuran --</option>
                                                        @foreach ($ukurans as $ukuran)
                                                            <option value="{{ $ukuran->id }}">
                                                                {{ $ukuran->nama_ukuran }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Stok</label>
                                                    <input type="number" name="stok_variant[]" class="form-control"
                                                        placeholder="Stok" min="0" value="0">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Harga</label>
                                                    <input type="number" name="harga_variant[]" class="form-control"
                                                        placeholder="Harga" min="0" value="0">
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-variant">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <button type="button" id="add-rulon-variant"
                                        class="btn btn-outline-primary btn-sm mt-2">
                                        <i class="bi bi-plus-circle"></i> Tambah Varian Ukuran
                                    </button>
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
                                                @forelse($produk->variants as $stok)
                                                    <tr class="sg25-varian-row">
                                                        <td>
                                                            <select name="sg25_ukuran_id[]" class="form-select">
                                                                <option value="">-- Pilih Ukuran --</option>
                                                                @foreach ($ukuranSg25 as $ukuran)
                                                                    <option value="{{ $ukuran->id }}"
                                                                        {{ $stok->id_ukuran == $ukuran->id ? 'selected' : '' }}>
                                                                        {{ $ukuran->nama_ukuran }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" name="stok_id[]"
                                                                value="{{ $stok->id }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="sg25_stok[]" class="form-control"
                                                                placeholder="0" min="0"
                                                                value="{{ $stok->stok }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="sg25_harga[]"
                                                                class="form-control" placeholder="0" min="0"
                                                                value="{{ $stok->harga }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                class="btn btn-outline-danger btn-sm btn-remove-sg25"
                                                                title="Hapus baris ini">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="sg25-varian-row">
                                                        <td>
                                                            <select name="sg25_ukuran_id[]" class="form-select">
                                                                <option value="">-- Pilih Ukuran --</option>
                                                                @foreach ($ukuranSg25 as $ukuran)
                                                                    <option value="{{ $ukuran->id }}">
                                                                        {{ $ukuran->nama_ukuran }}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" name="stok_id[]" value="">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="sg25_stok[]" class="form-control"
                                                                placeholder="0" min="0">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="sg25_harga[]"
                                                                class="form-control" placeholder="0" min="0">
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                class="btn btn-outline-danger btn-sm btn-remove-sg25"
                                                                title="Hapus baris ini">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- ============================= KOLOM KANAN ============================= --}}
                        <div class="col-md-6">

                            {{-- Spesifikasi Mekanis --}}
                            <div id="section-spec-mechanical" class="cat-section card shadow-sm mb-4"
                                style="display:none;">
                                <div class="card-header text-center fw-bold py-3">Spesifikasi Mekanis</div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Tensile Strength (MPa)</label>
                                            <input type="number" name="tensile" class="form-control"
                                                value="{{ old('tensile', $produk->tensile) }}" step="any">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Elongation (%)</label>
                                            <input type="text" name="elongation" class="form-control"
                                                value="{{ old('elongation', $produk->elongation) }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Specific Gravity</label>
                                            <input type="number" name="spesific" class="form-control"
                                                value="{{ old('spesific', $produk->spesific) }}" step="any">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Coefficient of Friction</label>
                                            <input type="text" name="friction" class="form-control"
                                                value="{{ old('friction', $produk->friction) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Spesifikasi Rulon --}}
                            <div id="section-spec-rulon" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold py-3">Spesifikasi Rulon</div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Environment</label>
                                            <select name="id_environmant" class="form-select">
                                                <option value="">-- Pilih Environment --</option>
                                                @foreach ($environment as $env)
                                                    <option value="{{ $env->id }}"
                                                        {{ old('id_environmant', $produk->id_environmant) == $env->id ? 'selected' : '' }}>
                                                        {{ $env->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Pressure (psi)</label>
                                            <input type="text" name="pressure" class="form-control"
                                                value="{{ old('pressure', $produk->pressure) }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Mating Surface</label>
                                            <input type="text" name="mating" class="form-control"
                                                value="{{ old('mating', $produk->mating) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Max PV (psi-fpm)</label>
                                            <input type="number" name="max_pv" class="form-control"
                                                value="{{ old('max_pv', $produk->max_pv) }}" step="any">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Maximum P (psi)</label>
                                            <input type="text" name="maximum_p" class="form-control"
                                                value="{{ old('maximum_p', $produk->maximum_p) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Max V (fpm)</label>
                                            <input type="number" name="max_v" class="form-control"
                                                value="{{ old('max_v', $produk->max_v) }}" step="any">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Deformation Under Load (%)</label>
                                            <input type="number" name="deformation" class="form-control"
                                                value="{{ old('deformation', $produk->deformation) }}" step="any">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Spesifikasi Tygon --}}
                            <div id="section-spec-tygon" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold py-3">Spesifikasi & Stok (Tygon)</div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Kategori Ukuran</label>
                                            <select name="tygon_size_category" class="form-select">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach ($jenis as $j)
                                                    <option value="{{ $j->id }}"
                                                        {{ old('tygon_size_category', $produk->tygon_size_category) == $j->id ? 'selected' : '' }}>
                                                        {{ $j->jenis }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Inner Diameter</label>
                                            <input type="text" name="inner_diameter" class="form-control"
                                                value="{{ old('inner_diameter', $produk->inner_diameter) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Outer Diameter</label>
                                            <input type="text" name="outer_diameter" class="form-control"
                                                value="{{ old('outer_diameter', $produk->outer_diameter) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Wall Thickness</label>
                                            <input type="text" name="wall_thickness" class="form-control"
                                                value="{{ old('wall_thickness', $produk->wall_thickness) }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Min. Bend Radius</label>
                                            <input type="text" name="min_bend_radius" class="form-control"
                                                value="{{ old('min_bend_radius', $produk->min_bend_radius) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Length (ft)</label>
                                            <input type="number" name="tygon_length" class="form-control"
                                                value="{{ old('tygon_length', $produk->tygon_length) }}" step="any">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Stok</label>
                                            <input type="number" name="tygon_stok" class="form-control"
                                                value="{{ old('tygon_stok', $produk->variants->first()->stok ?? 0) }}"
                                                min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Harga</label>
                                            <input type="number" name="tygon_harga" class="form-control"
                                                value="{{ old('tygon_harga', $produk->variants->first()->harga ?? 0) }}"
                                                min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Spesifikasi SG-25 --}}
                            <div id="section-spec-sg25" class="cat-section card shadow-sm mb-4" style="display:none;">
                                <div class="card-header text-center fw-bold py-3">Sertifikasi & Kepatuhan</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">EU1935</label>
                                            <select name="eu1935" class="form-select">
                                                <option value="tidak"
                                                    {{ strtolower(old('eu1935', $produk->eu1935)) == 'tidak' ? 'selected' : '' }}>
                                                    Tidak</option>
                                                <option value="ya"
                                                    {{ strtolower(old('eu1935', $produk->eu1935)) == 'ya' ? 'selected' : '' }}>
                                                    Ya</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">FDA</label>
                                            <select name="fda" class="form-select">
                                                <option value="tidak"
                                                    {{ strtolower(old('fda', $produk->fda)) == 'tidak' ? 'selected' : '' }}>
                                                    Tidak</option>
                                                <option value="ya"
                                                    {{ strtolower(old('fda', $produk->fda)) == 'ya' ? 'selected' : '' }}>Ya
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">USP</label>
                                            <select name="usp" class="form-select">
                                                <option value="tidak"
                                                    {{ strtolower(old('usp', $produk->usp)) == 'tidak' ? 'selected' : '' }}>
                                                    Tidak</option>
                                                <option value="ya"
                                                    {{ strtolower(old('usp', $produk->usp)) == 'ya' ? 'selected' : '' }}>Ya
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Gambar & Status --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-center fw-bold py-3">
                                    Gambar & Status
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="gambar" class="form-label">Upload Gambar Baru</label>
                                        <input class="form-control" type="file" id="gambar" name="gambar"
                                            onchange="previewImage()">
                                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah
                                            gambar.</small>
                                    </div>
                                    <div class="mb-3 text-center">
                                        <p class="fw-bold">Gambar Saat Ini:</p>
                                        @if ($produk->gambar)
                                            <img src="{{ asset('backend/assets/media/produk/' . $produk->gambar) }}"
                                                alt="Gambar Produk" class="img-fluid rounded" style="max-height: 200px;"
                                                id="image-preview">
                                        @else
                                            <img src="https://via.placeholder.com/300x200.png?text=No+Image"
                                                alt="Preview Gambar" class="img-fluid rounded" style="max-height: 200px;"
                                                id="image-preview">
                                        @endif
                                    </div>

                                    <hr>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="status_aktif"
                                                name="status_aktif" value="1"
                                                {{ old('status_aktif', $produk->status_aktif) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_aktif">Aktif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save me-2"></i> Update Produk
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Fungsi untuk preview gambar
        function previewImage() {
            const image = document.querySelector('#gambar');
            const imgPreview = document.querySelector('#image-preview');
            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);
            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const categoryType = "{{ $categoryType ?? '' }}";

            // ── Tampilkan section sesuai tipe produk ─────────────────────
            function showHideSections() {
                document.querySelectorAll('.cat-section').forEach(section => {
                    section.style.display = 'none';
                });
                if (categoryType === 'rulon') {
                    document.getElementById('section-rulon-varian').style.display = 'block';
                    document.getElementById('section-spec-mechanical').style.display = 'block';
                    document.getElementById('section-spec-rulon').style.display = 'block';
                } else if (categoryType === 'tygon') {
                    document.getElementById('section-spec-tygon').style.display = 'block';
                    document.getElementById('section-spec-mechanical').style.display = 'block';
                } else if (categoryType === 'sg-25') {
                    document.getElementById('section-sg-25-varian').style.display = 'block';
                    document.getElementById('section-spec-sg25').style.display = 'block';
                }
            }
            showHideSections();

            // ── Rulon: tambah/hapus varian ───────────────────────────────
            const varianContainer = document.getElementById('varian-container');
            const addVariantBtn = document.getElementById('add-rulon-variant');
            const variantTemplate = document.getElementById('rulon-variant-template');
            let variantIndex = {{ $produk->variants->count() }};

            if (addVariantBtn && variantTemplate) {
                addVariantBtn.addEventListener('click', function() {
                    const templateContent = variantTemplate.innerHTML.replace(/__INDEX__/g, variantIndex);
                    const newVarian = document.createElement('div');
                    newVarian.innerHTML = templateContent;
                    varianContainer.appendChild(newVarian.firstElementChild);
                    variantIndex++;
                });
            }

            if (varianContainer) {
                varianContainer.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-variant')) {
                        e.target.closest('.varian-item').remove();
                    }
                });
            }

            // ── Rulon: reload ukuran saat shape berubah ──────────────────
            const shapeSelect = document.getElementById('shapeSelect');
            if (shapeSelect) {
                shapeSelect.addEventListener('change', function() {
                    const shapeId = this.value;
                    if (!shapeId) return;

                    fetch(`/api/get-ukurans-by-shape/${shapeId}`)
                        .then(response => response.json())
                        .then(data => {
                            varianContainer.innerHTML = '';
                            variantIndex = 0;

                            if (variantTemplate) {
                                const ukuranSelect = variantTemplate.content.querySelector('select');
                                ukuranSelect.innerHTML = '<option value="">-- Pilih Ukuran --</option>';
                                data.forEach(ukuran => {
                                    const option = new Option(ukuran.nama_ukuran, ukuran.id);
                                    ukuranSelect.add(option);
                                });
                            }

                            if (addVariantBtn) addVariantBtn.click();
                        })
                        .catch(error => {
                            console.error('Error fetching sizes:', error);
                            varianContainer.innerHTML =
                                '<p class="text-danger">Gagal memuat ukuran.</p>';
                        });
                });
            }

            // ── SG-25: tambah/hapus baris ────────────────────────────────
            const sg25TableBody = document.getElementById('sg25-varian-body');
            const addSg25Button = document.getElementById('btn-add-sg25-varian');

            if (addSg25Button && sg25TableBody) {
                addSg25Button.addEventListener('click', function() {
                    const firstRow = sg25TableBody.querySelector('.sg25-varian-row');
                    if (!firstRow) return;
                    const newRow = firstRow.cloneNode(true);
                    newRow.querySelector('select').selectedIndex = 0;
                    newRow.querySelectorAll('input[type="number"]').forEach(input => input.value = '');
                    newRow.querySelectorAll('input[type="hidden"]').forEach(input => input.value = '');
                    sg25TableBody.appendChild(newRow);
                });
            }

            if (sg25TableBody) {
                sg25TableBody.addEventListener('click', function(e) {
                    if (e.target.closest('.btn-remove-sg25')) {
                        if (sg25TableBody.querySelectorAll('.sg25-varian-row').length > 1) {
                            e.target.closest('.sg25-varian-row').remove();
                        } else {
                            alert('Setidaknya harus ada satu baris varian.');
                        }
                    }
                });
            }
        });
    </script>
@endpush
