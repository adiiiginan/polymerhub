@extends('Admin.dashboard') @section('content') <div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0 fw-bold">Tambah Produk</h4>
        </div>
        <div class="card-body"> {{-- Notifikasi Error --}} @if ($errors->any()) <div class="alert alert-danger"> <strong>Perhatian!</strong> Ada kesalahan input. <ul class="mt-2 mb-0"> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
            </div> @endif {{-- Form Produk --}}
            <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data"> @csrf <div class="row"> <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header text-center fw-bold" style=" font-size: 17px; margin-top: 43px; "> Informasi Dasar Produk </div>
                            <div class="card-body"> {{-- Baris 1 --}}
                                <div class="row mb-3">
                                    <div class="col-md-4"> <label class="form-label">Kode Produk</label> <input type="text" class="form-control" value="{{ $kode }}" disabled> <input type="hidden" name="kode_produk" value="{{ $kode }}"> </div>
                                    <div class="col-md-8"> <label class="form-label">Nama Produk</label> <input type="text" name="nama_produk" class="form-control" required> </div>
                                </div> {{-- Deskripsi --}}
                                <div class="mb-3"> <label class="form-label">Deskripsi</label> <textarea name="deskripsi" class="form-control" rows="3"></textarea> </div> {{-- Baris 2 --}}
                                <div class="row mb-3">
                                    <div class="col-md-6"> <label class="form-label">Produk Kategori</label> <select name="id_kat" class="form-select">
                                            <option value="">-- Pilih Kategori --</option> @foreach ($kategori as $env) <option value="{{ $env->id }}"> {{ $env->kategori }} </option> @endforeach
                                        </select> </div>
                                    <div class="col-md-6"> <label class="form-label">Merk</label> <input type="text" name="merk" class="form-control"> </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6"> <label class="form-label">Harga</label> <input type="number" step="0.01" name="harga" class="form-control" required> </div>
                                    <div class="col-md-6"> <label class="form-label">Stok</label> <input type="number" name="stok" class="form-control" required> </div>
                                </div>
                                <div class="mb-3"> <label class="form-label">Temperature Range</label> <input type="text" name="tempratur" class="form-control"> </div>
                            </div>
                        </div>
                        <div class="card shadow-sm mb-4">
                            <div class="card-header text-center fw-bold" style=" font-size: 17px; margin-top: 43px; "> Sertifikasi </div>
                            <div class="card-body"> {{-- Baris 5 (Enum) --}}
                                <div class="row mb-3">
                                    <div class="col-md-4"> <label class="form-label">Certification EU1935</label> <select name="eu1935" class="form-select">
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select> </div>
                                    <div class="col-md-4"> <label class="form-label">Certification FDA</label> <select name="fda" class="form-select">
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select> </div>
                                    <div class="col-md-4"> <label class="form-label">Certification USP Class VI</label> <select name="usp" class="form-select">
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select> </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header text-center fw-bold" style=" font-size: 17px; margin-top: 43px; "> Spesifikasi Teknis </div>
                            <div class="card-body"> {{-- Baris 4 --}}
                                <div class="row mb-3">
                                    <div class="col-md-6"> <label class="form-label">Environment</label> <select name="id_environmant" class="form-select">
                                            <option value="">-- Pilih Environment --</option> @foreach ($environment as $env) <option value="{{ $env->id }}"> {{ $env->envi }} </option> @endforeach
                                        </select> </div>
                                    <div class="col-md-6"> <label class="form-label">Pressure</label> <input type="text" name="pressure" class="form-control"> </div>
                                </div> {{-- Baris 6 (Spesifikasi Teknis) --}}
                                <div class="row mb-3">
                                    <div class="col-md-6"> <label class="form-label">Shaft Hardness</label> <input type="text" name="mating" class="form-control" required> </div>
                                    <div class="col-md-6"> <label class="form-label">Max PV (Mpa x m/s)</label> <input type="number" step="0.01" name="max_pv" class="form-control"> </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6"> <label class="form-label">Maximum P (MPa)</label> <input type="text" name="maximum_p" class="form-control"> </div>
                                    <div class="col-md-6"> <label class="form-label">Max V (m/s)</label> <input type="number" step="0.01" name="max_v" class="form-control"> </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6"> <label class="form-label">Friction – static & dynamic​</label> <input type="text" step="0.01" name="friction" class="form-control"> </div>
                                    <div class="col-md-6"> <label class="form-label">Elongation ASTM D638</label> <input type="text" step="0.01" name="elongation" class="form-control"> </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6"> <label class="form-label">Deformation Under Load</label> <input type="number" step="0.01" name="deformation" class="form-control"> </div>
                                    <div class="col-md-6"> <label class="form-label">Tensile Strength (MPa)</label> <input type="number" step="0.01" name="tensile" class="form-control"> </div>
                                </div>
                                <div class="mb-3"> <label class="form-label">Specific Gravity</label> <input type="number" step="0.01" name="spesific" class="form-control"> </div>
                            </div>
                        </div>
                        <div class="card shadow-sm mb-4">
                            <div class="card-header text-center fw-bold" style=" font-size: 17px; margin-top: 43px; "> Media & Dimensi </div>
                            <div class="card-body"> {{-- Gambar Produk --}}
                                <div class="mb-3"> <label class="form-label">Gambar Produk</label> <input type="file" name="gambar" class="form-control"> </div>
                                <div class="row mb-3">
                                    <div class="col-md-6"> <label class="form-label">Shape</label> <select id="shape" name="id_jenis" class="form-select">
                                            <option value="">-- Pilih Shape --</option> {{-- opsi shape akan diisi via ajax --}}
                                        </select> </div>
                                    <div class="col-md-6"> <label class="form-label">Dimension</label> <select id="dimension" name="id_ukuran" class="form-select">
                                            <option value="">-- Pilih Dimension --</option> {{-- opsi dimension akan diisi via ajax --}}
                                        </select> </div>
                                </div> {{-- Status Aktif --}}
                                <div class="mb-3"> <label class="form-label">Status Aktif</label> <select name="status_aktif" class="form-select">
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select> </div>
                            </div>
                        </div>
                    </div>
                </div> {{-- Tombol --}}
                <div class="d-flex justify-content-end gap-2"> <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Batal</a> <button type="submit" class="btn btn-primary">Simpan</button> </div>
            </form>
        </div>
    </div>
</div> @endsection