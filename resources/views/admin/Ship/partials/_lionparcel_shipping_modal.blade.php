<!-- Lion Parcel Shipping Modal -->
<div class="modal fade" id="lionParcelShippingModal" tabindex="-1" aria-labelledby="lionParcelShippingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="lionParcelAwbForm" action="{{ route('admin.lion.update-resi', $invoice->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="lionParcelShippingModalLabel">
                        Atur Pengiriman Lion Parcel
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>
                        Masukkan nomor AWB (resi) untuk Invoice
                        <strong>{{ $invoice->kode_inv }}</strong>.
                    </p>

                    <div class="mb-3">
                        <label for="no_resi" class="form-label">Nomor AWB (Resi)</label>
                        <input type="text" class="form-control" id="no_resi" name="no_resi"
                            placeholder="Masukkan nomor AWB" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Simpan & Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
