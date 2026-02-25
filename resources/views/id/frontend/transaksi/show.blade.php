@extends('id.layouts.app')

@section('content')
    <div class="container">
        <h1>Transaction Details</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                Transaction #{{ $transaksi->idtransaksi }}
            </div>
            <div class="card-body">
                <p><strong>Date:</strong> {{ $transaksi->created_at->format('d F Y') }}</p>
                <p><strong>Total:</strong> Rp {{ number_format($transaksi->total, 2) }}</p>
                <p><strong>Shipping Service:</strong> {{ $transaksi->shipping_service }}</p>
                <p><strong>Shipping Cost:</strong> Rp {{ number_format($transaksi->shipping_cost, 2) }}</p>

                <h3>Items:</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi->details as $item)
                            <tr>
                                <td>{{ $item->produk->nama }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>Rp {{ number_format($item->harga, 2) }}</td>
                                <td>Rp {{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
