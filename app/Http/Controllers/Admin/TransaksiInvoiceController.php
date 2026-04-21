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

use App\Models\TransaksiInvoice;

class TransaksiInvoiceController extends Controller
{
    public function index()
    {
        $invoices = TransaksiInvoice::with('transaksi.user.userDetail', 'transaksi.details', 'statusRelasi')->where('status', 7)->orderBy('created_at', 'desc')->get();
        return view('admin.Transaksi.invo', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = TransaksiInvoice::with(['transaksi.user.userDetail', 'transaksi.details.produk'])->findOrFail($id);
        return view('admin.Transaksi.invoice_show', compact('invoice'));
    }

    public function upload_pajak(Request $request, $id)
    {
        $request->validate([
            'faktur' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $invoice = TransaksiInvoice::findOrFail($id);

        if ($request->hasFile('faktur')) {
            $file = $request->file('faktur');
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

        // Update status transaksi ke Paid (8)
        $transaksi = $invoice->transaksi;
        if ($transaksi) {
            $transaksi->status = 8;
            $transaksi->save();

            // Trigger booking Lion Parcel jika expedisi Lion Parcel
            if ($transaksi->expedisi === 'Lion Parcel') {
                try {
                    $this->bookLionParcel($transaksi);
                } catch (\Throwable $e) {
                    Log::channel('lionparcel')->error('BOOKING_AFTER_PAID_FAILED', [
                        'idtrans' => $transaksi->id,
                        'error'   => $e->getMessage(),
                    ]);
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

        // Kirim email notifikasi ke admin
        Mail::to('adiginandani28@gmail.com')->send(new \App\Mail\InvoiceCreated($invoice));
        //Mail::to('fitra@jns.co.id')->send(new \App\Mail\InvoiceCreated($invoice));
        return redirect()->back()->with('success', 'Invoice berhasil diperbarui.');
    }

    private function bookLionParcel($transaksi)
    {
        $lionParcelService = app(\App\Services\LionParcelService::class);

        // Load relasi yang dibutuhkan
        $transaksi->load('details.produk.variants', 'address');

        $receiverAddress = $transaksi->address;
        if (!$receiverAddress) {
            throw new \Exception('Alamat pengiriman tidak ditemukan.');
        }

        // Hitung subtotal
        $subtotal = $transaksi->details->sum(fn($item) => $item->harga * $item->qty);

        // Siapkan pieces
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

        // Siapkan destination
        $kecamatan   = trim(str_ireplace(['Kab.', 'Kabupaten', 'Kec.', 'Kecamatan'], '', $receiverAddress->kecamatan));
        $city        = trim(str_ireplace(['Kab.', 'Kabupaten', 'Kec.', 'Kecamatan'], '', $receiverAddress->city));
        $destination = strtoupper($kecamatan . ', ' . $city);
        $origin      = strtoupper(config('services.lionparcel.shipper.origin'));

        $payload = [
            'stt' => [
                'stt_no'                  => '',
                'stt_no_ref_external'     => $transaksi->idtransaksi,
                'stt_tax_number'          => '09.314.652.0-987.319',
                'stt_goods_estimate_price' => $subtotal,
                'stt_goods_status'        => '',
                'stt_origin'              => $origin,
                'stt_destination'         => $destination,
                'stt_sender_name'         => config('services.lionparcel.shipper.name'),
                'stt_sender_phone'        => config('services.lionparcel.shipper.phone'),
                'stt_sender_address'      => config('services.lionparcel.shipper.address'),
                'stt_recipient_name'      => $receiverAddress->nama,
                'stt_recipient_address'   => $receiverAddress->alamat,
                'stt_recipient_phone'     => $receiverAddress->phone,
                'stt_insurance_type'      => 'free',
                'stt_product_type'        => strtolower($transaksi->shipping_service),
                'stt_commodity_code'      => 'BPI087',
                'stt_is_cod'              => false,
                'stt_is_dfod'             => false,
                'stt_is_woodpacking'      => false,
                'stt_pieces'              => $pieces,
                'stt_piece_per_pack'      => 1,
                'stt_next_commodity'      => '',
                'stt_cod_amount'          => 0,
            ]
        ];

        $response = $lionParcelService->createShipment($payload);

        if (!($response['success'] ?? false)) {
            throw new \Exception($response['message'] ?? 'Lion API gagal.');
        }

        $sttNo     = $response['data']['stt'][0]['stt_no'] ?? null;
        $sttId     = $response['data']['stt'][0]['stt_id'] ?? null;

        // Update transaksi
        $transaksi->update([
            'lion_parcel_stt'        => $sttNo,
            'lion_parcel_booking_id' => $sttId,
            'lion_parcel_response'   => json_encode($response),
        ]);

        // Simpan ke LionShipment
        \App\Models\LionShipment::create([
            'idtrans'          => $transaksi->id,
            'tracking_number'  => $sttNo,
            'booking_id'       => $sttId,
            'service_type'     => $transaksi->shipping_service,
            'total_charge'     => $transaksi->shipping_cost,
            'status'           => 'BKD',
            'rate_response'    => json_encode($response),
            'shipper_address'  => json_encode(config('services.lionparcel.shipper')),
            'recipient_address' => json_encode($payload['stt']),
            'weight'           => $payload['stt']['stt_pieces'][0]['stt_piece_gross_weight'] ?? 0,
            'currency'         => 'IDR',
        ]);

        Log::channel('lionparcel')->info('BOOKING_AFTER_PAID_SUCCESS', [
            'idtrans' => $transaksi->id,
            'stt_no'  => $sttNo,
        ]);
    }
}
