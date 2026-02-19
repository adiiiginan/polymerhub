@extends('layouts.email')

@section('content')
    <div class="container">
        <div class="header">
            <h1>Pesanan Telah Disetujui</h1>
        </div>

        <div class="content">
            <p>Hai Admin,</p>

            <p>Pesanan dengan ID Transaksi <strong>{{ $transaksi->idtransaksi }}</strong> telah berhasil disetujui.</p>

            <div class="invoice-details">
                <h3>Detail Pesanan:</h3>
                <ul>
                    <li><strong>Customer:</strong> {{ $transaksi->user->userDetail->nama ?? $transaksi->user->username }}
                    </li>
                    <li><strong>Email Customer:</strong> {{ $transaksi->user->email }}</li>
                    <li><strong>Total:</strong> Rp {{ number_format($transaksi->total, 2, ',', '.') }}</li>
                    <li><strong>Kode PO:</strong> {{ $transaksi->kode_po }}</li>
                    <li><strong>Tanggal:</strong> {{ $transaksi->created_at->format('d M Y H:i') }}</li>
                </ul>
            </div>

            <p>Surat PO telah dibuat dan siap untuk diproses lebih lanjut.</p>

            <div class="footer">
                <p>Terima kasih,</p>
                <p>JAYA NIAGA SEMESTA</p>
            </div>
        </div>
    </div>
@endsection
