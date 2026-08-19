<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\FedexCommercialInvoice;
use App\Models\FedexCommercialInvoiceItem;
use App\Models\FedexShipment;
use App\Models\FedexTradeDocument;
use App\Models\LionShipment;
use App\Models\Transaksi;
use App\Models\TransaksiInvoice;
use App\Services\FedexService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\LionLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\ProdukStok;
use App\Models\User;
use App\Models\TransaksiProses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ShippingNotificationMail;
use App\Mail\CustomerShippingNotificationMail;
use App\Models\Ship;

class ShipController extends Controller
{
    protected $fedexService;

    public function __construct(FedexService $fedexService)
    {
        $this->fedexService = $fedexService;
    }

    public function index()
    {
        $invoices = TransaksiInvoice::with(['transaksi.user.userDetail.negara'])
            ->whereHas('transaksi')
            ->whereIn('status', [8])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.Ship.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = TransaksiInvoice::with([
            'transaksi.user.userDetail',
            'transaksi.details.produk',
            'transaksi.details.ukuran',
            'transaksi.address.city',
            'transaksi.address.country',
        ])->findOrFail($id);

        if (!$invoice->transaksi->address) {
            $primaryAddress = \App\Models\UserAddress::where('user_id', $invoice->transaksi->iduser)
                ->where('is_primary', 1)
                ->with(['city', 'country'])
                ->first();
            if ($primaryAddress) {
                $invoice->transaksi->setRelation('address', $primaryAddress);
            }
        }

        $fedexShipment      = FedexShipment::where('idtrans', $invoice->idtrans)->first();
        $lionparcelShipment = LionShipment::where('idtrans', $invoice->idtrans)->first();

        return view('admin.Ship.show', compact('invoice', 'fedexShipment', 'lionparcelShipment'));
    }

