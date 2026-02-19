<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        $invoice = TransaksiInvoice::findOrFail($id);
        $invoice->status = 8; // Assuming 8 is paid
        $invoice->save();

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
}
