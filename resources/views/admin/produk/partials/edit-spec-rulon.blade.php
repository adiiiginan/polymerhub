{{-- File: resources/views/admin/produk/partials/edit-spec-rulon.blade.php --}}
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
                <input type="text" name="mating" class="form-control" value="{{ old('mating', $produk->mating) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Max PV (psi-fpm)</label>
                <input type="number" name="max_pv" class="form-control" value="{{ old('max_pv', $produk->max_pv) }}"
                    step="any">
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
                <input type="number" name="max_v" class="form-control" value="{{ old('max_v', $produk->max_v) }}"
                    step="any">
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
