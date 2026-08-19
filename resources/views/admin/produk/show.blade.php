@extends('admin.dashboard')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">Detail Produk</h4>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-light btn-sm">Kembali ke Daftar</a>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ asset('backend/assets/media/produk/' . $produk->gambar) }}"
                            class="img-fluid rounded border p-2" alt="{{ $produk->nama_produk }}">
                    </div>
                    <div class="col-md-8">
                        <h3>{{ $produk->nama_produk }}</h3>
                        <p class="text-muted">{{ $produk->category->category ?? '-' }}</p>
                        <hr>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th style="width: 150px;">SKU</th>
                                <td>: {{ $produk->sku }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>: {{ $produk->kategori->kategori ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Merk</th>
                                <td>: {{ $produk->merk ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>: {{ $produk->deskripsi ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0 fw-bold">Varian Stok & Harga</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Ukuran</th>
                                <th>Stok</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produk->stok as $stok)
                                <tr>
                                    <td>{{ $stok->ukuran->nama_ukuran ?? ($stok->jenis->jenis ?? '-') }}</td>
                                    <td>{{ $stok->stok }}</td>
                                    <td>Rp {{ number_format($stok->harga, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data stok untuk produk ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
