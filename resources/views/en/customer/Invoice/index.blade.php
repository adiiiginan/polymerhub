<div class="space-y-6">
    @forelse ($invoices as $invoice)
        @php $order = $invoice->transaksi; @endphp
        <div class="border border-blue-200 rounded-lg p-4 bg-blue-50">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="flex items-center space-x-2 mb-1">
                        <span
                            class="font-semibold text-gray-900">#{{ $order->order_number ?? $invoice->kode_inv }}</span>
                        <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded-full">
                            <i class="fas fa-file-invoice mr-1"></i>Invoice Issued
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">
                        Invoice Date: {{ $invoice->created_at->format('d F Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">
                        Rp {{ number_format($invoice->total, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Status:
                        @if ($invoice->status == 9)
                            <span class="font-medium text-yellow-600">Unpaid</span>
                        @elseif ($invoice->status == 10)
                            <span class="font-medium text-green-600">Paid</span>
                        @else
                            <span class="font-medium text-red-600">Cancelled</span>
                        @endif
                    </p>
                </div>
            </div>

            @if ($order && $order->details)
                <div class="text-sm text-gray-700 mb-4">
                    <p>
                        @if ($order->details->count() > 0)
                            @foreach ($order->details->take(2) as $item)
                                {{ $item->produk->nama_produk }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                            @if ($order->details->count() > 2)
                                dan {{ $order->details->count() - 2 }} item lainnya
                            @endif
                        @endif
                    </p>
                </div>
            @endif

            <div class="flex justify-between items-center">
                @if ($order)
                    <a href="#" class="bg-gray-600 text-white px-4 py-1 rounded text-sm hover:bg-gray-700">
                        Lihat Detail Pesanan
                    </a>
                @else
                    <span></span>
                @endif
                <a href="{{ route('frontend.invoices.download', $invoice->id) }}"
                    class="text-green-600 hover:text-green-700 text-sm font-medium">
                    <i class="fas fa-download mr-1"></i>Download Invoice
                </a>
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-file-invoice text-4xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Invoice</h3>
            <p class="text-gray-600 mb-6">Tidak ada invoice yang diterbitkan untuk pesanan Anda.</p>
        </div>
    @endforelse
</div>
