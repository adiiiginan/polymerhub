@extends('admin.layout.partials.app')

@section('title', 'Order Request')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Order Request</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Nama Customer</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksi as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->created_at->format('d-m-Y') }}</td>
                            <td>{{ number_format($item->total, 2) }}</td>
                            <td><span class="badge bg-warning">{{ $item->status->status }}</span></td>
                            <td>
                                <a href="{{ route('admin.transaksi.show', $item->id) }}" class="btn btn-sm btn-info">Lihat
                                    Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
