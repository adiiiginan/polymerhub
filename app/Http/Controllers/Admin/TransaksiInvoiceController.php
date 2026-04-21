<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\LionLog;
use App\Models\Transaksi;
use App\Models\LionShipment;
use Illuminate\Support\Facades\Log;
use App\Services\LionParcelService;
use App\Models\TransaksiInvoice;

class TransaksiInvoiceController extends Controller
{
    public function index()
    {
        $invoices = TransaksiInvoice::with('transaksi.user.userDetail', 'transaksi.details', 'statusRelasi')
            ->where('status', 7)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.Transaksi.invo', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = TransaksiInvoice::with(['transaksi.user.userDetail', 'transaksi.details.produk'])
            ->findOrFail($id);

        return view('admin.Transaksi.invoice_show', compact('invoice'));
    }

    public function upload_pajak(Request $request, $id)
    {
        $request->validate([
            'faktur' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $invoice = TransaksiInvoice::findOrFail($id);

        if ($request->hasFile('faktur')) {
            $file     = $request->file('faktur');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('backend/assets/media/faktur'), $filename);
            $invoice->faktur = 'backend/assets/media/faktur/' . $filename;
            $invoice->save();
        }

        return redirect()->back()->with('success', 'Faktur pajak berhasil diunggah.');
    }

    public function paid($id)
    {
        $invoice = TransaksiInvoice::with('transaksi')->findOrFail($id);
        $invoice->status = 8;
        $invoice->save();

        $transaksi = $invoice->transaksi;
        if ($transaksi) {
            $transaksi->status = 8;
            $transaksi->save();

            if ($transaksi->expedisi === 'Lion Parcel') {
                try {
                    $this->bookLionParcel($transaksi);
                } catch (\Throwable $e) {
                    // Catat ke lion_logs sekaligus file log
                    $this->lionLog(
                        'booking/after-paid',
                        ['idtrans' => $transaksi->id],
                        ['error'   => $e->getMessage()],
                        0,
                        'error'
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'Invoice berhasil ditandai sebagai lunas.');
    }

    public function updateStatus(Request $request, $id)
    {
        $invoice = TransaksiInvoice::where('idtrans', $id)->first();

        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'kode_inv' => [
                'required',
                'string',
                'max:255',
                Rule::unique('transaksi_invoice')->ignore($invoice->id),
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $invoice->kode_inv = $request->kode_inv;
        $invoice->save();

        Mail::to('adiginandani28@gmail.com')->send(new \App\Mail\InvoiceCreated($invoice));
        return redirect()->back()->with('success', 'Invoice berhasil diperbarui.');
    }

    // =========================================================================
    // LION PARCEL: Get STT Detail
    // Route: GET /admin/transaksi/{id}/lion-detail
    // =========================================================================
    public function lionDetail($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $query = $transaksi->lion_parcel_stt
            ?? $transaksi->lion_parcel_booking_id
            ?? $transaksi->idtransaksi;

        if (!$query) {
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Nomor STT belum tersedia.'], 404);
            }
            return redirect()->back()->with('error', 'Nomor STT belum tersedia.');
        }

        $result = app(LionParcelService::class)->getDetailSTT($query);
        // getDetailSTT() sudah otomatis menyimpan ke lion_logs

        $detail = (!empty($result['data'])) ? $result['data'][0] : null;

        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => $result['success'],
                'stt_no'  => $transaksi->lion_parcel_stt,
                'detail'  => $detail,
                'message' => $result['success'] ? null : ($result['message'] ?? 'Gagal mengambil detail STT.'),
            ]);
        }

        return view('admin.Transaksi.lion_detail', compact('transaksi', 'detail'));
    }

    // =========================================================================
    // LION PARCEL: Refresh status & simpan ke DB
    // Route: POST /admin/transaksi/{id}/lion-refresh
    // =========================================================================
    public function lionRefresh($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $query = $transaksi->lion_parcel_stt ?? $transaksi->lion_parcel_booking_id;

        if (!$query) {
            return redirect()->back()->with('error', 'Nomor STT belum tersedia.');
        }

        $result = app(LionParcelService::class)->getDetailSTT($query);
        // getDetailSTT() sudah otomatis menyimpan ke lion_logs

        if (!$result['success'] || empty($result['data'])) {
            return redirect()->back()->with('error', 'Gagal mengambil data terbaru dari Lion Parcel.');
        }

        $detail = $result['data'][0];

        // Update lion_shipment
        LionShipment::where('idtrans', $transaksi->id)
            ->where('tracking_number', $transaksi->lion_parcel_stt)
            ->update([
                'status'        => $detail['current_status'] ?? null,
                'rate_response' => json_encode($detail),
                'updated_at'    => now(),
            ]);

        // Simpan response terbaru ke transaksi
        $transaksi->lion_parcel_response = json_encode($detail);
        $transaksi->save();

        return redirect()->back()->with('success', 'Status Lion Parcel diperbarui: ' . ($detail['current_status'] ?? '-'));
    }

    // =========================================================================
    // PRIVATE: Book Lion Parcel (dipanggil saat invoice paid)
    // =========================================================================
    private function bookLionParcel($transaksi)
    {
        $service = app(LionParcelService::class);

        $transaksi->load('details.produk.variants', 'address');

        $receiverAddress = $transaksi->address;
        if (!$receiverAddress) {
            throw new \Exception('Alamat pengiriman tidak ditemukan.');
        }

        $subtotal = $transaksi->details->sum(fn($item) => $item->harga * $item->qty);

        $pieces = $transaksi->details->map(function ($item) {
            $variant = optional($item->produk->variants
                ->where('id_ukuran', $item->id_ukuran)
                ->first());

            return [
                'stt_piece_length'       => (float)($variant->length ?? 1),
                'stt_piece_width'        => (float)($variant->width ?? 1),
                'stt_piece_height'       => (float)($variant->height ?? 1),
                'stt_piece_gross_weight' => (float)($item->gros ?? 1),
            ];
        })->toArray();

        $kecamatan   = trim(str_ireplace(['Kab.', 'Kabupaten', 'Kec.', 'Kecamatan'], '', $receiverAddress->kecamatan));
        $city        = trim(str_ireplace(['Kab.', 'Kabupaten', 'Kec.', 'Kecamatan'], '', $receiverAddress->city));
        $destination = strtoupper($kecamatan . ', ' . $city);
        $origin      = strtoupper(config('services.lionparcel.shipper.origin'));

        $payload = [
            'stt' => [
                'stt_no'                   => '',
                'stt_no_ref_external'      => $transaksi->idtransaksi,
                'stt_tax_number'           => '09.314.652.0-987.319',
                'stt_goods_estimate_price' => $subtotal,
                'stt_goods_status'         => '',
                'stt_origin'               => $origin,
                'stt_destination'          => $destination,
                'stt_sender_name'          => config('services.lionparcel.shipper.name'),
                'stt_sender_phone'         => config('services.lionparcel.shipper.phone'),
                'stt_sender_address'       => config('services.lionparcel.shipper.address'),
                'stt_recipient_name'       => $receiverAddress->nama,
                'stt_recipient_address'    => $receiverAddress->alamat,
                'stt_recipient_phone'      => $receiverAddress->phone,
                'stt_insurance_type'       => 'free',
                'stt_product_type'         => strtolower($transaksi->shipping_service),
                'stt_commodity_code'       => 'BPI087',
                'stt_is_cod'               => false,
                'stt_is_dfod'              => false,
                'stt_is_woodpacking'       => false,
                'stt_pieces'               => $pieces,
                'stt_piece_per_pack'       => 1,
                'stt_next_commodity'       => '',
                'stt_cod_amount'           => 0,
            ]
        ];

        // createShipment() sudah otomatis menyimpan ke lion_logs
        $response = $service->createShipment($payload);

        if (!($response['success'] ?? false)) {
            throw new \Exception($response['message'] ?? 'Lion API gagal.');
        }

        $sttNo = $response['data']['stt'][0]['stt_no'] ?? null;
        $sttId = $response['data']['stt'][0]['stt_id'] ?? null;

        $transaksi->update([
            'lion_parcel_stt'        => $sttNo,
            'lion_parcel_booking_id' => $sttId,
            'lion_parcel_response'   => json_encode($response),
        ]);

        LionShipment::create([
            'idtrans'           => $transaksi->id,
            'tracking_number'   => $sttNo,
            'booking_id'        => $sttId,
            'service_type'      => $transaksi->shipping_service,
            'total_charge'      => $transaksi->shipping_cost,
            'status'            => 'BKD',
            'rate_response'     => json_encode($response),
            'shipper_address'   => json_encode(config('services.lionparcel.shipper')),
            'recipient_address' => json_encode($payload['stt']),
            'weight'            => $payload['stt']['stt_pieces'][0]['stt_piece_gross_weight'] ?? 0,
            'currency'          => 'IDR',
        ]);

        // Catat success ke lion_logs sekaligus file log
        $this->lionLog(
            'booking/after-paid',
            ['idtrans' => $transaksi->id],
            ['stt_no' => $sttNo, 'stt_id' => $sttId],
            200,
            'success'
        );
    }

    // =========================================================================
    // PRIVATE HELPER: Tulis ke lion_logs + channel lionparcel sekaligus
    // Dipakai untuk event di controller yang tidak melalui LionParcelService
    // =========================================================================
    private function lionLog(
        string $endpoint,
        array  $request,
        array  $response,
        int    $statusCode,
        string $status
    ): void {
        try {
            LionLog::create([
                'endpoint'      => $endpoint,
                'request_json'  => json_encode($request),
                'response_json' => json_encode($response),
                'status_code'   => (string) $statusCode,
                'status'        => $status,
            ]);
        } catch (\Throwable $e) {
            Log::error('LION_LOG_DB_WRITE_FAILED', ['error' => $e->getMessage()]);
        }

        $logLabel = 'BOOKING_AFTER_PAID_' . strtoupper($status);
        $context  = array_merge($request, $response, ['status_code' => $statusCode]);

        if ($status === 'error') {
            Log::channel('lionparcel')->error($logLabel, $context);
        } else {
            Log::channel('lionparcel')->info($logLabel, $context);
        }
    }
}
