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
            width: 80px;
            height: 80px;

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
            <div class="company-info">
                <h1>PT. JAYA NIAGA SEMESTA</h1>
                <p>Taman Kopo Indah V Ruko Soho Sommerville No.51 Bandung 40218</p>
                <p>Tel : +6222 54438330, +62878 2330 9818/+62 813 2184 0775</p>
                <p>Email : info@jns.co.id&nbsp;&nbsp;&nbsp;Website : www.jns.co.id</p>
            </div>
            <div class="logo"><img src="{{ asset('backend/assets/media/logos/logo.png') }}" alt="Logo" width="80"
                    height="80"></div>
        </div>
