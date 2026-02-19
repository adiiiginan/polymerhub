@extends('Admin.Layout.partials.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stok Opname Selesai</h3>
        </div>
        <div class="card-body">
            <table id="table-stok-opname" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Opname</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stokOpnames as $key => $opname)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $opname->kode_opname }}</td>
                            <td>{{ \Carbon\Carbon::parse($opname->tanggal)->format('d-m-Y H:i') }}</td>
                            <td>{{ $opname->status }}</td>
                            <td>
                                <a href="{{ route('admin.stok-opname.show', $opname->id) }}"
                                    class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('admin.stok-opname.print', $opname->id) }}" class="btn btn-primary btn-sm"
                                    target="_blank">Cetak</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Stok Opname</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Kode Opname:</strong> <span id="detail-kode-opname"></span></p>
                            <p><strong>Tanggal:</strong> <span id="detail-tanggal"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Petugas:</strong> <span id="detail-petugas"></span></p>
                            <p><strong>Status:</strong> <span id="detail-status"></span></p>
                        </div>
                    </div>
                    <p><strong>Keterangan:</strong> <span id="detail-keterangan"></span></p>
                    <hr>
                    <h6>Detail Produk:</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Ukuran</th>
                                <th>Stok Sistem</th>
                                <th>Stok Fisik</th>
                                <th>Selisih</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody id="detail-produk-table">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#table-stok-opname').DataTable({
                responsive: true,
                autoWidth: false
            });

            // Event listener untuk tombol "Detail"
            $('#table-stok-opname').on('click', '.detail-btn', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.stok-opname.show', ':id') }}".replace(':id', id),
                    type: 'GET',
                    success: function(data) {
                        alert(JSON.stringify(data, null, 2));
                        try {
                            // Isi data umum
                            $('#detail-kode-opname').text(data.kode_opname || 'N/A');
                            $('#detail-tanggal').text(data.tanggal ? new Date(data.tanggal)
                                .toLocaleString('id-ID') : 'N/A');
                            $('#detail-status').text(data.status || 'N/A');
                            $('#detail-keterangan').text(data.keterangan || 'N/A');

                            // Isi petugas
                            if (data.user && data.user.user_detail) {
                                $('#detail-petugas').text(data.user.user_detail.nama || 'N/A');
                            } else {
                                $('#detail-petugas').text('N/A');
                            }

                            // Isi tabel detail produk
                            const detailsTable = $('#detail-produk-table');
                            detailsTable.empty();

                            if (data.details && Array.isArray(data.details) && data.details
                                .length > 0) {
                                data.details.forEach(function(detail) {
                                    const selisih = (detail.stok_fisik || 0) - (detail
                                        .stok_sistem || 0);

                                    const nama_produk = detail?.produk_stok?.produk
                                        ?.nama_produk || 'N/A';
                                    const nama_ukuran = detail?.produk_stok?.ukuran
                                        ?.nama_ukuran || 'N/A';

                                    detailsTable.append(`
                                        <tr>
                                            <td>${nama_produk}</td>
                                            <td>${nama_ukuran}</td>
                                            <td>${detail.stok_sistem || 0}</td>
                                            <td>${detail.stok_fisik || 0}</td>
                                            <td>${selisih}</td>
                                            <td>${detail.catatan || ''}</td>
                                        </tr>
                                    `);
                                });
                            } else {
                                detailsTable.append(
                                    '<tr><td colspan="6" class="text-center">Tidak ada detail produk.</td></tr>'
                                );
                            }

                            // Tampilkan modal
                            $('#detailModal').modal('show');
                        } catch (e) {
                            console.error("Error parsing data:", e);
                            alert(
                                'Terjadi kesalahan saat menampilkan data. Silakan cek konsol.'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert('Gagal memuat data. Silakan coba lagi nanti.');
                    }
                });
            });
        });
    </script>
@endsection
