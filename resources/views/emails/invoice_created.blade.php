@extends('layouts.email')

@section('content')
    <div class="container">
        <div class="header">
            <h1>Invoice Telah Dibuat</h1>
        </div>

        <div class="content">
            <p>Hai Admin,</p>

            <p>Invoice dengan nomor <strong>{{ $invoice->kode_inv }}</strong> telah berhasil dibuat untuk transaksi dengan
                ID <strong>{{ $invoice->transaksi->idtransaksi }}</strong>.</p>

            <div class="invoice-details">
                <h3>Detail Invoice:</h3>
                <ul>
                    <li><strong>Customer:</strong>
                        {{ $invoice->transaksi->user->user_detail->nama ?? $invoice->transaksi->user->username }}</li>
                    <li><strong>Email Customer:</strong> {{ $invoice->transaksi->user->email }}</li>
                    <li><strong>Total:</strong> {{ $invoice->transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}
                        {{ number_format($invoice->transaksi->total, 2, ',', '.') }}</li>
                    <li><strong>Kode PO:</strong> {{ $invoice->transaksi->kode_po }}</li>
                    <li><strong>Tanggal:</strong> {{ $invoice->created_at->format('d M Y H:i') }}</li>
                </ul>
            </div>

            <p>Silakan proses invoice ini lebih lanjut.</p>

            <div class="footer">
                <p>Terima kasih,</p>
                <p>JAYA NIAGA SEMESTA</p>
            </div>
        </div>
    </div>
@endsection
