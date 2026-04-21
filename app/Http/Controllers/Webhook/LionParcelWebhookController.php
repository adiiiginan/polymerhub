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

        // Log semua webhook masuk
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

        // Update LionShipment
        LionShipment::where('idtrans', $transaksi->id)->update([
            'status'        => $status,
            'rate_response' => json_encode($payload),
        ]);

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
        if (!empty($journeyType)) {
            return match ($journeyType) {
                'return', 'returnhq' => 5, // Cancelled
                'cancel'             => 5, // Cancelled
                'reroute'            => 3, // Shipped
                'return-reroute'     => 5, // Cancelled
                default              => 1,
            };
        }

        return match ($status) {
            'BKD'               => 6, // On Process
            'CIQ', 'STI', 'MNF' => 3, // Shipped
            'POD'               => 4, // Completed
            'RTS', 'RTSHQ'      => 5, // Cancelled
            default             => 1,
        };
    }
}
