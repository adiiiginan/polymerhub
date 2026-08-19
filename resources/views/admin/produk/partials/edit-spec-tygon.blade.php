{{-- File: resources/views/admin/produk/partials/edit-spec-tygon.blade.php --}}
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
                            {{ old('tygon_size_category', $produk->id_jenis) == $j->id ? 'selected' : '' }}>
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
                    value="{{ old('tygon_stok', $produk->variants->first()->stok ?? 0) }}" min="0">
            </div>
            <div class="col-md-6">
                <label class="form-label">Harga</label>
                <input type="number" name="tygon_harga" class="form-control"
                    value="{{ old('tygon_harga', $produk->stok->first()->harga ?? 0) }}" min="0">
            </div>
        </div>
    </div>
</div>
