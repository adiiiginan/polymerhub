@extends('layouts.email')

@section('content')
    <div class="container">
        <div class="header">
            <h1>Pesanan #{{ $invoice->kode_inv }} Telah Dikirim</h1>
        </div>

        <div class="content">
            <p>Halo,</p>

            <p>Pesanan Anda telah dikirim. Berikut adalah detail pengiriman:</p>

            <div class="invoice-details">
                <h3>Detail Pengiriman:</h3>
                <ul>
                    <li><strong>Kode Invoice:</strong> {{ $invoice->kode_inv }}</li>
                    <li><strong>Kode Pengiriman:</strong> {{ $proses->kode_ship ?? 'N/A' }}</li>
                    <li><strong>Tanggal Pengiriman:</strong>
                        {{ $proses->created_at->format('d-m-Y H:i') ?? now()->format('d-m-Y H:i') }}</li>
                    <li><strong>Status:</strong> Dikirim</li>
                    @if ($invoice->transaksi->is_request == 1)
                        <li><strong>Jenis Pesanan:</strong> <span style="color: #007bff; font-weight: bold;">Custom
                                Order</span></li>
                    @endif
                </ul>
            </div>

            <p>Silakan pantau status pengiriman Anda. Jika ada pertanyaan, hubungi customer service kami.</p>

            <div class="footer">

                <p>JAYA NIAGA SEMESTA</p>
            </div>
        </div>
    </div>
@endsection
