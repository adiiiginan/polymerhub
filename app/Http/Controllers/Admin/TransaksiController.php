<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Transaksi;
use App\Models\UserAddress;
use App\Models\TransaksiDetail;
use App\Models\TransaksiRequest;
use App\Models\TransaksiStatus;
use App\Models\TransaksiInvoice;
use App\Models\Produk;

use App\Models\Jenis;
use App\Models\Ukuran;
use App\Models\ProdukStok;
use Illuminate\Support\Str;
use App\Http\Controllers\TransaksiInvoiceController;
use App\Mail\OrderApproved;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function show($id)
    {
        $transaksi = Transaksi::with(['details.produk', 'details.jenis', 'details.ukuran', 'statusRelasi', 'user.userDetail'])->findOrFail($id);

        // Pastikan hanya customer yang benar yang bisa melihat transaksinya
        if (auth()->guard('customer')->id() !== $transaksi->iduser) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan hanya customer yang benar yang bisa melihat transaksinya
        if (auth()->guard('customer')->id() !== $transaksi->iduser) {
            abort(403, 'Unauthorized action.');
        }

        $primaryAddress = null;
        if (auth()->guard('customer')->check()) {
            $primaryAddress = UserAddress::where('user_id', auth()->guard('customer')->id())
                ->where('is_primary', 1)
                ->first();
        }

        return view('frontend.Transaksi.transaksi', compact('transaksi', 'primaryAddress'));
    }

    public function confirm($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = 1; // misalnya 2 = confirmed/paid
        $transaksi->save();

        return redirect()->back()->with('success', 'Transaksi berhasil dikonfirmasi.');
    }

    // Admin


    public function pending()
    {
        $transaksi = Transaksi::with('user.userDetail', 'user.addresses.country', 'details.produk', 'details.jenis', 'details.ukuran')->where('status', 1)->latest()->paginate(20);

        return view('admin.Transaksi.pending', compact('transaksi'));
    }

    public function po()
    {
        $transaksi = Transaksi::with(['user.userDetail', 'invoice', 'details'])->where('status', 7)->latest()->get();

        return view('admin.Transaksi.po', compact('transaksi'));
    }

    public function completed()
    {
        $transaksi = Transaksi::with('user.userDetail', 'details.produk', 'details.jenis', 'details.ukuran',)->where('status', 4)->latest()->get();

        return view('admin.Transaksi.completed', compact('transaksi'));
    }

    public function decline(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = 10;
        $transaksi->save();

        return redirect()->route('admin.transaksi.pending')->with('success', 'Transaksi berhasil ditolak.');
    }

    public function detail($id)
    {
        $transaksi = Transaksi::with(['details.produk', 'details.jenis', 'details.ukuran', 'statusRelasi', 'user.userDetail', 'address.country', 'address.state', 'address.city'])->findOrFail($id);
        return view('admin.Transaksi.detail', compact('transaksi'));
    }



    /**
     * Admin: list transactions that are request orders (is_request = 1)
     */
    public function invoice_paid(Request $request)
    {
        $produk_id = $request->input('produk_id');
        $produks = Produk::all();

        $invoices = TransaksiInvoice::with(['transaksi.user.userDetail.negara', 'transaksi.details', 'statusRelasi'])
            ->where('status', 8)
            ->when($produk_id, function ($query, $produk_id) {
                return $query->whereHas('transaksi.details.produk', function ($q) use ($produk_id) {
                    $q->where('id', $produk_id);
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.Transaksi.invoice_paid', compact('invoices', 'produks', 'produk_id'));
    }

    public function requests()
    {
        // Load request rows directly from transaksi_request table with useful relations
        $requests = TransaksiRequest::with(['user', 'produk'])
            ->where('idstatus', 1)
            ->latest()
            ->paginate(25);

        return view('admin.Transaksi.requests', compact('requests'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'kode_po' => 'required|string|unique:transaksi,kode_po,' . $id,
        ]);

        try {
            $transaksi = Transaksi::with('invoice', 'user.userDetail')->findOrFail($id);
            $transaksi->status = 7;

            $transaksi->kode_po = $request->kode_po;

            $transaksi->save();

            // Recalculate total from details
            $total = $transaksi->details->sum(function ($detail) {
                return $detail->harga * $detail->qty;
            });

            // Create invoice with the correct total
            \App\Models\TransaksiInvoice::create([
                'idtrans'    => $transaksi->id,
                'kode_inv'   => null, // Will be set manually later
                'total'      => $total,
                'status'     => 7, // Invoice Issued
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Kirim email notifikasi ke admin
            Mail::to('adiginandani28@gmail.com')->send(new OrderApproved($transaksi));
            //Mail::to('rifa.hasna@jns.co.id')->send(new OrderApproved($transaksi));

            return redirect()->route('admin.transaksi.pending')->with('success', 'Data berhasil disimpan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Approve a TransaksiRequest (created from customer request) by creating Transaksi + TransaksiDetail
     * Expects POST payload: qty (int), harga (decimal), note (optional)
     */
    public function approveRequest(Request $request, $requestId)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'kode_po' => 'required|string|unique:transaksi,kode_po',
        ]);

        // Load the TransaksiRequest
        $trxReq = \App\Models\TransaksiRequest::findOrFail($requestId);

        // If already linked to a transaksi, redirect back
        if (!empty($trxReq->idtrans)) {
            return redirect()->back()->with('error', 'Request sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Create Transaksi
            $customer = $trxReq->user;
            $kode_user = optional($customer)->kode_user ?? null;
            $iduser = $trxReq->iduser;

            $idtransaksi = 'RQ-' . time() . '-' . rand(100, 999);

            $transaksi = Transaksi::create([
                'idtransaksi' => $idtransaksi,
                'iduser' => $iduser,
                'kode_user' => $kode_user,
                'total' => ($request->harga * $request->qty),
                'status' => 7, // set to PO/approved status 7
                'kode_po' => $request->kode_po,
                'is_request' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Create TransaksiDetail
            TransaksiDetail::create([
                'idtrans' => $transaksi->id,
                'iduser' => $iduser,
                'idproduk' => $trxReq->idproduk,
                'id_jenis' => null,
                'id_ukuran' => null,
                'harga' => $request->harga,
                'qty' => $request->qty,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            Mail::to('adiginandani28@gmail.com')->send(new OrderApproved($transaksi));
            // Update transaksi_request to link to this transaksi and mark status approved (e.g., 2)
            $trxReq->idtrans = $transaksi->id;
            $trxReq->idstatus = 2;
            if (!empty($request->note)) {
                $trxReq->catatan = trim($request->note);
            }
            $trxReq->save();

            // Generate invoice for the transaksi
            $this->generateInvoiceForTransaction($transaksi, $request->harga * $request->qty);

            DB::commit();

            return redirect()->route('admin.transaksi.detailpo', ['id' => $transaksi->id])
                ->with('success', 'Request berhasil disetujui. Transaksi, PO, dan Invoice telah dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memproses approve request: ' . $e->getMessage()]);
        }
    }

    /**
     * Decline a request order.
     * Sets a "declined" status and optionally stores admin note (if implemented).
     */


    public function destroy($id)
    {
        $transaksi = TransaksiRequest::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('admin.permintaan.index')
            ->with('success', 'Data berhasil dihapus.');
    }


    public function detailpo($id)
    {

        $transaksi = Transaksi::with(['user.userDetail', 'details.produk', 'details.jenis', 'details.ukuran', 'invoice'])->findOrFail($id);

        $subtotal = $transaksi->details->sum(function ($detail) {
            return $detail->harga * $detail->qty;
        });
        $ppn = $subtotal * 0.11;

        return view('admin.Transaksi.detailpo', compact('transaksi', 'subtotal', 'ppn'));
    }


    public function confir($id)
    {
        $transaksi = Transaksi::with('user')->findOrFail($id);
        return view('admin.Transaksi.confir', compact('transaksi'));
    }

    /**
     * Generate invoice for a transaction
     */
    private function generateInvoiceForTransaction($transaksi, $total)
    {
        \App\Models\TransaksiInvoice::create([
            'idtrans'    => $transaksi->id,
            'kode_inv'   => null, // Will be set manually later
            'total'      => $total,
            'status'     => 7, // Invoice Issued
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function requestQuote(Request $request)
    {
        $productId = $request->input('product_id');
        $typeId = $request->input('type_id');
        $dimensionId = $request->input('dimension_id');

        $product = Produk::find($productId);
        $type = $typeId ? Jenis::find($typeId) : null;
        $dimension = $dimensionId ? Ukuran::find($dimensionId) : null;

        return view('frontend.request_quote', compact('product', 'type', 'dimension'));
    }

    public function diterima($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = 4; // 4 = completed
        $transaksi->save();

        $invoice = TransaksiInvoice::where('idtrans', $id)->first();
        if ($invoice) {
            $invoice->status = 4;
            $invoice->save();
        }

        return redirect()->route('customer.profile')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
