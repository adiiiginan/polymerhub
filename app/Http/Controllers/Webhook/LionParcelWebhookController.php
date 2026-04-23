<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\LionLog;
use App\Models\LionShipment;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LionParcelWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload     = $request->all();
        $sttNo       = $payload['stt_no']           ?? null;
        $status      = $payload['status_code']      ?? null;
        $journeyType = $payload['stt_journey_type'] ?? '';

        // Log semua webhook masuk ke DB
        LionLog::create([
            'endpoint'      => '/webhook/lionparcel',
            'request_json'  => json_encode($payload),
            'response_json' => null,
            'status_code'   => 200,
        ]);

        Log::channel('lionparcel')->info('WEBHOOK_RECEIVED', [
            'stt_no'       => $sttNo,
            'status'       => $status,
            'journey_type' => $journeyType,
        ]);

        if (!$sttNo || !$status) {
            return response()->json(['success' => false, 'message' => 'Invalid payload'], 400);
        }

        // Cari transaksi berdasarkan lion_parcel_stt
        $transaksi = Transaksi::where('lion_parcel_stt', $sttNo)->first();

        if (!$transaksi) {
            Log::channel('lionparcel')->warning('WEBHOOK_STT_NOT_FOUND', ['stt_no' => $sttNo]);
            return response()->json(['success' => true]); // Tetap return 200 ke Lion
        }

        // Update status Transaksi
        $transaksi->update([
            'status' => $this->mapTransaksiStatus($status, $journeyType),
        ]);

        // Update LionShipment - handle STT ADJUSTED khusus (update weight & tarif)
        if (in_array($status, ['STT ADJUSTED', 'STT ADJUSTED AFTER POD'])) {
            LionShipment::where('idtrans', $transaksi->id)->update([
                'status'        => $status,
                'weight'        => $payload['gross_weight']   ?? null,
                'total_charge'  => $payload['total_tariff']   ?? null,
                'rate_response' => json_encode($payload),
            ]);

            Log::channel('lionparcel')->info('WEBHOOK_STT_ADJUSTED', [
                'stt_no'            => $sttNo,
                'gross_weight'      => $payload['gross_weight']      ?? null,
                'chargeable_weight' => $payload['chargeable_weight'] ?? null,
                'total_tariff'      => $payload['total_tariff']      ?? null,
                'transaksi_id'      => $transaksi->id,
            ]);
        } else {
            LionShipment::where('idtrans', $transaksi->id)->update([
                'status'        => $status,
                'rate_response' => json_encode($payload),
            ]);
        }

        Log::channel('lionparcel')->info('WEBHOOK_PROCESSED', [
            'stt_no'       => $sttNo,
            'status'       => $status,
            'journey_type' => $journeyType,
            'transaksi_id' => $transaksi->id,
        ]);

        return response()->json(['success' => true]);
    }

    private function mapTransaksiStatus(string $status, string $journeyType): int
    {
        // Journey type lebih prioritas dari status_code
        if (!empty($journeyType)) {
            return match ($journeyType) {
                'return', 'returnhq' => 5, // Cancelled - barang kembali ke pengirim / ke HQ
                'cancel'             => 5, // Cancelled - barang dibatalkan
                'reroute'            => 3, // Shipped   - masih jalan, alamat dikoreksi
                'return-reroute'     => 5, // Cancelled - return lalu dialihkan
                default              => 1,
            };
        }

        return match ($status) {
            // Tahap booking & proses awal
            'BKD', 'SHPCRT', 'CRRSRC'           => 6, // On Process

            // Dalam perjalanan
            'CIQ', 'STI', 'STI-SC', 'STI-DEST',
            'MNF', 'DEL', 'PUP', 'REROUTE'      => 3, // Shipped

            // Terkirim & selesai
            'POD',
            'STT ADJUSTED AFTER POD'             => 4, // Completed

            // Perubahan berat/dimensi sebelum POD - tetap on process
            'STT ADJUSTED'                       => 6, // On Process (masih proses, hanya data berubah)

            // Return & cancel
            'RTS', 'RTSHQ', 'CANCEL'             => 5, // Cancelled

            default                              => 1,
        };
    }
}
