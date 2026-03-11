<?php

namespace App\Http\Controllers;

use App\Services\FedexService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FedExController extends Controller
{
    protected $fedexService;

    public function __construct(FedexService $fedexService)
    {
        $this->fedexService = $fedexService;
    }

    public function getRates(Request $request)
    {
        Log::info('FedExController->getRates: Received request.', $request->all());

        try {
            $rates = $this->fedexService->getRates($request->all());
            Log::info('FedExController->getRates: Rates retrieved successfully.', ['rates' => $rates]);
            return response()->json(['rates' => $rates]);
        } catch (\Exception $e) {
            Log::error('FedExController->getRates: Exception caught.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'An unexpected error occurred.', 'details' => $e->getMessage()], 500);
        }
    }
}
