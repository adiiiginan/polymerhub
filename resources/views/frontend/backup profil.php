@extends('layouts.app')

@section('title', 'Profil Saya')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-sm">
        <!-- Page Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-semibold text-gray-900">Profil Saya</h1>
            <p class="text-gray-600 mt-1">Kelola informasi akun dan pesanan Anda</p>
        </div>

        <!-- Tab Navigation -->
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <div class="px-6">
                <div class="flex space-x-8">
                    <button type="button" id="detail-tab" onclick="switchTab('detail')"
                        class="tab-btn active py-3 px-1 border-b-2 border-blue-600 text-blue-600 font-medium">
                        <i class="fas fa-user mr-2"></i>Detail User
                    </button>
                    <button type="button" id="history-tab" onclick="switchTab('history')"
                        class="tab-btn py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        <i class="fas fa-history mr-2"></i>Riwayat Pesanan
                    </button>
                    <button type="button" id="orders-tab" onclick="switchTab('orders')"
                        class="tab-btn py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        <i class="fas fa-shopping-bag mr-2"></i>Pesanan Saya
                        @if (isset($activeOrdersCount) && $activeOrdersCount > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ $activeOrdersCount }}
                        </span>
                        @endif
                    </button>

                    <!-- 🔹 Tab Baru PO -->
                    <button type="button" id="po-tab" onclick="switchTab('po')"
                        class="tab-btn py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        <i class="fas fa-file-invoice mr-2"></i>Order Confirmation
                        @if (isset($approvedPoOrders) && count($approvedPoOrders) > 0)
                        <span class="ml-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ count($approvedPoOrders) }}
                        </span>
                        @endif
                    </button>

                    <!-- 🔹 Tab Baru Invoice -->
                    <button type="button" id="invoice-tab" onclick="switchTab('invoice')"
                        class="tab-btn py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        <i class="fas fa-receipt mr-2"></i>Invoice Saya
                        @if (isset($invoices) && count($invoices) > 0)
                        <span class="ml-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ count($invoices) }}
                        </span>
                        @endif
                    </button>

                    <button type="button" id="custom-order-tab" onclick="switchTab('custom-order')"
                        class="tab-btn py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        <i class="fas fa-box-open mr-2"></i>Pesanan Custom
                        @if (isset($customOrders) && count($customOrders) > 0)
                        <span class="ml-2 bg-purple-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ count($customOrders) }}
                        </span>
                        @endif
                    </button>

                    <button type="button" id="edit-tab" onclick="switchTab('edit')"
                        class="tab-btn py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        <i class="fas fa-edit mr-2"></i>Edit Profile
                    </button>
                </div>
            </div>
        </div>


        <!-- Tab Content -->

        <!-- Detail User Tab -->
        <div id="detail-content" class="tab-content p-6">
            <div class="max-w-10xl">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Informasi Detail User</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Profile Picture & Basic Info -->
                    <div class="md:col-span-1">
                        <div class="text-center">
                            <div class="relative inline-block">
                                @if ($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Picture"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-blue-100">
                                @else
                                <div
                                    class="w-32 h-32 rounded-full bg-gray-200 border-4 border-blue-100 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400 text-4xl"></i>
                                </div>
                                @endif
                                <div
                                    class="absolute bottom-0 right-0 bg-green-500 w-6 h-6 rounded-full border-2 border-white">
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mt-4">
                                {{ $user->detail->nama ?? ($user->name ?? 'User') }}
                            </h3>
                            <p class="text-gray-600">Customer Premium</p>
                            <div class="flex items-center justify-center mt-2">
                                <div class="flex text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star"></i>
                                        @endfor
                                </div>
                                <span class="text-sm text-gray-600 ml-2">5.0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Information -->
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                        {{ $user->detail->nama ?? 'Belum diisi' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                        {{ $user->detail->telepon ?? 'Belum diisi' }}
                                    </p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                        {{ $user->detail && $user->detail->tanggal_lahir ? $user->detail->tanggal_lahir->format('d F Y') : 'Belum diisi' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Member Sejak</label>
                                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                        {{ $user->created_at->format('d F Y') }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Akun</label>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $user->detail->user_detail->alamat ?? 'Belum diisi' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <i class="fas fa-shopping-bag text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Pesanan</p>
                                <p class="text-2xl font-semibold text-blue-600">{{ $totalOrders ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-500 rounded-lg">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Pending</p>
                                <p class="text-2xl font-semibold text-yellow-600">{{ $pendingOrders ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-lg">
                                <i class="fas fa-file-invoice text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">PO</p>
                                <p class="text-2xl font-semibold text-green-600">{{ $poOrders ?? 0 }}</p>
                            </div>

                        </div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-lg">
                                <i class="fas fa-file-invoice text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Invoice</p>
                                <p class="text-2xl font-semibold text-green-600">{{ count($invoices) }}</p>
                            </div>

                        </div>
                    </div>



                </div>
            </div>
        </div>

        <!-- Riwayat Pesanan Tab -->
        <div id="history-content" class="tab-content p-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Riwayat Pesanan</h2>
                <div class="flex items-center space-x-4">
                    <select class="border border-gray-300 rounded-md px-3 py-2 text-sm" id="status-filter">
                        <option value="">Semua Status</option>
                        <option value="delivered">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                    <input type="date" class="border border-gray-300 rounded-md px-3 py-2 text-sm"
                        id="date-filter">
                </div>
            </div>

            <div class="space-y-4" id="history-orders">
                @forelse($completedOrdersHistory ?? [] as $order)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="flex items-cente` space-x-2 mb-1">
                                <span class="font-semibold text-gray-900">#{{ $order->order_number }}</span>
                                <span
                                    class="px-2 py-1 text-xs rounded-full border 
                                        @if ($order->status == 4) bg-green-50 text-green-700 border-green-200
                                        @else bg-red-50 text-red-700 border-red-200 @endif">
                                    @if ($order->status == 4)
                                    <i class="fas fa-check-circle mr-1"></i>Selesai
                                    @else
                                    <i class="fas fa-times-circle mr-1"></i>Dibatalkan
                                    @endif
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('d F Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">Rp
                                {{ number_format($order->total_amount, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                @if ($order->status == 4)
                                Diterima: {{ $order->updated_at->format('d F Y') }}
                                @else
                                Dibatalkan: {{ $order->updated_at->format('d F Y') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-700 mb-3">
                        <p>
                            @if ($order->items && $order->items->count() > 0)
                            @foreach ($order->items->take(2) as $item)
                            {{ $item->product_name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                            @if ($order->items->count() > 2)
                            dan {{ $order->items->count() - 2 }} item lainnya
                            @endif
                            @else
                            -
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $order->items->count() ?? 0 }} items • Ekspedisi:
                            {{ $order->shipping_method ?? 'JNE' }}
                        </p>
                    </div>
                    <div class="flex justify-between items-center">
                        @if ($order->status == 4)
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-star text-yellow-400"></i>
                            <span class="ml-1">Beri penilaian</span>
                        </div>
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Pesan Lagi
                            </button>
                            <a href="" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Lihat Detail
                            </a>
                        </div>
                        @else
                        <div class="flex-1"></div>
                        <a href="" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Lihat Detail
                        </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-history text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat Pesanan</h3>
                    <p class="text-gray-600 mb-6">Anda belum memiliki riwayat pesanan</p>
                    <a href=""
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Mulai Berbelanja
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Custom Order Tab -->
        <div id="custom-order-content" class="tab-content p-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Pesanan Custom Saya</h2>
                <div class="text-sm text-gray-600">
                    {{ count($customOrders) }} Pesanan Custom
                </div>
            </div>

            <div class="space-y-4">
                @forelse($customOrders as $order)
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="font-semibold text-gray-900">#{{ $order->order_number }}</span>
                                <span class="px-2 py-1 bg-purple-500 text-white text-xs rounded-full">
                                    <i class="fas fa-box-open mr-1"></i>Custom
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('d F Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">Rp
                                {{ number_format($order->total, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-600 font-medium">
                                {{ $order->statusRelasi->name ?? 'Status tidak diketahui' }}
                            </p>
                        </div>
                    </div>

                    <div class="text-sm text-gray-700 mb-4">
                        <p>
                            @if ($order->details && $order->details->count() > 0)
                            @foreach ($order->details->take(2) as $item)
                            {{ $item->produk->nama_produk ?? 'Produk tidak diketahui' }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                            @if ($order->details->count() > 2)
                            dan {{ $order->details->count() - 2 }} item lainnya
                            @endif
                            @endif
                        </p>
                    </div>

                    <div class="flex justify-end items-center">
                        <a href="{{ route('frontend.orders.show', $order->id) }}"
                            class="bg-blue-500 text-white px-4 py-1 rounded text-sm hover:bg-blue-600">Detail</a>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-box-open text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Pesanan Custom</h3>
                    <p class="text-gray-600 mb-6">Anda tidak memiliki pesanan custom saat ini.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pesanan Saya Tab -->
        <div id="orders-content" class="tab-content p-6 ">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Pesanan Aktif Saya</h2>
                <div class="text-sm text-gray-600">
                    {{ $activeOrdersCount ?? 0 }} pesanan aktif
                </div>
            </div>



            <div class="space-y-4">
                @forelse($activeOrders ?? [] as $order)
                @php
                $statusConfig = [
                '3' => [
                'bg-blue-50',
                'border-blue-200',
                'bg-blue-500',
                'text-blue-600',
                'fa-truck',
                'Dalam Pengiriman',
                ],
                '1' => [
                'bg-yellow-50',
                'border-yellow-200',
                'bg-yellow-500',
                'text-yellow-600',
                'fa-clock',
                'Menunggu Order Confirmation',
                ],
                '2' => [
                'bg-yellow-50',
                'border-yellow-200',
                'bg-yellow-500',
                'text-yellow-600',
                'fa-file-invoice',
                'Menunggu Pembayaran',
                ],
                ];
                $config = $statusConfig[$order->status] ?? $statusConfig['1'];
                @endphp

                <div class="border {{ $config[1] }} rounded-lg p-4 {{ $config[0] }}">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="font-semibold text-gray-900">#{{ $order->order_number }}</span>
                                <span class="px-2 py-1 {{ $config[2] }} text-white text-xs rounded-full">
                                    <i class="fas {{ $config[4] }} mr-1"></i>{{ $config[5] }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('d F Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">Rp
                                {{ number_format($order->total, 0, ',', '.') }}
                            </p>
                            <p class="text-sm {{ $config[3] }} font-medium">
                                @if ($order->status == 3)
                                Estimasi:
                                {{ $order->estimated_delivery ? $order->estimated_delivery->format('d F') : '-' }}
                                @elseif(in_array($order->status, [2, 6]))
                                Menunggu Pembayaran
                                @else
                                Akan dikirim hari ini
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($order->status == 3)
                    <!-- Progress Tracking -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                            <span>Pesanan Diproses</span>
                            <span>Dalam Pengiriman</span>
                            <span>Sampai Tujuan</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 66%;"></div>
                        </div>
                    </div>
                    @endif

                    <div class="text-sm text-gray-700 mb-4">
                        <p>
                            @if ($order->items && $order->items->count() > 0)
                            @foreach ($order->items->take(2) as $item)
                            {{ $item->nama_produk }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                            @if ($order->items->count() > 2)
                            dan {{ $order->items->count() - 2 }} item lainnya
                            @endif
                            @endif
                        </p>

                    </div>

                    <div class="flex justify-between items-center">
                        @if ($order->status == 3)
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-map-marker-alt mr-1"></i>Lacak Pesanan
                        </button>
                        @elseif($order->status == 6)
                        <div class="text-sm text-yellow-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <span>Segera lakukan pembayaran</span>
                        </div>
                        @else
                        <div></div>
                        @endif

                        <div class="flex space-x-2">
                            @if ($order->status == 3)
                            <form action="{{ route('frontend.orders.diterima', $order->id) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-green-500 text-white px-4 py-1 rounded text-sm hover:bg-green-600">
                                    <i class="fas fa-check-circle mr-1"></i>Pesanan Diterima
                                </button>
                            </form>
                            @elseif($order->status == 6)
                            <a href="{{ route('frontend.orders.show', $order->id) }}"
                                class="bg-blue-500 text-white px-4 py-1 rounded text-sm hover:bg-blue-600">Lihat
                                Penawaran</a>
                            @else
                            @if ($order->invoice && $order->invoice->kode_inv)
                            <a href="{{ route('frontend.orders.show', $order->id) }}"
                                class="bg-blue-500 text-white px-4 py-1 rounded text-sm hover:bg-blue-600">Detail</a>
                            <a href="{{ route('invoices.view', $order->invoice->id) }}" target="_blank"
                                class="bg-gray-500 text-white px-4 py-1 rounded text-sm hover:bg-gray-600">Lihat
                                Invoice</a>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-shopping-bag text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Pesanan Aktif</h3>
                    <p class="text-gray-600 mb-6">Anda tidak memiliki pesanan yang sedang diproses</p>
                    <a href=""
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Mulai Berbelanja
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- PO Saya Tab -->
        <div id="po-content" class="tab-content p-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Pesanan PO Saya</h2>
                <div class="text-sm text-gray-600">
                    {{ count($approvedPoOrders ?? []) }} PO disetujui
                </div>
            </div>

            <div class="space-y-4">
                @forelse($approvedPoOrders ?? [] as $order)
                <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-1">
                                <span
                                    class="font-semibold text-gray-900">{{ $order->kode_po ?? 'Approve' }}</span>
                                </span>
                                <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">
                                    <i class="fas fa-check mr-1"></i>Approved
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">
                                {{ $order->created_at->format('d F Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-green-600 font-medium">PO Disetujui Admin</p>
                        </div>
                    </div>

                    <div class="text-sm text-gray-700 mb-4">
                        <p>
                            @if ($order->items && $order->items->count() > 0)
                            @foreach ($order->items->take(2) as $item)
                            {{ $item->nama_produk }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                            @if ($order->items->count() > 2)
                            dan {{ $order->items->count() - 2 }} item lainnya
                            @endif
                            @endif
                        </p>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href=""
                            class="bg-blue-600 text-white px-4 py-1 rounded text-sm hover:bg-blue-700">
                            Lihat Detail PO
                        </a>

                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-file-invoice text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada PO Disetujui</h3>
                    <p class="text-gray-600 mb-6">Tidak ada pesanan PO yang sudah disetujui admin.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Invoice Saya Tab -->
        <div id="invoice-content" class="tab-content p-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Invoice Saya</h2>
                <div class="text-sm text-gray-600">
                    {{ $invoiceOrders ?? 0 }} Invoice diterbitkan
                </div>
            </div>

            <div class="space-y-4">
                @forelse($invoices ?? [] as $invoice)
                <div class="border border-blue-200 rounded-lg p-4 bg-blue-50">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="font-semibold text-gray-900">#{{ $invoice->kode_inv }}</span>

                                {{-- Badge status --}}
                                @if ($invoice->status == 7)
                                <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded-full">
                                    <i class="fas fa-file-invoice mr-1"></i> Menunggu Pembayaran
                                </span>
                                @elseif ($invoice->status == 9)
                                <span class="px-2 py-1 bg-yellow-500 text-white text-xs rounded-full">
                                    <i class="fas fa-clock mr-1"></i> Unpaid
                                </span>
                                @elseif ($invoice->status == 8)
                                <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">
                                    <i class="fas fa-check mr-1"></i> Paid
                                </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600">
                                {{ $invoice->created_at->format('d F Y') }}
                            </p>
                        </div>

                        <div class="text-right">
                            @if ($invoice->transaksi)
                            <p class="font-semibold text-gray-900">
                                Rp {{ number_format($invoice->transaksi->total, 0, ',', '.') }}
                            </p>
                            @endif
                            <p
                                class="text-sm font-medium
                    @if ($invoice->status == 9) text-yellow-600
                    @elseif($invoice->status == 8) text-green-600
                    @elseif($invoice->status == 7) text-blue-600
                    @else text-gray-600 @endif">

                                @if ($invoice->status == 9)
                                Unpaid
                                @elseif ($invoice->status == 8)
                                Paid
                                @elseif ($invoice->status == 7)
                                Invoice Issued
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- List item transaksi --}}
                    @if ($invoice->transaksi && $invoice->transaksi->items->count() > 0)
                    <div class="text-sm text-gray-700 mb-4">
                        <p>
                            @foreach ($invoice->transaksi->items->take(2) as $item)
                            {{ $item->nama_produk }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach

                            @if ($invoice->transaksi->items->count() > 2)
                            dan {{ $invoice->transaksi->items->count() - 2 }} item lainnya
                            @endif
                        </p>
                    </div>
                    @endif

                    <div class="flex justify-start items-center space-x-2">
                        @if ($invoice->kode_inv)
                        <a href="{{ route('invoices.view', $invoice->id) }}" target="_blank"
                            class="bg-blue-600 text-white px-4 py-1 rounded text-sm hover:bg-blue-700">
                            Lihat Detail Invoice
                        </a>
                        @endif
                        @if (!empty($invoice->faktur))
                        <a href="{{ asset('storage/' . $invoice->faktur) }}" target="_blank"
                            class="bg-green-600 text-white px-4 py-1 rounded text-sm hover:bg-green-700">
                            Lihat Faktur
                        </a>
                        @endif
                    </div>

                </div>
                @empty
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-file-invoice text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Invoice</h3>
                    <p class="text-gray-600 mb-6">Tidak ada invoice yang diterbitkan.</p>
                </div>
                @endforelse



            </div>

        </div>



        <!-- Edit Profile Tab -->
        <div id="edit-content" class="tab-content p-6 hidden">
            <div class="max-w-4xl">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Edit Profile</h2>

                @if (session('success'))
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span class="font-medium">Terjadi kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Profile Picture Upload -->
                    <div class="flex items-center space-x-6">
                        <div class="relative">
                            @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Picture"
                                class="w-24 h-24 rounded-full object-cover border-4 border-gray-200"
                                id="preview-avatar">
                            @else
                            <div class="w-24 h-24 rounded-full bg-gray-200 border-4 border-gray-200 flex items-center justify-center"
                                id="preview-avatar">
                                <i class="fas fa-user text-gray-400 text-2xl"></i>
                            </div>
                            @endif
                            <input type="file" name="avatar" accept="image/*" class="hidden" id="avatar-input"
                                onchange="previewAvatar(event)">
                            <button type="button" onclick="document.getElementById('avatar-input').click()"
                                class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600">
                                <i class="fas fa-camera text-xs"></i>
                            </button>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Foto Profil</h3>
                            <p class="text-sm text-gray-600">Format JPG, PNG. Maksimal 2MB.</p>
                            <button type="button" onclick="document.getElementById('avatar-input').click()"
                                class="mt-2 text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Ubah Foto
                            </button>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama"
                                value="{{ old('nama', $user->detail->nama ?? '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                                required>
                            @error('nama')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                required>
                            @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="telepon"
                                value="{{ old('telepon', $user->detail->telepon ?? '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telepon') border-red-500 @enderror">
                            @error('telepon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $user->detail && $user->detail->tanggal_lahir ? $user->detail->tanggal_lahir->format('Y-m-d') : '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_lahir') border-red-500 @enderror">
                            @error('tanggal_lahir')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('alamat') border-red-500 @enderror">{{ old('alamat', $user->detail->alamat ?? '') }}</textarea>
                        @error('alamat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Change Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Password</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                                <input type="password" name="current_password" placeholder="Masukkan password lama"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_password') border-red-500 @enderror">
                                @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                <input type="password" name="password" placeholder="Masukkan password baru"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
                                @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password
                                    Baru</label>
                                <input type="password" name="password_confirmation"
                                    placeholder="Konfirmasi password baru"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Kosongkan jika tidak ingin mengubah password
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6">
                        <button type="button" onclick="resetForm()"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            Reset
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div id="invoice-modal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-4 max-w-4xl w-full h-[90vh]">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Detail Invoice</h3>
            <div class="flex gap-2">
                <a id="download-invoice-btn" href="#" target="_blank"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>Download
                </a>
                <button onclick="closeInvoiceModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div id="invoice-modal-content" class="h-[calc(90vh-80px)]">
            <iframe id="invoice-pdf-frame" class="w-full h-full rounded border-0" src=""
                frameborder="0"></iframe>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openInvoiceModal(invoiceId) {
        // Set the PDF source URL
        const pdfUrl = `/invoices/${invoiceId}/view`;
        document.getElementById('invoice-pdf-frame').src = pdfUrl;

        // Set the download button URL
        document.getElementById('download-invoice-btn').href = `/invoices/${invoiceId}/download`;

        // Show the modal
        document.getElementById('invoice-modal').classList.remove('hidden');
    }

    function closeInvoiceModal() {
        // Clear the iframe source when closing
        document.getElementById('invoice-pdf-frame').src = '';
        document.getElementById('invoice-modal').classList.add('hidden');
    }
</script>
@endpush


@push('scripts')
<script>
    function switchTab(tab) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Remove active class from all tabs
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'border-blue-600', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected tab content
        document.getElementById(tab + '-content').classList.remove('hidden');

        // Add active class to selected tab
        const activeTab = document.getElementById(tab + '-tab');
        activeTab.classList.add('active', 'border-blue-600', 'text-blue-600');
        activeTab.classList.remove('border-transparent', 'text-gray-500');

        // Smooth scroll to content
        document.getElementById(tab + '-content').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview-avatar');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace div with img
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Profile Picture';
                    img.className = 'w-24 h-24 rounded-full object-cover border-4 border-gray-200';
                    img.id = 'preview-avatar';
                    preview.parentNode.replaceChild(img, preview);
                }
            };
            reader.readAsDataURL(file);
        }
    }

    function resetForm() {
        document.querySelector('#edit-content form').reset();
        // Reset avatar preview if needed
        location.reload();
    }

    // Filter functions for history tab
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('status-filter');
        const dateFilter = document.getElementById('date-filter');

        if (statusFilter) {
            statusFilter.addEventListener('change', filterOrders);
        }

        if (dateFilter) {
            dateFilter.addEventListener('change', filterOrders);
        }
    });

    function filterOrders() {
        const statusFilter = document.getElementById('status-filter').value;
        const dateFilter = document.getElementById('date-filter').value;
        const orders = document.querySelectorAll('#history-orders > div');

        orders.forEach(order => {
            let showOrder = true;

            // Status filter
            if (statusFilter) {
                const statusBadge = order.querySelector('.px-2.py-1');
                if (statusBadge) {
                    const orderStatus = statusBadge.textContent.toLowerCase().includes('selesai') ?
                        'delivered' : 'cancelled';
                    if (orderStatus !== statusFilter) {
                        showOrder = false;
                    }
                }
            }

            // Date filter (basic implementation)
            if (dateFilter && showOrder) {
                const orderDate = order.querySelector('.text-sm.text-gray-600').textContent;
                // Convert date for comparison (implementation depends on your date format)
                // This is a simplified version
                if (!orderDate.includes(dateFilter)) {
                    // showOrder = false; // Uncomment if you want exact date matching
                }
            }

            order.style.display = showOrder ? 'block' : 'none';
        });
    }

    // Auto-switch to specific tab based on URL hash
    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash.replace('#', '');
        const validTabs = ['detail', 'history', 'orders', 'po', 'invoice', 'edit'];

        if (validTabs.includes(hash)) {
            switchTab(hash);
        }
    });

    // Form submission with loading state
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('#edit-content form');
        if (form) {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                submitBtn.disabled = true;

                // Reset button after a delay if form doesn't redirect
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }, 5000);
            });
        }
    });
</script>
@endpush