<?php

// app/Http/Controllers/Frontend/TrackingController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\LionParcelService;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function track(Request $request, LionParcelService $lionParcelService)
    {
        $sttNo = $request->query('stt_no');

        if (!$sttNo) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor resi tidak boleh kosong'
            ], 400);
        }

        $result = $lionParcelService->getTrackingSTT($sttNo);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor resi tidak ditemukan'
            ], 404);
        }

        $stt = $result['data'][0] ?? null;

        return response()->json([
            'success' => true,
            'data'    => [
                'stt_no'         => $stt['stt_no'] ?? null,
                'sender_name'    => $stt['sender_name'] ?? null,
                'recipient_name' => $stt['recipient_name'] ?? null,
                'origin'         => $stt['origin'] ?? null,
                'destination'    => $stt['destination'] ?? null,
                'current_status' => $stt['current_status'] ?? null,
                'status_code'    => $stt['status_code'] ?? null,
                'product_type'   => $stt['product_type'] ?? null,
                'history'        => $stt['history'] ?? [],
            ]
        ]);
    }
}
