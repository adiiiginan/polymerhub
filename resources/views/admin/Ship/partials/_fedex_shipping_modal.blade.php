<!-- FedEx Shipping Modal -->
<div class="modal fade" id="fedexShippingModal" tabindex="-1" aria-labelledby="fedexShippingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fedexShippingModalLabel">Atur Pengiriman via FedEx</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.ship.create-shipment') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Anda akan membuat pengiriman untuk Invoice <strong>{{ $invoice->kode_inv }}</strong> menggunakan
                        FedEx.</p>
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                    {{-- Add form fields for FedEx shipment details here if needed --}}
                    {{-- For example, package dimensions, weight, etc. --}}
                    {{-- If no extra fields are needed, this confirmation is enough. --}}

                    <div class="alert alert-info">
                        Pastikan semua detail barang dan alamat penerima sudah benar sebelum melanjutkan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat Pengiriman FedEx</button>
                </div>
            </form>
        </div>
    </div>
</div>
