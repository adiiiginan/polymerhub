@extends('Admin.Layout.partials.app')

@section('content')
    <div class="container">
        <h1>Buat Stok Opname Baru</h1>

        <form action="{{ route('admin.stok-opname.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="kode_opname">Kode Opname</label>
                <input type="text" name="kode_opname" class="form-control" value="{{ $kode_opname }}" readonly>
            </div>
            <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="datetime-local" name="tanggal_mulai" class="form-control"
                    value="{{ now()->format('Y-m-d\TH:i') }}">
            </div>

            <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input type="datetime-local" name="tanggal_selesai" class="form-control"
                    value="{{ now()->format('Y-m-d\TH:i') }}">
            </div>
            <div class="form-group">
                <label for="iduser">Petugas</label>
                <select name="iduser" class="form-control" required style="color: black;">
                    <option value="">Pilih Petugas</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ optional($user->userDetail)->nama ?? $user->username }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" name="status" class="form-control" value="Draft" readonly>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" class="form-control"></textarea>
            </div>

            <hr>

            <h3>Detail Stok Opname</h3>
            <table class="table" id="details-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Stok Sistem</th>
                        <th>Stok Fisik</th>
                        <th>Catatan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="details[0][produk_stok_id]" class="form-control">
                                <option value="">Pilih Produk</option>
                                @foreach ($productStoks as $stok)
                                    <option value="{{ $stok->id }}">{{ $stok->produk->nama_produk }} -
                                        {{ $stok->ukuran->nama_ukuran }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="details[0][stok_sistem]" class="form-control stok-sistem" required
                                readonly></td>
                        <td><input type="number" name="details[0][stok_fisik]" class="form-control" required></td>
                        <td><input type="text" name="details[0][catatan]" class="form-control"></td>
                        <td><button type="button" class="btn btn-danger remove-detail-row">Hapus</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" id="add-detail-row" class="btn btn-primary">Tambah Produk</button>

            <hr>

            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let detailRowIndex = 1;

            document.getElementById('add-detail-row').addEventListener('click', function() {
                const tableBody = document.querySelector('#details-table tbody');
                const newRow = document.createElement('tr');

                newRow.innerHTML = `
                    <td>
                        <select name="details[${detailRowIndex}][produk_stok_id]" class="form-control">
                            <option value="">Pilih Produk</option>
                            @foreach ($productStoks as $stok)
                                <option value="{{ $stok->id }}">{{ $stok->produk->nama_produk }} - {{ $stok->ukuran->nama_ukuran }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="details[${detailRowIndex}][stok_sistem]" class="form-control stok-sistem" required readonly></td>
                    <td><input type="number" name="details[${detailRowIndex}][stok_fisik]" class="form-control" required></td>
                    <td><input type="text" name="details[${detailRowIndex}][catatan]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger remove-detail-row">Hapus</button></td>
                `;

                tableBody.appendChild(newRow);
                detailRowIndex++;
            });

            document.querySelector('#details-table').addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-detail-row')) {
                    e.target.closest('tr').remove();
                }
            });

            document.querySelector('#details-table').addEventListener('change', function(e) {
                if (e.target && e.target.name.endsWith('[produk_stok_id]')) {
                    const produkStokId = e.target.value;
                    const stokSistemInput = e.target.closest('tr').querySelector('.stok-sistem');

                    if (produkStokId) {
                        const url = `{{ url('admin/stok-opname/get-stok') }}/${produkStokId}`;
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                stokSistemInput.value = data.stok;
                            });
                    } else {
                        stokSistemInput.value = '';
                    }
                }
            });
        });
    </script>
@endsection
