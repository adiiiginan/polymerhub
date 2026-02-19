<!DOCTYPE html>
<html>

<head>
    <title>Commercial Invoice</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10px;
            color: #333;
        }

        .letterhead {
            overflow: auto;
            margin-bottom: 20px;
        }

        .logo-container {
            float: left;
            width: 50%;
        }

        .logo-container img {
            max-width: 150px;
        }

        .company-details {
            float: right;
            width: 45%;
            text-align: right;
        }

        .company-details p {
            margin: 0;
            line-height: 1.4;
        }

        .invoice-details {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 10px;
        }

        .invoice-info-table {
            width: auto;
            /* Let table size itself */
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .company-details .invoice-info-table {
            margin-left: auto;
            /* Align table to the right in the company details section */
        }

        .label-cell,
        .value-cell {
            padding: 5px;
        }

        .label-cell {
            text-align: right;
        }

        .logo-container .label-cell {
            text-align: left;
            /* Align labels to the left under the logo */
        }

        .value-cell {
            text-align: left;
            padding-left: 10px;
        }

        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .address-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .address-table th,
        .address-table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
            width: 50%;
        }

        .address-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .items-table .text-right {
            text-align: right;
        }

        .items-table .text-center {
            text-align: center;
        }

        .totals-table {
            width: 40%;
            border-collapse: collapse;
            margin-left: auto;
            margin-bottom: 20px;
        }

        .totals-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .totals-table .label {
            font-weight: bold;
        }

        .totals-table .value {
            text-align: right;
        }

        .footer-notes {
            margin-top: 30px;
            font-size: 9px;
        }

        .footer-notes p {
            margin: 0;
            line-height: 1.4;
        }

        .signature-section {
            margin-top: 40px;
            text-align: right;
        }

        .signature-image img {
            max-width: 150px;
            margin-bottom: -40px;
            margin-right: 50px;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 250px;
            margin-top: 50px;
            display: inline-block;
        }

        .signature-title {
            margin-top: 5px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="letterhead">
        <div class="logo-container">
            <img src="{{ public_path('backend/assets/media/logos/logo.png') }}" alt="logo"
                style="margin-bottom: 10px;">
            <table class="invoice-info-table">
                <tr>
                    <td class="label-cell"><strong>Date:</strong></td>
                    <td class="value-cell">{{ $invoice->issue_date }}</td>
                </tr>
                <tr>
                    <td class="label-cell"><strong>AWB No:</strong></td>
                    <td class="value-cell">{{ $invoice->tracking_number ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        <div class="company-details">
            <table class="invoice-info-table">
                <tr>
                    <td class="label-cell"><strong>Invoice No:</strong></td>
                    <td class="value-cell">{{ $invoice->invoice_number }}</td>
                </tr>
            </table>
            <p><strong>{{ config('services.fedex.shipper.company') }}</strong></p>
            <p>{{ config('services.fedex.shipper.address') }}</p>
            <p>{{ config('services.fedex.shipper.city') }}, {{ config('services.fedex.shipper.state') }}
                {{ config('services.fedex.shipper.postal_code') }}</p>
            <p>Phone: {{ config('services.fedex.shipper.phone') }}</p>
            <p>Email: {{ config('services.fedex.shipper.email') }}</p>
        </div>
    </div>
    <div class="invoice-details">
        <h1 class="invoice-title">COMMERCIAL INVOICE</h1>
    </div>
    <div class="section-title">SHIPPER</div>


    <table class="items-table">
        <thead>
            <tr>
                <th>Qty</th>
                <th>Unit</th>
                <th>Full Description of Goods</th>
                <th>Harmonized Code</th>
                <th>Country of Origin</th>
                <th class="text-right">Unit Value</th>
                <th class="text-right">Total Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-center">{{ $item->unit }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">{{ $item->harmonized_code }}</td>
                    <td class="text-center">{{ $item->country_of_origin }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td class="label">Total Invoice Value</td>
            <td class="value">{{ number_format($invoice->total_invoice_value, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Currency</td>
            <td class="value">{{ $invoice->currency }}</td>
        </tr>
    </table>

    <div class="footer-notes">
        <p>I/We hereby certify that the information on this Invoice is true and correct and that the contents of this
            shipment are as stated above.</p>
    </div>

    <div class="signature-section">
        <div class="signature-image">
            <img src="{{ public_path('backend/assets/media/logos/ttd.png') }}" alt="signature">
        </div>
        <div class="signature-line"></div>
        <div class="signature-title">
            {{ config('services.fedex.shipper.name') }}<br>
            Signature of Shipper/Exporter
        </div>
    </div>

</body>

</html>
