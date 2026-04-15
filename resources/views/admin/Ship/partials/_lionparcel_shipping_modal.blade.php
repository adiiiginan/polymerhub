<!-- Lion Parcel Shipping Modal -->
<div class="modal fade" id="lionParcelShippingModal" tabindex="-1" aria-labelledby="lionParcelShippingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lionParcelShippingModalLabel">
                    Detail Pengiriman Lion Parcel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>Detail booking Lion Parcel untuk Invoice <strong>{{ $invoice->kode_inv }}</strong>:</p>

                @if ($lionparcelShipment)
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th>Nomor STT (AWB)</th>
                            <td><strong class="text-primary">{{ $lionparcelShipment->tracking_number ?? '-' }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $lionparcelShipment->status ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Pengirim</th>
                            <td>{{ $lionparcelShipment->shipper_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Penerima</th>
                            <td>{{ $lionparcelShipment->recipient_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tujuan</th>
                            <td>{{ $lionparcelShipment->destination ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Layanan</th>
                            <td>{{ $lionparcelShipment->service_type ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Berat</th>
                            <td>{{ $lionparcelShipment->weight ?? '-' }} kg</td>
                        </tr>
                        <tr>
                            <th>Biaya</th>
                            <td>Rp {{ number_format($lionparcelShipment->total_charge ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    </table>

                    {{-- Form konfirmasi menggunakan data dari booking --}}
                    <form id="lionParcelAwbForm" action="{{ route('admin.lion.update-resi', $invoice->id) }}"
                        method="POST">
                        @csrf
                        {{-- AWB diambil otomatis dari LionShipment, bukan input manual --}}
                        <input type="hidden" name="no_resi" value="{{ $lionparcelShipment->tracking_number }}">

                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Klik <strong>Konfirmasi & Cetak</strong> untuk menyimpan AWB
                            <strong>{{ $lionparcelShipment->tracking_number }}</strong>
                            dan mencetak label pengiriman.
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Belum ada data booking Lion Parcel untuk invoice ini.
                        Silakan buat booking terlebih dahulu.
                    </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                @if ($lionparcelShipment && $lionparcelShipment->tracking_number)
                    <button type="button" class="btn btn-primary" id="btnKonfirmasiCetak">
                        <i class="fas fa-print me-1"></i> Konfirmasi & Cetak
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnKonfirmasiCetak')?.addEventListener('click', function() {
        const sttNumber = "{{ $lionparcelShipment->tracking_number ?? '' }}";
        const clientId = "{{ env('LION_PARCEL_CLIENT_ID', '2407') }}";
        const printUrl = "{{ env('LION_PARCEL_PRINT_URL', 'https://stg-genesis.lionparcel.com/print/stt') }}";

        // 1. Buka tab baru untuk cetak resi dengan client ID
        window.open(`${printUrl}?q=${sttNumber}&client=${clientId}`, '_blank');

        // 2. Submit form untuk update status di database
        document.getElementById('lionParcelAwbForm').submit();
    });
</script>
