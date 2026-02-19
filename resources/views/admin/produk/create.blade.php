@extends('admin.dashboard')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0 fw-bold">Tambah Produk</h4>
            </div>
            <div class="card-body">

                {{-- Notifikasi Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Perhatian!</strong> Ada kesalahan input.
                        <ul class="mt-2 mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form Produk --}}
                {{-- Mengubah method menjadi GET untuk reload, dan action ke route create --}}
                <form action="{{ route('admin.produk.store') }}" method="POST" id="product-form"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_jenis" value="{{ request('shape_id') }}">
                    <input type="hidden" name="nama_produk_hidden" value="{{ request('nama_produk') }}">
                    <input type="hidden" name="deskripsi_hidden" value="{{ request('deskripsi') }}">
                    <input type="hidden" name="id_kat_hidden" value="{{ request('id_kat') }}">
                    <input type="hidden" name="merk_hidden" value="{{ request('merk') }}">
                    <input type="hidden" name="tempratur_hidden" value="{{ request('tempratur') }}">
                    <input type="hidden" name="eu1935_hidden" value="{{ request('eu1935') }}">
                    <input type="hidden" name="fda_hidden" value="{{ request('fda') }}">
                    <input type="hidden" name="usp_hidden" value="{{ request('usp') }}">
                    <input type="hidden" name="id_environmant_hidden" value="{{ request('id_environmant') }}">
                    <input type="hidden" name="pressure_hidden" value="{{ request('pressure') }}">
                    <input type="hidden" name="mating_hidden" value="{{ request('mating') }}">
                    <input type="hidden" name="max_pv_hidden" value="{{ request('max_pv') }}">
                    <input type="hidden" name="maximum_p_hidden" value="{{ request('maximum_p') }}">
                    <input type="hidden" name="max_v_hidden" value="{{ request('max_v') }}">
                    <input type="hidden" name="friction_hidden" value="{{ request('friction') }}">
                    <input type="hidden" name="elongation_hidden" value="{{ request('elongation') }}">
                    <input type="hidden" name="deformation_hidden" value="{{ request('deformation') }}">
                    <input type="hidden" name="tensile_hidden" value="{{ request('tensile') }}">
                    <input type="hidden" name="spesific_hidden" value="{{ request('spesific') }}">
                    <input type="hidden" name="status_aktif_hidden" value="{{ request('status_aktif') }}">


                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            {{-- Informasi Dasar Produk --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-center fw-bold" style="font-size: 17px; margin-top: 43px;">
                                    Informasi Dasar Produk
                                </div>
                                <div class="card-body">
                                    {{-- Kode & Nama --}}
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Kode Produk</label>
                                            <input type="text" class="form-control" value="{{ $kode }}"
                                                disabled>
                                            <input type="hidden" name="kode_produk" value="{{ $kode }}">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">SKU Produk</label>
                                            <input type="text" name="sku" class="form-control"
                                                value="{{ request('sku') }}" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Nama Produk</label>
                                            <input type="text" name="nama_produk" class="form-control"
                                                value="{{ request('nama_produk') }}" required>
                                        </div>


                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Weight (kg)</label>
                                            <input type="number" name="weight" id="weight" class="form-control"
                                                value="{{ old('weight') }}" step="any">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Length (cm)</label>
                                            <input type="number" name="length" id="length" class="form-control"
                                                value="{{ old('length') }}" step="any">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Width (cm)</label>
                                            <input type="number" name="width" id="width" class="form-control"
                                                value="{{ old('width') }}" step="any">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Height (cm)</label>
                                            <input type="number" name="height" id="height" class="form-control"
                                                value="{{ old('height') }}" step="any">
                                        </div>
                                    </div>

                                    {{-- Deskripsi --}}
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="3">{{ request('deskripsi') }}</textarea>
                                    </div>

                                    {{-- Kategori & Merk --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Produk Kategori</label>
                                            <select name="id_kat" class="form-select">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach ($kategori as $env)
                                                    <option value="{{ $env->id }}"
                                                        {{ request('id_kat') == $env->id ? 'selected' : '' }}>
                                                        {{ $env->kategori }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Merk</label>
                                            <input type="text" name="merk" class="form-control"
                                                value="{{ request('merk') }}">
                                        </div>
                                    </div>




                                    {{-- Temperature --}}
                                    <div class="mb-3">
                                        <label class="form-label">Temperature Range</label>
                                        <input type="text" name="tempratur" class="form-control"
                                            value="{{ request('tempratur') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Varian Stok & Harga --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-center fw-bold" style="font-size: 17px; margin-top: 43px;">
                                    Varian Stok & Harga
                                </div>
                                <div class="card-body">
                                    {{-- Pilihan Shape (Dropdown) --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Pilih Shape</label>
                                        <select name="shape_id" id="shapeSelect" class="form-select">
                                            <option value="">-- Pilih Shape untuk menampilkan ukuran --</option>
                                            @foreach ($shapes as $shape)
                                                <option value="{{ $shape->id }}"
                                                    {{ request('shape_id') == $shape->id ? 'selected' : '' }}>
                                                    {{ $shape->jenis }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Kontainer Ukuran, Stok, dan Harga --}}
                                    <div id="varian-details" style="{{ request('shape_id') ? '' : 'display: block;' }}">
                                        @if ($ukurans->count() > 0)
                                            <hr>
                                            <div class="mb-3">
                                                <label for="id_ukuran" class="form-label fw-bold">Pilih Ukuran</label>
                                                <select class="form-select" name="id_ukuran[]" id="id_ukuran">
                                                    <option value="">Pilih Ukuran</option>
                                                    @foreach ($ukurans as $ukuran)
                                                        <option value="{{ $ukuran->id }}"
                                                            {{ request('id_ukuran') == $ukuran->id ? 'selected' : '' }}>
                                                            {{ $ukuran->nama_ukuran }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Stok</label>
                                                    <input type="number" name="stok_variant[]" class="form-control"
                                                        placeholder="Stok" value="">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Harga</label>
                                                    <input type="number" name="harga_variant[]" class="form-control"
                                                        placeholder="Harga" value="">
                                                </div>
                                            </div>
                                        @elseif(request('shape_id'))
                                            <hr>
                                            <p class="text-muted text-center">Tidak ada ukuran yang tersedia untuk shape
                                                ini.</p>
                                        @endif
                                    </div>
                                    {{-- Hidden inputs to ensure validation triggers if no shape is selected --}}
                                    @if (!request('shape_id'))
                                        <div style="display: none;">
                                            <select name="id_ukuran[]">
                                                <option value=""></option>
                                            </select>
                                            <input type="number" name="stok_variant[]" value="">
                                            <input type="number" name="harga_variant[]" value="">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Sertifikasi --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-center fw-bold" style="font-size: 17px; margin-top: 43px;">
                                    Sertifikasi
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Certification EU1935</label>
                                            <select name="eu1935" class="form-select">
                                                <option value="ya" {{ request('eu1935') == 'ya' ? 'selected' : '' }}>
                                                    Ya</option>
                                                <option value="tidak"
                                                    {{ request('eu1935') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Certification FDA</label>
                                            <select name="fda" class="form-select">
                                                <option value="ya" {{ request('fda') == 'ya' ? 'selected' : '' }}>Ya
                                                </option>
                                                <option value="tidak" {{ request('fda') == 'tidak' ? 'selected' : '' }}>
                                                    Tidak</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Certification USP Class VI</label>
                                            <select name="usp" class="form-select">
                                                <option value="ya" {{ request('usp') == 'ya' ? 'selected' : '' }}>Ya
                                                </option>
                                                <option value="tidak" {{ request('usp') == 'tidak' ? 'selected' : '' }}>
                                                    Tidak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            {{-- Spesifikasi Teknis --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-center fw-bold" style="font-size: 17px; margin-top: 43px;">
                                    Spesifikasi Teknis
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Environment</label>
                                            <select name="id_environmant" class="form-select">
                                                <option value="">-- Pilih Environment --</option>
                                                @foreach ($environment as $env)
                                                    <option value="{{ $env->id }}"
                                                        {{ request('id_environmant') == $env->id ? 'selected' : '' }}>
                                                        {{ $env->envi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Pressure</label>
                                            <input type="text" name="pressure" class="form-control"
                                                value="{{ request('pressure') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Shaft Hardness</label>
                                            <input type="text" name="mating" class="form-control"
                                                value="{{ request('mating') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Max PV (Mpa x m/s)</label>
                                            <input type="number" step="0.01" name="max_pv" class="form-control"
                                                value="{{ request('max_pv') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Maximum P (MPa)</label>
                                            <input type="text" name="maximum_p" class="form-control"
                                                value="{{ request('maximum_p') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Max V (m/s)</label>
                                            <input type="number" step="0.01" name="max_v" class="form-control"
                                                value="{{ request('max_v') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Friction – static & dynamic​</label>
                                            <input type="text" step="0.01" name="friction" class="form-control"
                                                value="{{ request('friction') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Elongation ASTM D638</label>
                                            <input type="text" step="0.01" name="elongation" class="form-control"
                                                value="{{ request('elongation') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Deformation Under Load</label>
                                            <input type="number" step="0.01" name="deformation" class="form-control"
                                                value="{{ request('deformation') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tensile Strength (MPa)</label>
                                            <input type="number" step="0.01" name="tensile" class="form-control"
                                                value="{{ request('tensile') }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Specific Gravity</label>
                                        <input type="number" step="0.01" name="spesific" class="form-control"
                                            value="{{ request('spesific') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Media & Gambar --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-center fw-bold" style="font-size: 17px; margin-top: 43px;">
                                    Media & Status
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Gambar Produk</label>
                                        <input type="file" name="gambar" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status Aktif</label>
                                        <select name="status_aktif" class="form-select">
                                            <option value="1" {{ request('status_aktif') == '1' ? 'selected' : '' }}>
                                                Aktif</option>
                                            <option value="0" {{ request('status_aktif') == '0' ? 'selected' : '' }}>
                                                Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productForm = document.getElementById('product-form');
            const shapeSelect = document.getElementById('shapeSelect');

            if (shapeSelect) {
                shapeSelect.addEventListener('change', function() {
                    // Set form method to GET and action to the create route to reload with selected shape
                    productForm.method = 'GET';
                    productForm.action = "{{ route('admin.produk.create') }}";
                    productForm.submit();
                });
            }
        });
    </script>
@endpush
