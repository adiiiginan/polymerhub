<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Opname</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .pdf-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .pdf-toolbar {
            background: #333;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .pdf-toolbar h3 {
            flex: 1;
            font-size: 16px;
        }

        .toolbar-buttons {
            display: flex;
            gap: 10px;
        }

        .toolbar-btn {
            background: #555;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.3s;
        }

        .toolbar-btn:hover {
            background: #666;
        }

        .document-content {
            padding: 40px;
            line-height: 1.4;
            font-size: 12px;
        }

        /* Header Section */
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: #c41e3a;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
            margin-right: 20px;
            border: 3px solid #999;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-tagline {
            color: #0066cc;
            font-style: italic;
            margin-bottom: 10px;
        }

        .company-address {
            font-size: 11px;
            color: #666;
            line-height: 1.3;
        }

        /* Document Info */
        .doc-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .doc-number {
            font-weight: bold;
        }

        .doc-date {
            font-weight: bold;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        .items-table .center {
            text-align: center;
        }

        .items-table .right {
            text-align: right;
        }

        /* Footer */
        .footer {
            text-align: right;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 10px;
            margin-right: 47px;
        }

        .signature-title {
            font-size: 11px;
            color: #666;
            margin-right: 39px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .pdf-toolbar {
                display: none;
            }

            .pdf-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <div class="pdf-container">
        <div class="pdf-toolbar">
            <h3>Laporan Stok Opname</h3>
            <div class="toolbar-buttons">
                <button class="toolbar-btn" onclick="window.print();">Cetak</button>
            </div>
        </div>
        <div class="document-content" id="document-content">
            <!-- Header -->
            <div class="header">
                <img src="{{ asset('backend/assets/media/logos/logo.png') }}" alt="Logo"
                    style="max-height: 77px;margin-left: 53px;margin-top: -21px;" />
                <div class="company-info" style="text-align : center;margin-left: -69px;">
                    <div class="company-name" style="margin-left: -56px;">PT. JAYA NIAGA SEMESTA</div>
                    <div class="company-tagline" style="margin-left: -59px;"><b>Safety &amp; Industrial Equipment</b>
                    </div>
                    <div class="company-address" style="margin-left: -51px;">
                        <b> Taman Kopo Indah V Ruko Soho Sommerville No.51 Bandung 40218<br>
                            +6222 54438330, +62878 2330 9818/+62 813 2184 0775 Email : info@jns.co.id Website :
                            www.jns.co.id</b>
                    </div>
                </div>
            </div>

            <!-- Document Info -->
            <div class="doc-info">
                <div>
                    <strong>No :</strong> <span class="doc-number">{{ $stokOpname->kode_opname }}</span>
                </div>
                <div>
                    <strong>Tanggal :</strong> <span
                        class="doc-date">{{ \Carbon\Carbon::parse($stokOpname->tanggal_mulai)->format('d-m-Y') }} s/d
                        {{ \Carbon\Carbon::parse($stokOpname->tanggal_selesai)->format('d-m-Y') }}</span>
                </div>
            </div>

            <p><strong>Keterangan:</strong> {{ $stokOpname->keterangan }}</p>
            <hr>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Ukuran</th>
                        <th>Stok Sistem</th>
                        <th>Stok Fisik</th>
                        <th>Selisih</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stokOpname->details as $index => $detail)
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td>{{ $detail->produkStok->produk->nama_produk }}</td>
                            <td>{{ $detail->produkStok->ukuran->nama_ukuran }}</td>
                            <td class="center">{{ $detail->stok_sistem }}</td>
                            <td class="center">{{ $detail->stok_fisik }}</td>
                            <td class="center">{{ $detail->stok_fisik - $detail->stok_sistem }}</td>
                            <td>{{ $detail->catatan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Footer -->
            <div class="footer">
                <div class="company-footer">PT. JAYA NIAGA SEMESTA</div>
                <div class="signature"><img src="{{ asset('backend/assets/media/logos/ttd-ayu.png') }}"style="
                margin-right: 32px;margin-top: -6px;width: 12%; "></div>
                <div class="signature-name">Ayu Rindy</div>
                <div class="signature-title">Sales Operation</div>
            </div>
        </div>
    </div>
</body>

</html>
