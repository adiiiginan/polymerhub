{{-- File: resources/views/admin/produk/partials/edit-rulon-varian.blade.php --}}

<div id="varian-container">
    @forelse($produk->variants as $index => $stok)
        <div class="varian-item mb-3 p-3 border rounded">
            <input type="hidden" name="variants[{{ $index }}][stok_id]" value="{{ $stok->id }}">
            <div class="row">
                <div class="col-md-5">
                    <label class="form-label">Ukuran</label>
                    <select name="variants[{{ $index }}][id_ukuran]" class="form-select">
                        <option value="">-- Pilih Ukuran --</option>
                        @foreach ($ukurans as $ukuran)
                            <option value="{{ $ukuran->id }}" {{ $stok->id_ukuran == $ukuran->id ? 'selected' : '' }}>
                                {{ $ukuran->nama_ukuran }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="variants[{{ $index }}][stok]" class="form-control"
                        placeholder="Stok" min="0" value="{{ $stok->stok }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="variants[{{ $index }}][harga]" class="form-control"
                        placeholder="Harga" min="0" value="{{ $stok->harga }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-variant">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    @empty
        {{-- Kosong, akan ditambahkan oleh JS jika diperlukan --}}
    @endforelse
</div>

<template id="rulon-variant-template">
    <div class="varian-item mb-3 p-3 border rounded">
        <input type="hidden" name="variants[__INDEX__][stok_id]" value="">
        <div class="row">
            <div class="col-md-5">
                <label class="form-label">Ukuran</label>
                <select name="variants[__INDEX__][id_ukuran]" class="form-select">
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
                <input type="number" name="variants[__INDEX__][stok]" class="form-control" placeholder="Stok"
                    min="0" value="0">
            </div>
            <div class="col-md-3">
                <label class="form-label">Harga</label>
                <input type="number" name="variants[__INDEX__][harga]" class="form-control" placeholder="Harga"
                    min="0" value="0">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-variant">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>