    public function update(Request $request, $id)
    {
        $invoice = TransaksiInvoice::with('transaksi.details.produk')->findOrFail($id);

        $existingShipment = TransaksiProses::where('kode_inv', $invoice->kode_inv)->first();
        if ($existingShipment) {
            return redirect()->back()->with('error', 'Informasi pengiriman untuk invoice ini sudah ada.');
        }

        $shippingCode = 'SHIP-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $proses = TransaksiProses::create([
            'idtrans'   => $invoice->idtrans,
            'kode_ship' => $shippingCode,
            'status'    => 3,
            'kode_inv'  => $invoice->kode_inv,
        ]);

        $invoice->status = 3;
        $invoice->save();

        $invoice->transaksi->status     = 3;
        $invoice->transaksi->shipped_at = now();
        $invoice->transaksi->save();

        try {
            if ($invoice->transaksi->details->isNotEmpty()) {
                foreach ($invoice->transaksi->details as $detail) {
                    $stokProduk = ProdukStok::where('id_produk', $detail->idproduk)
                        ->where('id_jenis', $detail->id_jenis)
                        ->where('id_ukuran', $detail->id_ukuran)
                        ->first();

                    if ($stokProduk) {
                        $stokProduk->stok -= $detail->qty;
                        $stokProduk->save();
                    } else {
                        Log::warning('Product stock variant not found, stock not updated.', [
                            'invoice_id'     => $invoice->id,
                            'transaction_id' => $invoice->idtrans,
                            'product_id'     => $detail->idproduk,
                            'id_jenis'       => $detail->id_jenis,
                            'id_ukuran'      => $detail->id_ukuran,
                        ]);
                    }
                }
            } else {
                Log::warning('No details found for transaction, stock not updated.', [
                    'invoice_id'     => $invoice->id,
                    'transaction_id' => $invoice->idtrans,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error updating stock: ' . $e->getMessage(), [
                'invoice_id'     => $invoice->id,
                'transaction_id' => $invoice->idtrans,
            ]);
        }

        Mail::to('adiginandani28@gmail.com')->send(new ShippingNotificationMail($invoice));

        if ($invoice->transaksi->is_request == 1) {
            Mail::to('adiginandani28@gmail.com')->send(new CustomerShippingNotificationMail($invoice));
        }

        return redirect()->route('admin.ship.print_surat_jalan', $proses->id);
    }

    public function shipment()
    {
        $invoices = TransaksiInvoice::with(['transaksi.user.userDetail', 'proses'])
            ->where('status', 3)
            ->paginate(10);

        return view('admin.Ship.shipment', compact('invoices'));
    }

    public function updateResiLion(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $invoice   = TransaksiInvoice::findOrFail($id);
            $transaksi = $invoice->transaksi;

            $lionShipment = LionShipment::where('idtrans', $transaksi->id)->first();
            if (!$lionShipment || empty($lionShipment->tracking_number)) {
                return redirect()->back()->with('error', 'Data Lion Parcel shipment tidak ditemukan.');
            }

            $invoice->status = 3;
            $invoice->save();

            $transaksi->status     = 3;
            $transaksi->shipped_at = now();
            $transaksi->save();

            TransaksiProses::updateOrCreate(
                ['idtrans' => $transaksi->id],
                [
                    'kode_ship' => 'SHIP-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                    'status'    => 3,
                    'kode_inv'  => $invoice->kode_inv,
                    'no_resi'   => $lionShipment->tracking_number,
                    'expedisi'  => 'Lion Parcel',
                ]
            );

            LionLog::create([
                'endpoint'      => 'update_resi_lion',
                'status_code'   => 200,
                'request_json'  => json_encode([
                    'invoice_id' => $invoice->id,
                    'kode_inv'   => $invoice->kode_inv,
                    'idtrans'    => $transaksi->id,
                ]),
                'response_json' => json_encode([
                    'tracking_number' => $lionShipment->tracking_number,
                    'booking_id'      => $lionShipment->booking_id,
                    'service_type'    => $lionShipment->service_type,
                    'rate_response'   => json_decode($lionShipment->rate_response),
                ]),
                'status' => 'success',
            ]);

            DB::commit();

            session()->flash('success', 'Status pengiriman berhasil diperbarui. AWB siap dicetak.');
            return redirect()->route('admin.ship.show', $invoice->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update resi Lion Parcel: ' . $e->getMessage(), ['invoice_id' => $id]);

            try {
                LionLog::create([
                    'endpoint'      => 'update_resi_lion',
                    'status_code'   => 500,
                    'request_json'  => json_encode(['invoice_id' => $id]),
                    'response_json' => json_encode(['error' => $e->getMessage()]),
                    'status'        => 'failed',
                ]);
            } catch (\Exception $logEx) {
                Log::error('Gagal menulis ke lion_logs: ' . $logEx->getMessage());
            }

            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function updateResi(Request $request, $id)
    {
        $request->validate([
            'no_resi'        => 'required|string|max:255',
            'jasa_ekspedisi' => 'required|string|max:255',
        ]);

        $proses = TransaksiProses::findOrFail($id);
        $proses->update([
            'no_resi'  => $request->no_resi,
            'expedisi' => $request->jasa_ekspedisi,
        ]);

        return redirect()->back()->with('success', 'Resi berhasil diperbarui.');
    }

    public function print_surat_jalan($id)
    {
        $proses  = TransaksiProses::with('transaksi.user.userDetail.negara')->findOrFail($id);
        $invoice = TransaksiInvoice::where('idtrans', $proses->idtrans)
            ->with(['transaksi.user.userDetail.negara', 'transaksi.details.produk', 'transaksi.details.ukuran'])
            ->firstOrFail();
        $today = Carbon::now()->translatedFormat('d F Y');

        return view('admin.Ship.surat_jalan_dan_resi', compact('invoice', 'proses', 'today'));
    }

    public function packing_slip($id)
    {
        $invoice = TransaksiInvoice::with([
            'transaksi.user.userDetail.negara',
            'transaksi.details.produk',
            'transaksi.details.jenis',
            'transaksi.details.ukuran',
        ])->findOrFail($id);

        return view('admin.ship.packing_slip', compact('invoice'));
    }

    public function createShipment(Request $request, FedexService $fedexService)
    {
        $request->validate([
            'invoice_id' => 'required|exists:transaksi_invoice,id',
        ]);

        $invoice = TransaksiInvoice::with([
            'transaksi.details.produk',
            'transaksi.address.country',
            'transaksi.address.state',
            'transaksi.address.city',
            'transaksi.user.userDetail',
        ])->findOrFail($request->invoice_id);

        $transaksi = $invoice->transaksi;

        if (!$transaksi->address) {
            $primaryAddress = \App\Models\UserAddress::where('user_id', $transaksi->iduser)
                ->where('is_primary', 1)
                ->with(['city', 'country', 'state'])
                ->first();
            if ($primaryAddress) {
                $transaksi->setRelation('address', $primaryAddress);
            }
        }

        if (!$transaksi->address) {
            $errorMessage = "No shipping address found for this transaction (ID: {$transaksi->id}) and no primary address for the user (ID: {$transaksi->iduser}).";
            Log::error($errorMessage);
            return redirect()->back()->with('error', $errorMessage);
        }

        try {
            $destinationCountryCode = $transaksi->address->country->iso2
                ?? $transaksi->address->country->country_code
                ?? 'US';

            // 1. Buat Commercial Invoice di DB dulu
            $commercialInvoice = FedexCommercialInvoice::create([
                'order_id'          => $transaksi->id,
                'shipment_id'       => 0, // sementara, update setelah shipment dibuat
                'invoice_number'    => $invoice->kode_inv,
                'invoice_date'      => $invoice->created_at->format('Y-m-d'),
                'awb_number'        => null,
                'incoterms'         => 'DAP',
                'reason_for_export' => 'Sale',
                'currency'          => 'USD',
                'subtotal'          => $transaksi->total,
                'freight'           => $transaksi->ongkir,
                'insurance'         => 0,
                'total_value'       => $transaksi->total + $transaksi->ongkir,
                'gross_weight'      => $transaksi->details->sum(fn($d) => $d->produk->berat * $d->qty),
                'net_weight'        => $transaksi->details->sum(fn($d) => $d->produk->berat * $d->qty),
                'status'            => 'pending',
            ]);

            foreach ($transaksi->details as $item) {
                FedexCommercialInvoiceItem::create([
                    'fedex_commercial_invoice_id' => $commercialInvoice->id,
                    'description'                 => $item->produk->nama_produk,
                    'hs_code'                     => $item->produk->hs_code,
                    'country_of_origin'           => 'ID',
                    'quantity'                    => $item->qty,
                    'unit'                        => 'PCS',
                    'unit_price'                  => $item->harga,
                    'total_price'                 => $item->total,
                ]);
            }

            // 2. Generate PDF
            $pdf        = Pdf::loadView('pdf.commercial_invoice', ['invoice' => $commercialInvoice->fresh('items')]);
            $pdfContent = $pdf->output();

            $relativePath = 'backend/assets/commercial/' . $commercialInvoice->invoice_number . '.pdf';
            $absolutePath = public_path($relativePath);

            if (!is_dir(dirname($absolutePath))) {
                mkdir(dirname($absolutePath), 0755, true);
            }

            file_put_contents($absolutePath, $pdfContent);
            $commercialInvoice->update(['pdf_path' => $relativePath]);

            // 3. Buat FedEx Shipment dulu → dapat trackingNumber
            $shipmentResult = $fedexService->createShipment($transaksi, null);
            $trackingNumber = $shipmentResult['trackingNumber'];

            // 4. Simpan shipment ke DB
            $fedexShipment                    = new FedexShipment();
            $fedexShipment->idtrans           = $invoice->idtrans;
            $fedexShipment->tracking_number   = $trackingNumber;
            $fedexShipment->shipment_id       = $shipmentResult['shipmentId'];
            $fedexShipment->label_url         = $shipmentResult['labelUrl'];
            $fedexShipment->service_type      = $shipmentResult['serviceType'];
            $fedexShipment->total_charge      = $shipmentResult['totalCharge'];
            $fedexShipment->status            = 'Success';
            $fedexShipment->rate_response     = $shipmentResult['rateResponse'];
            $fedexShipment->shipper_address   = $shipmentResult['shipperAddress'];
            $fedexShipment->recipient_address = $shipmentResult['recipientAddress'];
            $fedexShipment->weight            = $shipmentResult['weight'];
            $fedexShipment->currency          = $shipmentResult['currency'];
            $fedexShipment->save();

            // 5. Upload dokumen ETD ke FedEx — sekarang trackingNumber sudah ada
            $docId = null;
            try {
                $tradeDocService = new \App\Services\FedexTradeDocumentService($fedexService);

                $uploadResponse = $tradeDocService->uploadCommercialInvoice(
                    $trackingNumber,
                    $relativePath,
                    'ID',
                    $destinationCountryCode,
                    $invoice->created_at->format('Y-m-d')
                );

                $docId = $uploadResponse['output']['locator']['id']
                    ?? $uploadResponse['output']['documentId']
                    ?? null;

                Log::info('FedEx ETD upload success.', [
                    'docId'          => $docId,
                    'trackingNumber' => $trackingNumber,
                    'response'       => $uploadResponse,
                ]);
            } catch (\Throwable $uploadEx) {
                Log::warning('FedEx ETD upload failed (non-critical): ' . $uploadEx->getMessage());
            }

            // 6. Update commercial invoice dengan shipment_id, awb_number, dan docId
            $commercialInvoice->update([
                'shipment_id'       => $fedexShipment->id,
                'awb_number'        => $trackingNumber,
                'status'            => $docId ? 'uploaded' : 'pending',
                'fedex_document_id' => $docId,
            ]);

            // 7. Simpan FedexTradeDocument
            FedexTradeDocument::create([
                'shipment_id'                 => $fedexShipment->id,
                'fedex_commercial_invoice_id' => $commercialInvoice->id,
                'document_type'               => 'CO',
                'file_path'                   => $relativePath,
                'fedex_document_id'           => $docId,
                'upload_status'               => $docId ? 'uploaded' : 'pending',
                'error_message'               => null,
            ]);

            Log::info("FedEx shipment created for Invoice ID: {$invoice->id}. Tracking: {$trackingNumber}", [
                'doc_id'          => $docId,
                'tracking_number' => $trackingNumber,
            ]);

            return redirect()->route('admin.ship.show', $invoice->id)
                ->with('success', "Shipment created successfully. AWB: {$trackingNumber}");
        } catch (\Throwable $e) {
            Log::critical("FedEx Shipment Failed for Invoice ID: {$invoice->id}", [
                'error_message'  => $e->getMessage(),
                'invoice_id'     => $invoice->id,
                'transaction_id' => $invoice->idtrans,
                'trace'          => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'An error occurred while creating the FedEx shipment: ' . $e->getMessage());
        }
    }

    // ============================================================
    // PRINT FEDEX LABEL
    // FIX: Ambil label dari DB, JANGAN create shipment baru
    // ============================================================
    public function printFedexLabel($id)
    {
        try {
            $invoice = TransaksiInvoice::findOrFail($id);

            // Ambil shipment yang sudah ada dari DB
            $fedexShipment = FedexShipment::where('idtrans', $invoice->idtrans)
                ->where('status', 'Success')
                ->latest()
                ->first();

            if (!$fedexShipment) {
                Log::error("printFedexLabel: No FedEx shipment found for invoice ID {$id}");
                return redirect()->back()->with('error', 'FedEx shipment not found. Please create the shipment first.');
            }

            if (empty($fedexShipment->label_url)) {
                Log::error("printFedexLabel: label_url is empty for shipment ID {$fedexShipment->id}");
                return redirect()->back()->with('error', 'Label URL not found. Please recreate the shipment.');
            }

            Log::info("printFedexLabel: Opening label for invoice {$id}", [
                'tracking_number' => $fedexShipment->tracking_number,
                'label_url'       => $fedexShipment->label_url,
            ]);

            // label_url sudah berupa local asset URL (disimpan saat createShipment)
            // langsung redirect ke sana — browser akan render PDF
            return redirect()->away($fedexShipment->label_url);
        } catch (\Exception $e) {
            Log::error('printFedexLabel error: ' . $e->getMessage(), [
                'invoice_id' => $id,
                'trace'      => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Failed to open FedEx label. Please check the logs.');
        }
    }

    // ============================================================
    // PRINT LION PARCEL
    // ============================================================
    public function print($invoice_id)
    {
        $invoice      = TransaksiInvoice::findOrFail($invoice_id);
        $lionShipment = LionShipment::where('idtrans', $invoice->idtrans)->first();

        if (!$lionShipment || empty($lionShipment->tracking_number)) {
            return redirect()->back()->with('error', 'Nomor STT Lion Parcel tidak ditemukan untuk invoice ini.');
        }

        $sttNumber   = $lionShipment->tracking_number;
        $clientId    = env('LION_PARCEL_CLIENT_ID', '2407');
        $printUrl    = env('LION_PARCEL_PRINT_URL', 'https://stg-genesis.lionparcel.com/print/stt');
        $queryParams = http_build_query(['q' => $sttNumber, 'client' => $clientId]);
        $fullUrl     = $printUrl . '?' . $queryParams;

        return redirect()->away($fullUrl);
    }

    public function updateStatusAfterPrint(Request $request, $invoice_id)
    {
        DB::beginTransaction();
        try {
            $invoice   = TransaksiInvoice::findOrFail($invoice_id);
            $transaksi = Transaksi::findOrFail($invoice->idtrans);

            $invoice->status = 3;
            $invoice->save();

            $transaksi->status     = 3;
            $transaksi->shipped_at = now();
            $transaksi->save();

            TransaksiProses::create([
                'idtrans'    => $invoice->idtrans,
                'kode_ship'  => 'SHIP-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'status'     => 3,
                'kode_inv'   => $invoice->kode_inv,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            $lionShipment = LionShipment::where('idtrans', $invoice->idtrans)->first();
            if (!$lionShipment || empty($lionShipment->tracking_number)) {
                return response()->json(['error' => 'Nomor STT tidak ditemukan.'], 404);
            }

            $sttNumber = $lionShipment->tracking_number;
            $clientId  = env('LION_PARCEL_CLIENT_ID', '2407');
            $printUrl  = env('LION_PARCEL_PRINT_URL', 'https://stg-genesis.lionparcel.com/print/stt');
            $fullUrl   = $printUrl . '?' . http_build_query(['q' => $sttNumber, 'client' => $clientId]);

            LionLog::create([
                'endpoint'      => 'print_awb_lion',
                'status_code'   => 200,
                'request_json'  => json_encode([
                    'invoice_id' => $invoice->id,
                    'kode_inv'   => $invoice->kode_inv,
                    'idtrans'    => $invoice->idtrans,
                ]),
                'response_json' => json_encode([
                    'tracking_number' => $sttNumber,
                    'booking_id'      => $lionShipment->booking_id,
                    'print_url'       => $fullUrl,
                ]),
                'status' => 'success',
            ]);

            session()->flash('success', 'Status pengiriman berhasil diperbarui dan AWB siap dicetak.');

            return response()->json(['success' => true, 'print_url' => $fullUrl]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui status setelah cetak AWB: ' . $e->getMessage());

            try {
                LionLog::create([
                    'endpoint'      => 'print_awb_lion',
                    'status_code'   => 500,
                    'request_json'  => json_encode(['invoice_id' => $invoice_id]),
                    'response_json' => json_encode(['error' => $e->getMessage()]),
                    'status'        => 'failed',
                ]);
            } catch (\Exception $logEx) {
                Log::error('Gagal menulis ke lion_logs: ' . $logEx->getMessage());
            }

            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui status.'], 500);
        }
    }
}
