@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="container mx-auto py-8">
        @if (session('success'))
            <div class="bg-green-200 text-green-800 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Purchase Order Design -->
        <div class="bg-white shadow-lg rounded-lg p-8 mb-6">
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">

                <div class="text-right flex-1">
                    <div class="text-2xl font-bold mb-3">Waiting for Payment</div>
                    <div class="mb-1"><strong>No:</strong> {{ $transaksi->idtransaksi }}</div>
                    <div><strong>Date:</strong> {{ $transaksi->created_at->format('d-M-Y') }}</div>
                    {{-- Notice Pembayaran --}}

                </div>

            </div>

            <br>
            <!-- Address Section -->
            <div class="flex gap-6 mb-6">
                <div class="flex-1 border border-gray-300 p-4 bg-gray-50 rounded">
                    <div class="font-bold mb-2 text-gray-700">Customer:</div>
                    <div class="font-semibold">
                        {{ $primaryAddress->perusahaan ?? ($transaksi->user->userDetail->perusahaan ?? 'Customer Name') }}
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        {{ $primaryAddress->email ?? ($transaksi->user->userDetail->email ?? 'customer@email.com') }}</div>
                    <div class="text-sm text-gray-600">
                        {{ $primaryAddress->alamat ?? ($transaksi->user->detail->alamat ?? 'Customer Address') }}
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $primaryAddress->no_hp ?? ($transaksi->user->detail->no_hp ?? 'Phone Number') }}</div>
                </div>
                <div class="flex-1 border border-gray-300 p-4 bg-gray-50 rounded">
                    <div class="font-bold mb-2 text-gray-700">Attn:</div>
                    <div>{{ $primaryAddress->nama ?? 'Customer Name' }}</div>
                    <br>
                    <div class="font-bold mb-1 text-gray-700">Subject:</div>
                    <div>Waiting for Payment</div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto mb-6">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 p-3 text-center font-bold w-12">No</th>
                            <th class="border border-gray-300 p-3 text-center font-bold w-32">Product No.</th>
                            <th class="border border-gray-300 p-3 text-left font-bold">Description</th>
                            <th class="border border-gray-300 p-3 text-center font-bold w-20">Quantity</th>
                            <th class="border border-gray-300 p-3 text-center font-bold w-28">Unit Price</th>
                            <th class="border border-gray-300 p-3 text-center font-bold w-32">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi->details as $index => $item)
                            @if ($item->produk)
                                <tr>
                                    <td class="border border-gray-300 p-3 text-center">{{ $index + 1 }}</td>
                                    <td class="border border-gray-300 p-3">
                                        {{ $item->produk->kode_produk ?? 'PRD' . str_pad($item->produk->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="border border-gray-300 p-3">
                                        <div class="font-medium">{{ $item->produk->nama_produk }}</div>
                                        @if ($item->produk->deskripsi)
                                            <div class="text-sm text-gray-600 mt-1">
                                                {{ \Illuminate\Support\Str::limit($item->produk->deskripsi, 100) }}</div>
                                        @endif
                                        <div class="text-sm text-gray-500 mt-2">
                                            @if ($item->jenis)
                                                <span>Shape: {{ $item->jenis->jenis }}</span>
                                            @endif
                                            @if ($item->ukuran)
                                                <span class="ml-4">Size: {{ $item->ukuran->nama_ukuran }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="border border-gray-300 p-3 text-center">{{ $item->qty }}
                                        {{ $item->produk->satuan ?? 'pcs' }}</td>
                                    <td class="border border-gray-300 p-3 text-right">
                                        {{ number_format($item->harga ?? 0, 2, ',', '.') }}</td>
                                    <td class="border border-gray-300 p-3 text-right">Rp.
                                        {{ number_format(($item->harga ?? 0) * ($item->qty ?? 0), 2, ',', '.') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary Table -->
            <div class="flex justify-end mb-6">
                <table class="w-80 border-collapse border border-gray-300">
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 p-3 font-bold">Subtotal</td>
                            <td class="border border-gray-300 p-3 text-right">
                                Rp.
                                {{ number_format($transaksi->details->sum(function ($item) {return ($item->harga ?? 0) * ($item->qty ?? 0);}),2,',','.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-3 font-bold">Shipping ({{ $transaksi->shipping_service }})
                            </td>
                            <td class="border border-gray-300 p-3 text-right">
                                Rp.{{ number_format($transaksi->shipping_cost, 2, ',', '.') }}</td>
                        </tr>
                        @php
                            $subtotal = $transaksi->details->sum(function ($item) {
                                return ($item->harga ?? 0) * ($item->qty ?? 0);
                            });
                            $ppn = $subtotal * 0.11;
                            $total = $subtotal + $transaksi->shipping_cost + $ppn;
                        @endphp
                        <tr>
                            <td class="border border-gray-300 p-3 font-bold">PPN (11%)</td>
                            <td class="border border-gray-300 p-3 text-right">
                                Rp. {{ number_format($ppn, 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-3 font-bold bg-gray-100">Total</td>
                            <td class="border border-gray-300 p-3 text-right font-bold bg-gray-100">
                                Rp. {{ number_format($total, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>




            <!-- Informasi Pembayaran -->
            <div class="bg-gray-50 border border-gray-300 rounded-lg p-5 mb-8">
                <h4 class="text-lg font-bold text-gray-700 mb-4">💳 Payment Information</h4>

                <p class="text-gray-700 mb-4">Please make the payment to one of the following accounts:</p>

                <div class="space-y-1 text-sm text-gray-800">


                    <div class="info-row">
                        <span class="info-label font-semibold w-28 inline-block">Nama Acc</span>
                        <span class="info-value">: PT. JAYA NIAGA SEMESTA</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label font-semibold w-28 inline-block">Acc. No</span>
                        <span class="info-value">: 0128-0000-2177 (USD)</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label font-semibold w-28 inline-block">Swift Code</span>
                        <span class="info-value">: NISPIDJ1BDG</span>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mt-3">
                    <p class="text-gray-700 mb-1"><strong>Total to be paid:</strong></p>
                    <h3 class="text-xl font-bold text-green-700">
                        Rp. {{ number_format($total, 2, ',', '.') }}
                    </h3>

                    <p class="text-gray-700 mt-4">
                        After making the transfer, please confirm the payment to:
                    </p>
                    <ul class="list-disc list-inside text-gray-700">
                        <li>WhatsApp : <strong>+62 813-2184-0775</strong></li>
                        <li>Email: <strong>fitra@jns.co.id</strong></li>

                        <li>Delivery Time <strong>7-14 days from confirmation </strong></li>

                    </ul>

                    <p class="text-gray-600 mt-3 text-sm">
                        *Include proof of transfer and order number (<strong>{{ $transaksi->idtransaksi }}</strong>) when
                        confirming.
                    </p>


                </div>
            </div>


            <!-- Terms & Conditions -->


            <!-- Signature -->

        </div>

        <!-- Action Buttons -->


    </div>
    </div>
    </div>
    <!-- Print Styles -->
    <style>
        @media print {
            @page {
                size: A5;
                margin: 15mm;
            }

            body {
                font-size: 10pt;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .container {
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .bg-white {
                box-shadow: none !important;
            }

            .flex.gap-4.justify-center {
                display: none !important;
            }

            .bg-green-200,
            .bg-yellow-100,
            .bg-green-100 {
                background-color: #f0f0f0 !important;
                border: 1px solid #ccc !important;
            }
        }
    </style>
@endsection
