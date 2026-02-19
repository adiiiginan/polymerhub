<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan & Resi - PT. JAYA NIAGA SEMESTA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            color: #000;
        }

        .surat-jalan-container {
            max-width: 800px;
            margin: 0 auto 40px;
            background: white;
            border: 2px solid #000;
            padding: 0;
        }

        /* Header */
        .header {
            border-bottom: 2px solid #000;
            padding: 20px;
            position: relative;
        }

        .logo-section {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            border: 2px solid #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            background: #f9f9f9;
            flex-shrink: 0;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .company-address {
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin-bottom: 5px;
        }

        .company-contact {
            font-size: 11px;
            color: #333;
            line-height: 1.3;
        }

        .document-title {
            position: absolute;
            top: 20px;
            right: 20px;
            text-align: right;
        }

        .title-text {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        /* Content */
        .content {
            padding: 20px;
            position: relative;
        }

        .recipient-section {
            margin-bottom: 30px;
        }

        .document-details {
            position: absolute;
            top: 0;
            right: 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            margin-bottom: 3px;
            font-size: 12px;
        }

        .detail-label {
            width: 60px;
            font-weight: normal;
        }

        .detail-colon {
            width: 15px;
            text-align: center;
        }

        .detail-value {
            font-weight: bold;
            flex: 1;
        }

        .recipient-text {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .recipient-name {
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        .recipient-address {
            font-size: 11px;
            line-height: 1.3;
            margin-top: 3px;
            max-width: 400px;
        }

        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            border: 2px solid #000;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: center;
            font-size: 11px;
            vertical-align: middle;
        }

        .items-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .items-table .nama-barang {
            text-align: left;
            width: 40%;
        }

        .items-table .qty {
            width: 10%;
        }

        .items-table .unit {
            width: 15%;
        }

        .items-table .berat {
            width: 15%;
        }

        .items-table .keterangan {
            width: 20%;
            text-align: left;
        }

        .total-row {
            font-weight: bold;
            background: #f8f8f8;
        }



        /* --- Receipt Styles --- */
        .receipt-container {
            background: white;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 50px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            page-break-before: always;
        }

        .receipt-container .header {
            border-bottom: none;
            padding: 0;
            position: static;
            margin-bottom: 30px;
        }

        .kepada {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }

        .receipt-container .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin-bottom: 25px;
            text-transform: none;
        }

        .attn-section {
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .attn-label {
            font-weight: bold;
            font-size: 14px;
        }

        .receipt-container .address {
            font-size: 13px;
            color: #333;
            line-height: 1.7;
            max-width: none;
        }

        .po-section {
            margin: 30px 0;
            padding: 15px 0;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }

        .po-label {
            font-size: 13px;
            color: #666;
            display: inline-block;
            width: 80px;
        }

        .po-number {
            font-weight: bold;
            color: #000;
            background: #ffeb3b;
            padding: 2px 8px;
            display: inline-block;
        }

        .sip-section {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px solid #000;
        }

        .sip-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }

        .sip-company {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin-bottom: 10px;
        }

        .spacer {
            height: 20px;
        }

            {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            color: #000;
        }

        .surat-jalan-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 2px solid #000;
            padding: 0;
        }

        /* Header */
        .header {
            border-bottom: 2px solid #000;
            padding: 20px;
            position: relative;
        }

        .logo-section {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            border: 2px solid #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            background: #f9f9f9;
            flex-shrink: 0;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .company-address {
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin-bottom: 5px;
        }

        .company-contact {
            font-size: 11px;
            color: #333;
            line-height: 1.3;
        }

        .document-title {
            position: absolute;
            top: 20px;
            right: 20px;
            text-align: right;
        }

        .title-text {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        /* Content */
        .content {
            padding: 20px;
        }

        .recipient-section {
            margin-bottom: 30px;
        }

        .document-details {
            float: right;
            text-align: left;
            margin-top: 15px;
            margin-right: 24px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 3px;
            font-size: 12px;
        }

        .detail-label {
            width: 60px;
            font-weight: normal;
        }

        .detail-colon {
            width: 15px;
            text-align: center;
        }

        .detail-value {
            font-weight: bold;
            flex: 1;
        }

        .recipient-text {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .recipient-name {
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        .recipient-address {
            font-size: 11px;
            line-height: 1.3;
            margin-top: 3px;
            max-width: 400px;
        }

        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            border: 2px solid #000;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: center;
            font-size: 11px;
            vertical-align: middle;
        }

        .items-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .items-table .nama-barang {
            text-align: left;
            width: 40%;
        }

        .items-table .qty {
            width: 10%;
        }

        .items-table .unit {
            width: 15%;
        }

        .items-table .berat {
            width: 15%;
        }

        .items-table .keterangan {
            width: 20%;
            text-align: left;
        }

        .total-row {
            font-weight: bold;
            background: #f8f8f8;
        }

        /* Notes */
        .notes-section {
            margin: 20px 0;
            font-size: 11px;
            font-style: italic;
        }

        /* Signatures */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding: 0 20px;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-title {
            font-size: 11px;
            margin-bottom: 60px;
        }

        .signature-line {
            border-top: 1px solid #000;
            padding-top: 5px;
            position: relative;
        }

        .signature-name {
            font-size: 11px;
            font-weight: bold;
            color: #0066cc;
        }

        .signature-stamp {
            position: absolute;
            right: -30px;
            top: -40px;
            transform: rotate(-15deg);
            font-size: 8px;
            color: #666;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .surat-jalan-container {
                border: 1px solid #000;
                max-width: none;
                margin: 0;
            }

            .print-btn {
                display: none;
            }
        }

        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #0066cc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            z-index: 1000;
        }

        .print-btn:hover {
            background: #0052a3;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 15px;
            }

            .logo-section {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 15px;
            }

            .document-title {
                position: static;
                text-align: center;
                margin-top: 15px;
            }

            .document-details {
                float: none;
                margin-top: 26px;
                margin-right: 23px;
            }

            .signatures {
                flex-direction: column;
                align-items: center;
                gap: 30px;
            }

            .items-table {
                font-size: 10px;
            }

            .items-table th,
            .items-table td {
                padding: 6px 4px;
            }
        }
    </style>
</head>

<body>
    <div class="surat-jalan-container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo">
                    <img src="{{ asset('backend/assets/media/logos/logo.png') }}" alt="Logo"
                        style="max-height: 87px;margin-left: 2px;margin-top: -2px;" />
                </div>
                <div class="company-info">
                    <div class="company-name">PT. JAYA NIAGA SEMESTA</div>
                    <div class="company-address">
                        Taman Kopo Indah V Ruko Soho Summerville No.51 Bandung 40218
                    </div>
                    <div class="company-contact">
                        +6222 54438330, +62878 2330 9818/+62 813 2184 0775 Email :
                        info@jns.co.id&nbsp;&nbsp;&nbsp;Website : www.jns.co.id
                    </div>
                </div>
            </div>

            <div class="document-title">
                <div class="title-text">SURAT JALAN</div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Recipient and Document Details -->
            <div class="recipient-section">
                <div class="recipient-text">Kepada Yth,</div>
                <div class="recipient-name">{{ $proses->transaksi->user->user_detail->nama ?? 'Nama tidak tersedia' }}
                </div>
                <div class="recipient-address">
                    {{ $proses->transaksi->user->user_detail->alamat ?? 'Alamat tidak tersedia' }}
                </div>

                <div class="document-details">
                    <div class="detail-row">
                        <span class="detail-label">Tanggal</span>
                        <span class="detail-colon">:</span>
                        <span class="detail-value">{{ $today }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">No.</span>
                        <span class="detail-colon">:</span>
                        <span class="detail-value">{{ $proses->kode_ship }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">No.PO</span>
                        <span class="detail-colon">:</span>
                        <span class="detail-value">{{ $proses->transaksi->kode_po }}</span>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="nama-barang">NAMA BARANG</th>
                        <th class="qty">QTY</th>
                        <th class="unit">UNIT</th>
                        <th class="harga">HARGA</th>
                        <th class="subtotal">SUBTOTAL</th>
                        <th class="keterangan">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalQty = 0;
                        $totalSubtotal = 0;
                    @endphp
                    @foreach ($proses->transaksi->details as $detail)
                        <tr>
                            <td class="nama-barang">{{ $detail->produk->nama_produk }}
                                <div class="text-sm text-gray-500">
                                    @if ($detail->jenis)
                                        <p>Shape: {{ $detail->jenis->jenis }}</p>
                                    @endif
                                    @if ($detail->ukuran)
                                        <p>Ukuran: {{ $detail->ukuran->nama_ukuran }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="qty">{{ $detail->qty }}</td>
                            <td class="unit">{{ $detail->produk->satuan }}</td>
                            <td class="harga">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="subtotal">Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}
                            </td>
                            <td class="keterangan"></td>
                        </tr>
                        @php
                            $totalQty += $detail->qty;
                            $totalSubtotal += $detail->qty * $detail->harga;
                        @endphp
                    @endforeach
                    <tr class="total-row">
                        <td class="nama-barang"><strong>TOTAL</strong></td>
                        <td class="qty"><strong>{{ $totalQty }}</strong></td>
                        <td class="unit"><strong>-</strong></td>
                        <td class="harga"></td>
                        <td class="subtotal"><strong>Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</strong></td>
                        <td class="keterangan"><strong>-</strong></td>
                    </tr>
                </tbody>
            </table>

            <!-- Notes -->
            <div class="notes-section">
                <strong>BARANG SUDAH DITERIMA DENGAN BAIK DAN CUKUP</strong><br>
                (tanda tangan dan stempel perusahaan)
            </div>

            <!-- Signatures -->
            <div class="signatures">
                <div class="signature-box">
                    <div class="signature-title">Penerima / Pembeli</div>
                    <div class="signature-line">
                        <div style="height: 40px;"></div>
                        <div>____________________</div>
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-title">Pengirim,</div>
                    <div class="signature-line">
                        <div class="signature-name">Ayu Rindy</div>
                        <div class="signature-stamp">
                            <div class="signature"><img
                                    src="{{ asset('backend/assets/media/logos/ttd-ayu.png') }}"style="
                margin-right: 32px;margin-top: -56px;width: 42%; "></div>
                        </div>
                        <div style="margin-top: 10px;">____________________</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="receipt-container">
        <div class="header">
            <div class="kepada">KEPADA YTH:</div>
            <div class="company-name">
                {{ $proses->transaksi->user->user_detail->perusahaan ?? 'Nama Perusahaan tidak tersedia' }}
            </div>

            <div class="attn-section">
                <div class="attn-label">Attn: <span
                        style="font-weight:normal;">{{ $proses->transaksi->user->user_detail->nama ?? 'Nama Kontak tidak tersedia' }}</span>
                </div>
                <div class="spacer"></div>
                <div class="address">
                    {!! nl2br(e($proses->transaksi->user->user_detail->alamat ?? 'Alamat tidak tersedia')) !!}<br>
                    PHONE : {{ $proses->transaksi->user->user_detail->no_hp ?? 'No. HP tidak tersedia' }}
                </div>
            </div>
        </div>

        <div class="po-section">
            <span class="po-label">PO NO:</span>
            <span class="po-number">{{ $proses->transaksi->kode_po ?? 'N/A' }}</span>
        </div>

        <div class="sip-section">
            <div class="sip-label">S/P :</div>
            <div class="sip-company">PT. JAYA NIAGA SEMESTA</div>
            <div class="address">
                Jl. Raya Taman Kopo Indah V<br>
                Ruko Soho Sommerville No. 51<br>
                Bandung – West Java<br>
                Phone : +62 22 54438330 / +62 812 2101 3603
            </div>
        </div>
    </div>
</body>

</html>
