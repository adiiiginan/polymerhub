{{-- File: resources/views/admin/produk/partials/edit-spec-sg25.blade.php --}}
<div id="section-spec-sg25" class="cat-section card shadow-sm mb-4" style="display:none;">
    <div class="card-header text-center fw-bold py-3">Sertifikasi & Kepatuhan</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">EU1935</label>
                <select name="eu1935" class="form-select">
                    <option value="tidak"
                        {{ strtolower(old('eu1935', $produk->eu1935)) == 'tidak' ? 'selected' : '' }}>Tidak</option>
                    <option value="ya" {{ strtolower(old('eu1935', $produk->eu1935)) == 'ya' ? 'selected' : '' }}>Ya
                    </option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">FDA</label>
                <select name="fda" class="form-select">
                    <option value="tidak" {{ strtolower(old('fda', $produk->fda)) == 'tidak' ? 'selected' : '' }}>Tidak
                    </option>
                    <option value="ya" {{ strtolower(old('fda', $produk->fda)) == 'ya' ? 'selected' : '' }}>Ya
                    </option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">USP</label>
                <select name="usp" class="form-select">
                    <option value="tidak" {{ strtolower(old('usp', $produk->usp)) == 'tidak' ? 'selected' : '' }}>Tidak
                    </option>
                    <option value="ya" {{ strtolower(old('usp', $produk->usp)) == 'ya' ? 'selected' : '' }}>Ya
                    </option>
                </select>
            </div>
        </div>
    </div>
</div>
