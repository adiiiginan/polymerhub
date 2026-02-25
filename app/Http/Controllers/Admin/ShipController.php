<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\UploadTradeDocuments;
use App\Models\FedexCommercialInvoice;
use App\Models\FedexCommercialInvoiceItem;
use App\Models\LionShipment;
use App\Models\Transaksi;
use App\Models\TransaksiInvoice;
use App\Services\FedexService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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
            ->whereHas('transaksi') // Ensure the transaction exists
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
            'transaksi.address.country'
        ])->findOrFail($id);

        // Fallback to the user's primary address if the transaction address is not set
        if (!$invoice->transaksi->address) {
            $primaryAddress = \App\Models\UserAddress::where('user_id', $invoice->transaksi->iduser)
                ->where('is_primary', 1)
                ->with(['city', 'country'])
                ->first();
            if ($primaryAddress) {
                $invoice->transaksi->setRelation('address', $primaryAddress);
            }
        }

        $fedexShipment = \App\Models\FedexShipment::where('idtrans', $invoice->idtrans)->first();
        $lionparcelShipment = LionShipment::where('idtrans', $invoice->idtrans)->first();

        return view('admin.Ship.show', compact('invoice', 'fedexShipment', 'lionparcelShipment'));
    }

    public function update(Request $request, $id)
    {
        $invoice = TransaksiInvoice::with('transaksi.details.produk')->findOrFail($id);
        // Validasi agar tidak ada data ganda
        $existingShipment = TransaksiProses::where('kode_inv', $invoice->kode_inv)->first();
        if ($existingShipment) {
            return redirect()->back()->with('error', 'Informasi pengiriman untuk invoice ini sudah ada.');
        }

        // Generate unique shipping code
        $shippingCode = 'SHIP-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // Auto-generate no_resi and jasa_ekspedisi if not provided
        $noResi = $request->no_resi ?? 'RESI-' . strtoupper(Str::random(8));
        $jasaEkspedisi = $request->jasa_ekspedisi ?? 'JNE'; // Default to JNE, can be changed

        $proses = TransaksiProses::create([
            'idtrans' => $invoice->idtrans,
            'kode_ship' => $shippingCode,
            'status' => 3, // Dikirim
            'kode_inv' => $invoice->kode_inv,

        ]);

        // Update invoice status
        $invoice->status = 3;
        $invoice->save();

        // Update transaction status
        $invoice->transaksi->status = 3;
        $invoice->transaksi->shipped_at = now();
        $invoice->transaksi->save();

        // Kurangi stok produk
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
                        // Log jika stok untuk varian produk tidak ditemukan
                        Log::warning('Product stock variant not found, stock not updated.', [
                            'invoice_id' => $invoice->id,
                            'transaction_id' => $invoice->idtrans,
                            'product_id' => $detail->idproduk,
                            'id_jenis' => $detail->id_jenis,
                            'id_ukuran' => $detail->id_ukuran,
                        ]);
                    }
                }
            } else {
                Log::warning('No details found for transaction, stock not updated.', [
                    'invoice_id' => $invoice->id,
                    'transaction_id' => $invoice->idtrans,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error updating stock: ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'transaction_id' => $invoice->idtrans,
            ]);
        }

        // Send email notification to admins
        Mail::to('adiginandani28@gmail.com')->send(new ShippingNotificationMail($invoice));

        // Also send customer notification to admin if is_request=1
        if ($invoice->transaksi->is_request == 1) {
            Mail::to('adiginandani28@gmail.com')->send(new CustomerShippingNotificationMail($invoice));
        }

        // Redirect to print surat jalan and resi
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
        $request->validate([
            'no_resi' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $invoice = TransaksiInvoice::findOrFail($id);
            $transaksi = $invoice->transaksi;

            // Update no_resi di invoice
            $invoice->no_resi = $request->no_resi;
            $invoice->jasa_ekspedisi = 'Lion Parcel';
            $invoice->status = 3; // Mark as shipped
            $invoice->save();

            // Update status transaksi
            $transaksi->status = 3; // Dikirim
            $transaksi->shipped_at = now();
            $transaksi->save();

            // Catat ke proses transaksi, hindari duplikat
            TransaksiProses::updateOrCreate(
                ['idtrans' => $transaksi->id],
                [
                    'kode_ship' => 'SHIP-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                    'status' => 3, // Dikirim
                    'kode_inv' => $invoice->kode_inv,
                    'no_resi' => $request->no_resi,
                    'expedisi' => 'Lion Parcel',
                ]
            );

            DB::commit();

            // Siapkan URL untuk cetak
            $printUrl = route('admin.lion.print', $invoice->id);

            session()->flash('success', 'Nomor resi berhasil disimpan. AWB siap dicetak.');
            session()->flash('print_url', $printUrl);


            return redirect()->route('admin.ship.show', $invoice->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui nomor resi Lion Parcel: ' . $e->getMessage(), ['invoice_id' => $id]);
            return redirect()->back()->with('error', 'Gagal memperbarui nomor resi: ' . $e->getMessage());
        }
    }


    public function updateResi(Request $request, $id)
    {
        $request->validate([
            'no_resi' => 'required|string|max:255',
            'jasa_ekspedisi' => 'required|string|max:255',
        ]);

        $proses = TransaksiProses::findOrFail($id);

        $proses->update([
            'no_resi' => $request->no_resi,
            'expedisi' => $request->jasa_ekspedisi,
        ]);

        return redirect()->back()->with('success', 'Resi berhasil diperbarui.');
    }

    public function print_surat_jalan($id)
    {
        $proses = TransaksiProses::with('transaksi.user.userDetail.negara')->findOrFail($id);
        $invoice = TransaksiInvoice::where('idtrans', $proses->idtrans)->with(['transaksi.user.userDetail.negara', 'transaksi.details.produk', 'transaksi.details.ukuran'])->firstOrFail();
        $today = Carbon::now()->translatedFormat('d F Y');
        return view('admin.Ship.surat_jalan_dan_resi', compact('invoice', 'proses', 'today'));
    }

    public function packing_slip($id)
    {
        $invoice = TransaksiInvoice::with(['transaksi.user.userDetail.negara', 'transaksi.details.produk', 'transaksi.details.jenis', 'transaksi.details.ukuran'])->findOrFail($id);
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
            'transaksi.user.userDetail'
        ])->findOrFail($request->invoice_id);
        $transaksi = $invoice->transaksi;

        // Fallback to the user's primary address if the transaction address is not set
        if (!$transaksi->address) {
            $primaryAddress = \App\Models\UserAddress::where('user_id', $transaksi->iduser)
                ->where('is_primary', 1)
                ->with(['city', 'country', 'state']) // Eager load all needed relations
                ->first();
            if ($primaryAddress) {
                $transaksi->setRelation('address', $primaryAddress);
            }
        }

        // Final check for address before proceeding
        if (!$transaksi->address) {
            $errorMessage = "No shipping address found for this transaction (ID: {$transaksi->id}) and no primary address for the user (ID: {$transaksi->iduser}).";
            Log::error($errorMessage);
            return redirect()->back()->with('error', $errorMessage);
        }

        try {
            // 1. Create FedEx Shipment and get AWB
            $shipmentResult = $fedexService->createShipment($transaksi);

            $trackingNumber = $shipmentResult['trackingNumber'];
            $labelPath = $shipmentResult['labelPath'];

            // Save shipment details to the database
            $fedexShipment = new \App\Models\FedexShipment();
            $fedexShipment->idtrans = $invoice->idtrans;
            $fedexShipment->tracking_number = $shipmentResult['trackingNumber'];
            $fedexShipment->shipment_id = $shipmentResult['shipmentId'];
            $fedexShipment->label_url = $shipmentResult['labelUrl'];
            $fedexShipment->service_type = $shipmentResult['serviceType'];
            $fedexShipment->total_charge = $shipmentResult['totalCharge'];
            $fedexShipment->status = 'Success'; // Initial status
            $fedexShipment->rate_response = $shipmentResult['rateResponse'];
            $fedexShipment->shipper_address = $shipmentResult['shipperAddress'];
            $fedexShipment->recipient_address = $shipmentResult['recipientAddress'];
            $fedexShipment->weight = $shipmentResult['weight'];
            $fedexShipment->currency = $shipmentResult['currency'];
            $fedexShipment->save();

            // 2. Create Commercial Invoice in the database
            $commercialInvoice = FedexCommercialInvoice::create([
                'order_id' => $transaksi->id,
                'shipment_id' => $fedexShipment->id,
                'invoice_number' => $invoice->kode_inv,
                'invoice_date' => $invoice->created_at->format('Y-m-d'),
                'awb_number' => $trackingNumber,
                'incoterms' => 'DAP', // Or get from request/config
                'reason_for_export' => 'Sale', // Or get from request/config
                'currency' => 'USD', // Or get from transaction
                'subtotal' => $transaksi->total,
                'freight' => $transaksi->ongkir,
                'insurance' => 0, // Or get from transaction
                'total_value' => $transaksi->total + $transaksi->ongkir,
                'gross_weight' => $transaksi->details->sum(function ($d) {
                    return $d->produk->berat * $d->qty;
                }),
                'net_weight' => $transaksi->details->sum(function ($d) {
                    return $d->produk->berat * $d->qty;
                }),
                'status' => 'pending',
            ]);

            foreach ($transaksi->details as $item) {
                FedexCommercialInvoiceItem::create([
                    'fedex_commercial_invoice_id' => $commercialInvoice->id,
                    'description' => $item->produk->nama_produk,
                    'hs_code' => $item->produk->hs_code, // Assuming hs_code is on the produk model
                    'country_of_origin' => 'ID', // Assuming origin is Indonesia
                    'quantity' => $item->qty,
                    'unit' => 'PCS', // Or get from product
                    'unit_price' => $item->harga,
                    'total_price' => $item->total,
                ]);
            }

            // ARCHITECTURE CHANGE: Save the PDF first, then dispatch the job with only the ID.
            // 3. Generate PDF and save it to a stable location.
            $pdf = Pdf::loadView('pdf.commercial_invoice', ['invoice' => $commercialInvoice->fresh('items')]);
            $pdfContent = $pdf->output(); // Get raw PDF content

            // Define a stable, unique filename.
            $relativePath = 'backend/assets/commercial/' . $commercialInvoice->invoice_number . '.pdf';
            $absolutePath = public_path($relativePath);

            // pastikan folder ada
            if (!is_dir(dirname($absolutePath))) {
                mkdir(dirname($absolutePath), 0755, true);
            }

            // simpan PDF
            file_put_contents($absolutePath, $pdfContent);

            // simpan path ke DB (RELATIVE DARI public/)
            $commercialInvoice->update([
                'pdf_path' => $relativePath
            ]);

            // 4. Dispatch the job with ONLY the invoice ID. The payload is now small and efficient.
            UploadTradeDocuments::dispatch($trackingNumber, $commercialInvoice->id)->onQueue('fedex');

            Log::info("FedEx shipment created and document upload job dispatched for Invoice ID: {$invoice->id}. Tracking: {$trackingNumber}");

            return redirect()->route('admin.ship.index')->with('success', "Shipment created successfully with AWB: {$trackingNumber}");
        } catch (\Throwable $e) {
            Log::critical("FedEx Shipment or Job Dispatch Failed for Invoice ID: {$invoice->id}", [
                'error_message' => $e->getMessage(),
                'invoice_id' => $invoice->id,
                'transaction_id' => $invoice->idtrans,
            ]);

            return redirect()->back()->with('error', 'An error occurred while creating the FedEx shipment. Please check the logs for details.');
        }
    }



    public function print($invoice_id)
    {
        // 1. Cari invoice dan relasi pengiriman Lion Parcel
        $invoice = TransaksiInvoice::findOrFail($invoice_id);
        $lionShipment = LionShipment::where('idtrans', $invoice->idtrans)->first();

        // 2. Validasi jika pengiriman atau nomor STT tidak ditemukan
        if (!$lionShipment || empty($lionShipment->tracking_number)) {
            return redirect()->back()->with('error', 'Nomor STT Lion Parcel tidak ditemukan untuk invoice ini.');
        }

        // 3. Ambil konfigurasi dari .env
        $sttNumber = $lionShipment->tracking_number;
        $clientId = env('LION_PARCEL_CLIENT_ID', '2407'); // Fallback ke client ID contoh
        $printUrl = env('LION_PARCEL_PRINT_URL', 'https://stg-genesis.lionparcel.com/print/stt');

        // 4. Buat query string dengan aman
        $queryParams = http_build_query([
            'q' => $sttNumber,
            'client' => $clientId,
        ]);

        // 5. Gabungkan URL dan redirect ke halaman eksternal
        $fullUrl = $printUrl . '?' . $queryParams;

        return redirect()->away($fullUrl);
    }

    public function updateStatusAfterPrint(Request $request, $invoice_id)
    {
        DB::beginTransaction();
        try {
            $invoice = TransaksiInvoice::findOrFail($invoice_id);
            $transaksi = Transaksi::findOrFail($invoice->idtrans);

            // Update status
            $invoice->status = 3;
            $invoice->save();

            $transaksi->status = 3;
            $transaksi->shipped_at = now();
            $transaksi->save();

            // Create TransaksiProses record
            TransaksiProses::create([
                'idtrans' => $invoice->idtrans,
                'kode_ship' => 'SHIP-' . date('Ymd') . '-' . strtoupper(Str::random(4)), // Or generate as needed
                'status' => 3, // Dikirim
                'kode_inv' => $invoice->kode_inv,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            // Prepare the redirect URL for printing
            $lionShipment = LionShipment::where('idtrans', $invoice->idtrans)->first();
            if (!$lionShipment || empty($lionShipment->tracking_number)) {
                return response()->json(['error' => 'Nomor STT tidak ditemukan.'], 404);
            }

            $sttNumber = $lionShipment->tracking_number;
            $clientId = env('LION_PARCEL_CLIENT_ID', '2407');
            $printUrl = env('LION_PARCEL_PRINT_URL', 'https://stg-genesis.lionparcel.com/print/stt');
            $queryParams = http_build_query(['q' => $sttNumber, 'client' => $clientId]);
            $fullUrl = $printUrl . '?' . $queryParams;

            // Flash a success message to the session
            session()->flash('success', 'Status pengiriman berhasil diperbarui dan AWB siap dicetak.');

            return response()->json([
                'success' => true,
                'print_url' => $fullUrl
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui status setelah cetak AWB: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui status.'], 500);
        }
    }
}
