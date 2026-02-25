<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print STT - {{ $invoice }}</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .container {
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            border: 1px solid #ccc;
            padding: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Print Air Waybill (AWB/STT)</h1>
            <p>Invoice: {{ $invoice }} | Client: {{ $client }}</p>
        </div>
        <div class="content">
            {{-- The $data variable will contain the HTML or content from the Lion Parcel API response --}}
            {!! $data !!}
        </div>
    </div>
</body>

</html>
