@extends('admin.dashboard')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Laporan Perbandingan Stok</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.laporan.perbandingan_stok') }}">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="produk_id">Produk</label>
                            <select name="produk_id" id="produk_id" class="form-control">
                                <option value="">Pilih Produk</option>
                                @foreach ($produks as $produk)
                                    <option value="{{ $produk->id }}"
                                        {{ $selectedProdukId == $produk->id ? 'selected' : '' }}>
                                        {{ $produk->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="start_date">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ $startDate }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="end_date">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ $endDate }}">
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.laporan.perbandingan_stok') }}"
                                class="btn btn-secondary ml-2">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if ($selectedProdukId && $startDate && $endDate)
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Penjualan</h6>
                        </div>
                        <div class="card-body">
                            @if (!empty($penjualanData))
                                <p><strong>Total Produk Terjual:</strong> {{ $penjualanData['total_terjual'] }}</p>
                            @else
                                <p>Tidak ada data penjualan untuk produk dan periode yang dipilih.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Stok Opname</h6>
                        </div>
                        <div class="card-body">
                            @if (!empty($stokOpnameData))
                                <p><strong>Tanggal Stok Opname:</strong> {{ $stokOpnameData['tanggal'] }}</p>
                                <p><strong>Stok Fisik:</strong> {{ $stokOpnameData['stok_fisik'] }}</p>
                                <p><strong>Stok Sistem:</strong> {{ $stokOpnameData['stok_sistem'] }}</p>
                                <p><strong>Selisih:</strong> {{ $stokOpnameData['selisih'] }}</p>
                            @else
                                <p>Tidak ada data stok opname untuk produk dan periode yang dipilih.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
