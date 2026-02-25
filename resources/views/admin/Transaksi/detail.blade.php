@extends('admin.dashboard')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            Detail Transaksi</h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('admin.transaksi.pending') }}" class="text-muted text-hover-primary">Order
                                    Pending</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Detail Transaksi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-5">
                    <div class="col-lg-8">
                        <div class="card card-flush">
                            <div class="card-header">
                                <h3 class="card-title">Detail Item</h3>
                            </div>
                            <div class="card-body">
                                <table class="table align-middle table-row-dashed fs-6 gy-5">
                                    <thead class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <tr>
                                            <th class="min-w-175px">Produk</th>
                                            <th class="min-w-70px text-end">Qty</th>
                                            <th class="min-w-100px text-end">Harga</th>
                                            <th class="min-w-100px text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        @foreach ($transaksi->details as $detail)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-50px me-5">
                                                            <img src="{{ asset('backend/assets/media/produk/' . ($detail->produk->gambar ?? '')) }}"
                                                                alt="{{ $detail->produk->nama_produk ?? 'N/A' }}"
                                                                style="width: 50px; height: 50px; object-fit: cover;" />
                                                        </div>
                                                        <div class="d-flex justify-content-start flex-column">
                                                            <a href="#"
                                                                class="text-dark fw-bold text-hover-primary fs-6">{{ $detail->produk->nama_produk ?? 'N/A' }}</a>
                                                            <span
                                                                class="text-muted fw-semibold text-muted d-block fs-7">{{ $detail->jenis->jenis ?? '' }}
                                                                {{ $detail->ukuran->nama_ukuran ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">{{ $detail->qty }}</td>
                                                <td class="text-end">
                                                    {{ $transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}{{ number_format($detail->harga, 2, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ $transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}{{ number_format($detail->harga * $detail->qty, 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="fw-semibold text-gray-600">
                                        <tr>
                                            <td colspan="3" class="text-end">Subtotal</td>
                                            <td class="text-end">
                                                {{ $transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}{{ number_format($transaksi->details->sum(function ($d) {return $d->harga * $d->qty;}),2,',','.') }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="3" class="text-end">Biaya Ekspedisi</td>
                                            <td class="text-end">
                                                {{ $transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}
                                                {{ number_format($transaksi->shipping_cost, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end fs-5 fw-bold">Total</td>
                                            <td class="text-end fs-5 fw-bolder">
                                                {{ $transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}{{ number_format($transaksi->details->sum(function ($d) {return $d->harga * $d->qty;}) + $transaksi->shipping_cost,2,',','.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Transaksi</h3>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>ID Transaksi</th>
                                        <td>{{ $transaksi->idtransaksi }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td>{{ $transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}
                                            {{ number_format($transaksi->total, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <td>{{ $transaksi->created_at->format('d M Y - H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td><span
                                                class="badge bg-warning btn-md">{{ $transaksi->statusRelasi->status ?? 'N/A' }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Pelanggan</h3>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>Nama</th>
                                        <td>{{ $transaksi->user->userDetail->nama ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $transaksi->user->email ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="card card-flush">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Pengiriman</h3>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>Alamat</th>
                                        <td>{{ optional($transaksi->address)->alamat }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kota</th>
                                        <td>{{ optional(optional($transaksi->address)->city)->nama_kota ?? optional($transaksi->address)->city }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Provinsi</th>
                                        <td>{{ optional(optional($transaksi->address))->state }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kode Pos</th>
                                        <td>{{ optional($transaksi->address)->zip_code }}</td>
                                    </tr>
                                    <tr>
                                        <th>Negara</th>
                                        <td>{{ optional(optional($transaksi->address)->country)->country_name ?? optional($transaksi->address)->idcountry }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Layanan Ekspedisi</th>
                                        <td>{{ $transaksi->shipping_service }}</td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection

@section('scripts')
    <!-- Decline Modal -->
    <div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="declineModalLabel">Tolak Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.transaksi.decline', $transaksi->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan Penolakan</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Setujui Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui transaksi ini?</p>
                    <form action="{{ route('admin.transaksi.approve', $transaksi->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Setuju</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
