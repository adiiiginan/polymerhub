<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - PT. Jaya Niaga Semesta</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 2px solid #333;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 15px;
        }

        .company-info h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-info p {
            margin-bottom: 2px;
            font-size: 11px;
        }

        .logo {
            width: 180px;
            height: 180px;

            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 24px;
            color: #666;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .customer-info {
            flex: 1;
            margin-right: 50px;
        }

        .customer-box {
            border: 2px solid #333;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 10px;
            font-size: 12px;
            line-height: 1.6;
        }

        .info-row {
            display: flex;
            margin-bottom: 4px;
        }

        .info-label {
            width: 70px;
            font-weight: bold;
        }

        .info-value {
            flex: 1;
        }

        .payment-info {
            flex: 1;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #333;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
            font-size: 11px;
        }

        .items-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .items-table .desc-col {
            text-align: left;
            width: 40%;
        }

        .totals {
            float: right;
            width: 300px;
            margin-bottom: 30px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            padding: 3px 0;
        }

        .total-row.grand-total {
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
            font-weight: bold;
            padding: 8px 0;
        }

        .signature-section {
            clear: both;
            text-align: center;
            margin-top: 50px;
        }

        .signature-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .signature-company {
            font-size: 14px;
            color: #4A90E2;
            font-weight: bold;
            margin-bottom: 50px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin: 0 auto 10px;
        }

        .signature-label {
            font-size: 12px;
            font-weight: bold;
        }

        .currency {
            text-align: right;
        }

        @media print {

            body {
                background: white;
                padding: 0;
            }

            .invoice-container {
                border: none;
                box-shadow: none;
                margin: 0;
                padding: 20px;
                page-break-inside: avoid;
            }

            .totals {
                float: none !important;
                width: 300px;
                margin: 20px 0 40px auto !important;
            }

            .signature-section {
                clear: both;
                page-break-inside: avoid;
                text-align: center;
                margin-top: 80px !important;
                margin-bottom: 0 !important;
            }

            .signature-company img {
                display: block;
                margin: 0 auto -35px auto !important;
                width: 120px !important;
            }

            .signature-line {
                width: 200px !important;
                border-bottom: 1px solid #000;
                margin: 50px auto 10px auto !important;
            }

            .signature-label {
                margin-top: 5px !important;
                text-align: center !important;
                font-weight: bold;
            }

            /* Hindari potongan halaman aneh */
            .items-table,
            .totals,
            .signature-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info"style="    margin-top: 60px;">
                <h1>PT. JAYA NIAGA SEMESTA</h1>
                <p>Taman Kopo Indah V Ruko Soho Sommerville No.51 Bandung 40218</p>
                <p>Tel : +6222 54438330, +62878 2330 9818/+62 813 2184 0775</p>
                <p>Email : info@jns.co.id&nbsp;&nbsp;&nbsp;Website : www.jns.co.id</p>
            </div>
            <div class="logo"><img src="{{ asset('backend/assets/media/logos/logo.png') }}" alt="Logo" width="180"
                    height="180"></div>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="font-size: 24px; font-weight: bold; text-decoration: underline;">INVOICE</h2>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="customer-info">
                <div class="customer-box">
                    <div class="info-row">
                        <span class="info-label">Kepada</span>
                        <span class="info-value">:
                            <b>{{ $invoice->transaksi->user->userDetail->nama ?? 'Nama Pelanggan' }}
                                {{ $invoice->transaksi->user->userDetail->nama_lengkap ?? '' }}</b></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Alamat</span>
                        <span class="info-value">:
                            {{ $invoice->transaksi->user->userDetail->alamat ?? 'Alamat Pelanggan' }}
                        </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tel.</span>
                        <span class="info-value">: {{ $invoice->transaksi->user->userDetail->no_hp ?? '' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Fax</span>
                        <span class="info-value">:</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">NPWP</span>
                        <span class="info-value">: {{ $invoice->transaksi->user->userDetail->vat ?? '' }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-label">PO No</span>
                    <span class="info-value">: {{ $invoice->transaksi->kode_po ?? '' }}</span>
                </div>
            </div>

            <div class="payment-info">
                <div class="info-row">
                    <span class="info-label">Invoice No</span>
                    <span class="info-value">: {{ $invoice->kode_inv }}
                        @if ($invoice->transaksi->is_request == 1)
                            <span style="color: red; font-weight: bold;">(REQUEST)</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal</span>
                    <span class="info-value">: {{ $invoice->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Pembayaran</span>
                    <span class="info-value">: DEBIT</span>
                </div>
                <br>


                <div class="info-row">
                    <span class="info-label">Nama Acc</span>
                    <span class="info-value">: PT. JAYA NIAGA SEMESTA</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Acc. No</span>
                    <span class="info-value">: 0128-0000-2177 (USD)</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Swift Code</span>
                    <span class="info-value">: NISPIDJ1BDG</span>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>PRODUCT NO.</th>
                    <th class="desc-col">DESKRIPSI</th>
                    <th>QTY</th>
                    <th>Unit PRICE</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->transaksi->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->produk->kode_produk ?? '' }}</td>
                        <td class="desc-col">{{ $detail->produk->nama_produk ?? '' }}
                            <div class="text-sm text-gray-500">
                                @if ($detail->jenis)
                                    <p>Shape: {{ $detail->jenis->jenis }}</p>
                                @endif
                                @if ($detail->ukuran)
                                    <p>Ukuran: {{ $detail->ukuran->nama_ukuran }}</p>
                                @endif
                            </div>
                        </td>
                        <td>{{ $detail->qty }} {{ $detail->produk->satuan ?? '' }}</td>
                        <td class="currency">
                            {{ $invoice->transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}{{ number_format($detail->harga, 2, ',', '.') }}
                        </td>
                        <td class="currency">
                            {{ $invoice->transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}{{ number_format($detail->qty * $detail->harga, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $subtotal = $invoice->transaksi->details->sum(function ($detail) {
                return $detail->qty * $detail->harga;
            });
            $shipping_cost = $invoice->transaksi->shipping_cost ?? 0;
            $total = $subtotal + $shipping_cost;
        @endphp
        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Sub Total :</span>
                <span>{{ $invoice->transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}{{ number_format($subtotal, 2, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Expedition Cost :</span>
                <span>{{ $invoice->transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}{{ number_format($shipping_cost, 2, ',', '.') }}</span>
            </div>

            <div class="total-row grand-total">
                <span>TOTAL INV :</span>
                <span>{{ $invoice->transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}{{ number_format($total, 2, ',', '.') }}</span>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section"style="
    margin: 0px 36px 16px 477px">
            <div class="signature-title">PT. JAYA NIAGA SEMESTA</div>
            <div class="signature-company">
                <img src="{{ asset('backend/assets/media/logos/logos/invo2.png') }}" alt="Invoice Signature"
                    style="width: 120px;margin-bottom: -46px;">
            </div>
            <div class="signature-line"></div>
            <div class="signature-label">Rifa Hasna</div>
        </div>
    </div>
</body>

</html>
