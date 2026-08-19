{{-- File: resources/views/admin/produk/partials/edit-spec-mechanical.blade.php --}}
<div id="section-spec-mechanical" class="cat-section card shadow-sm mb-4" style="display:none;">
    <div class="card-header text-center fw-bold py-3">Spesifikasi Mekanis</div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Tensile Strength (MPa)</label>
                <input type="number" name="tensile" class="form-control" value="{{ old('tensile', $produk->tensile) }}"
                    step="any">
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
