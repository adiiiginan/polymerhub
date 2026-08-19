{{-- File: resources/views/admin/produk/partials/edit-sg25-varian.blade.php --}}
<div id="section-sg-25-varian" class="cat-section card shadow-sm mb-4" style="display:none;">
    <div class="card-header text-center fw-bold py-3">
        Varian Stok & Harga (SG-25)
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <label class="form-label fw-bold mb-0">
                Pilih Ukuran & Isi Stok/Harga <span class="text-danger">*</span>
            </label>
            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-sg25-varian">
                <i class="bi bi-plus-circle me-1"></i> Tambah Ukuran
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="sg25-varian-table">
                <thead class="table-light">
                    <tr>
                        <th>Ukuran <span class="text-danger">*</span></th>
                        <th>Stok <span class="text-danger">*</span></th>
                        <th style="min-width:140px;">Harga (Rp) <span class="text-danger">*</span></th>
                        <th style="width:50px;" class="text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody id="sg25-varian-body">
                    @forelse($produk->variants as $index => $stok)
                        <tr class="sg25-varian-row">
                            <td>
                                <select name="sg25_variants[{{ $stok->id }}][id_ukuran]" class="form-select">
                                    <option value="">-- Pilih Ukuran --</option>
                                    @foreach ($ukuranSg25 as $ukuran)
                                        <option value="{{ $ukuran->id }}"
                                            {{ $stok->id_ukuran == $ukuran->id ? 'selected' : '' }}>
                                            {{ $ukuran->nama_ukuran }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="sg25_variants[{{ $stok->id }}][stok]"
                                    class="form-control" placeholder="0" min="0" value="{{ $stok->stok }}">
                            </td>
                            <td>
                                <input type="number" name="sg25_variants[{{ $stok->id }}][harga]"
                                    class="form-control" placeholder="0" min="0" value="{{ $stok->harga }}">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-sg25"
                                    title="Hapus baris ini">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        {{-- Baris template akan ditambahkan oleh JS jika tidak ada stok --}}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<template id="sg25-row-template">
    <tr class="sg25-varian-row">
        <td>
            <select name="sg25_variants[new___INDEX__][id_ukuran]" class="form-select">
                <option value="">-- Pilih Ukuran --</option>
                @foreach ($ukuranSg25 as $ukuran)
                    <option value="{{ $ukuran->id }}">{{ $ukuran->nama_ukuran }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="sg25_variants[new___INDEX__][stok]" class="form-control" placeholder="0"
                min="0">
        </td>
        <td>
            <input type="number" name="sg25_variants[new___INDEX__][harga]" class="form-control" placeholder="0"
                min="0">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm btn-remove-sg25" title="Hapus baris ini">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>
</template>
