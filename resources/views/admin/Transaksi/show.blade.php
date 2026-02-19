@extends('admin.dashboard')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    <h3 class="card-title">Detail Transaksi: {{ $transaksi->idtransaksi }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Informasi Pelanggan</h4>
                                <p><strong>Nama:</strong> {{ $transaksi->user->detail->nama ?? 'N/A' }}</p>
                                <p><strong>Email:</strong> {{ $transaksi->user->email ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h4>Informasi Transaksi</h4>
                                <p><strong>Tanggal:</strong> {{ $transaksi->created_at->format('d M Y - H:i') }}</p>
                                <p><strong>Status:</strong> <span class="badge bg-warning">Pending</span></p>
                                <p><strong>Total:</strong> Rp {{ number_format($transaksi->total ?? 0, 2, ',', '.') }}</p>
                            </div>
                        </div>

                        <h4 class="mt-4">Item Transaksi</h4>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Jenis</th>
                                    <th>Ukuran</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->produk->nama_produk ?? 'N/A' }}</td>
                                        <td>{{ $item->jenis->jenis ?? 'N/A' }}</td>
                                        <td>{{ $item->ukuran->ukuran ?? 'N/A' }}</td>
                                        <td>Rp {{ number_format($item->harga ?? 0, 2, ',', '.') }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>Rp {{ number_format($item->harga * $item->qty, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-end mt-3">
                            <a href="{{ route('admin.transaksi.pending') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
