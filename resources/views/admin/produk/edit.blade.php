@extends('admin.dashboard')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header text-white text-center py-8">
                <h3 class="mb-0 fw-bolder">Edit Produk</h3>
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
                <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" id="product-form"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                            <input type="text" class="form-control" value="{{ $produk->kode_produk }}"
                                                disabled>
                                            <input type="hidden" name="kode_produk" value="{{ $produk->kode_produk }}">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Nama Produk</label>
                                            <input type="text" name="nama_produk" class="form-control"
                                                value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label class="form-label">SKU</label>
                                            <input type="text" name="sku" class="form-control"
                                                value="{{ old('sku', $produk->sku) }}" required>
                                        </div>
                                    </div>

                                    {{-- Deskripsi --}}
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                                    </div>

                                    {{-- Kategori & Merk --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Produk Kategori</label>
                                            <select name="id_kategori" class="form-select">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach ($kategori as $kat)
                                                    <option value="{{ $kat->id }}"
                                                        {{ old('id_kategori', $produk->id_kat) == $kat->id ? 'selected' : '' }}>
                                                        {{ $kat->kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Merk</label>
                                            <input type="text" name="merk" class="form-control"
                                                value="{{ old('merk', $produk->merk) }}">
                                        </div>
                                    </div>



                                    {{-- Temperature --}}
                                    <div class="mb-3">
                                        <label class="form-label">Temperature Range</label>
                                        <input type="text" name="tempratur" class="form-control"
                                            value="{{ old('tempratur', $produk->tempratur) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Varian Stok & Harga --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-center fw-bold" style="font-size: 17px; margin-top: 43px;">
                                    Varian Stok & Harga
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Pilih Shape</label>
                                        @php
                                            $selected_shape_id = old('id_shape', $produk->id_jenis);
                                        @endphp
                                        <select name="id_shape" id="shapeSelect" class="form-select">
                                            <option value="">-- Pilih Shape untuk menampilkan ukuran --</option>
                                            @foreach ($shapes as $shape)
                                                <option value="{{ $shape->id }}"
                                                    {{ $selected_shape_id == $shape->id ? 'selected' : '' }}>
                                                    {{ $shape->jenis }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Kontainer Ukuran, Stok, dan Harga --}}
                                    <div id="varian-details">
                                        @if ($produk->variants && $produk->variants->count() > 0)
                                            @foreach ($produk->variants as $stok)
                                                <hr>
                                                <div class="mb-3">
                                                    <label for="id_ukuran" class="form-label fw-bold">Ukuran</label>
                                                    <select class="form-select" name="id_ukuran[]">
                                                        <option value="">Pilih Ukuran</option>
                                                        @foreach ($ukurans as $ukuran)
                                                            <option value="{{ $ukuran->id }}"
                                                                {{ $stok->id_ukuran == $ukuran->id ? 'selected' : '' }}>
                                                                {{ $ukuran->nama_ukuran }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Stok</label>
                                                        <input type="number" name="stok_variant[]" class="form-control"
                                                            value="{{ $stok->stok }}">
                                                    </div>


                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Harga</label>
                                                        <input type="number" name="harga_variant[]" class="form-control"
                                                            step="0.01" value="{{ $stok->harga }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Weight (kg)</label>
                                                        <input type="number" name="weight_variant[]" class="form-control"
                                                            value="{{ $stok->weight }}" step="any">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Length (cm)</label>
                                                        <input type="number" name="length_variant[]"
                                                            class="form-control" value="{{ $stok->length }}"
                                                            step="any">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Width (cm)</label>
                                                        <input type="number" name="width_variant[]" class="form-control"
                                                            value="{{ $stok->width }}" step="any">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Height (cm)</label>
                                                        <input type="number" name="height_variant[]"
                                                            class="form-control" value="{{ $stok->height }}"
                                                            step="any">
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-muted text-center">Belum ada varian stok & harga.</p>
                                        @endif
                                    </div>
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
                                                <option value="ya"
                                                    {{ old('eu1935', $produk->eu1935) == 'ya' ? 'selected' : '' }}>Ya
                                                </option>
                                                <option value="tidak"
                                                    {{ old('eu1935', $produk->eu1935) == 'tidak' ? 'selected' : '' }}>Tidak
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Certification FDA</label>
                                            <select name="fda" class="form-select">
                                                <option value="ya"
                                                    {{ old('fda', $produk->fda) == 'ya' ? 'selected' : '' }}>Ya</option>
                                                <option value="tidak"
                                                    {{ old('fda', $produk->fda) == 'tidak' ? 'selected' : '' }}>Tidak
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Certification USP Class VI</label>
                                            <select name="usp" class="form-select">
                                                <option value="ya"
                                                    {{ old('usp', $produk->usp) == 'ya' ? 'selected' : '' }}>Ya</option>
                                                <option value="tidak"
                                                    {{ old('usp', $produk->usp) == 'tidak' ? 'selected' : '' }}>Tidak
                                                </option>
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
                                                        {{ old('id_environmant', $produk->id_environmant) == $env->id ? 'selected' : '' }}>
                                                        {{ $env->envi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Pressure</label>
                                            <input type="text" name="pressure" class="form-control"
                                                value="{{ old('pressure', $produk->pressure) }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Shaft Hardness</label>
                                            <input type="text" name="mating" class="form-control"
                                                value="{{ old('mating', $produk->mating) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Max PV (Mpa x m/s)</label>
                                            <input type="number" step="0.01" name="max_pv" class="form-control"
                                                value="{{ old('max_pv', $produk->max_pv) }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Maximum P (MPa)</label>
                                            <input type="text" name="maximum_p" class="form-control"
                                                value="{{ old('maximum_p', $produk->maximum_p) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Max V (m/s)</label>
                                            <input type="number" step="0.01" name="max_v" class="form-control"
                                                value="{{ old('max_v', $produk->max_v) }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Friction – static & dynamic​</label>
                                            <input type="text" step="0.01" name="friction" class="form-control"
                                                value="{{ old('friction', $produk->friction) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Elongation ASTM D638</label>
                                            <input type="text" step="0.01" name="elongation" class="form-control"
                                                value="{{ old('elongation', $produk->elongation) }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Deformation Under Load</label>
                                            <input type="number" step="0.01" name="deformation" class="form-control"
                                                value="{{ old('deformation', $produk->deformation) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tensile Strength (MPa)</label>
                                            <input type="number" step="0.01" name="tensile" class="form-control"
                                                value="{{ old('tensile', $produk->tensile) }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Specific Gravity</label>
                                        <input type="number" step="0.01" name="spesific" class="form-control"
                                            value="{{ old('spesific', $produk->spesific) }}">
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
                                        @if ($produk->gambar)
                                            <div class="mb-2">
                                                <img src="{{ asset('backend/assets/media/produk/' . $produk->gambar) }}"
                                                    alt="Preview" class="img-thumbnail" width="150">
                                            </div>
                                        @endif
                                        <input type="file" name="gambar" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status Aktif</label>
                                        <select name="status_aktif" class="form-select">
                                            <option value="1"
                                                {{ old('status_aktif', $produk->status_aktif) == '1' ? 'selected' : '' }}>
                                                Aktif</option>
                                            <option value="0"
                                                {{ old('status_aktif', $produk->status_aktif) == '0' ? 'selected' : '' }}>
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
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
