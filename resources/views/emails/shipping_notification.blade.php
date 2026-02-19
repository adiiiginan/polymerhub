@extends('layouts.email')

@section('content')
    <div class="container">
        <div class="header">
            <h1>Pengiriman Baru Telah Dibuat</h1>
        </div>

        <div class="content">
            <p>Halo,</p>

            <p>Pengiriman baru telah dibuat untuk invoice berikut:</p>

            <div class="invoice-details">
                <h3>Detail Invoice:</h3>
                <ul>
                    <li><strong>Kode Invoice:</strong> {{ $invoice->kode_inv }}</li>
                    <li><strong>Kode Pengiriman:</strong> {{ $proses->kode_ship ?? 'N/A' }}</li>
                    <li><strong>Customer:</strong> {{ $customer->userDetail->nama ?? $customer->username }}
                        ({{ $customer->email }})</li>
                    <li><strong>Tanggal Pengiriman:</strong>
                        {{ $proses->created_at->format('d-m-Y H:i') ?? now()->format('d-m-Y H:i') }}</li>
                    <li><strong>Status:</strong> Dikirim</li>
                </ul>
            </div>

            <p>Silakan periksa sistem untuk detail lebih lanjut.</p>

            <div class="footer">
                <p>Terima kasih,</p>
                <p>JAYA NIAGA SEMESTA</p>
            </div>
        </div>
    </div>
@endsection
