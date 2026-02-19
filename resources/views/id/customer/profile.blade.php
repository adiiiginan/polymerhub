@extends('id.layouts.app')

@section('title', 'Profil Pelanggan')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <!--begin::Navbar-->
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-body pt-9 pb-0">
                            <!--begin::Details-->
                            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                                <!--begin: Pic-->

                                <!--end::Pic-->
                                <!--begin::Info-->

                                <!--end::Info-->
                            </div>
                            <!--end::Details-->
                        </div>
                    </div>
                    <!--end::Navbar-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-5 mb-xl-10">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="me-7 mb-4">
                                                <div
                                                    class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                                    <img src="{{ asset('/backend/assets/media/avatars/blank.png') }}"
                                                        alt="image" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row py-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Nama:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0">{{ $user->detail?->nama ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="row py-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Email:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0">{{ $user->email ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="row py-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Telepon:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0">{{ $user->detail?->no_hp ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="row py-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Alamat:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0">{{ $user->detail?->alamat ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="row py-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Perusahaan:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0">{{ $user->detail?->perusahaan ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="row py-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Jabatan:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0">{{ $user->detail?->jabatan ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="row py-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Negara:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0">
                                                        {{ $user->detail?->negara?->nama_negara ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-flush">
                                <div class="card-header mt-6">
                                    <div class="card-title">
                                        <h2>Alamat Pengiriman</h2>
                                    </div>
                                    <div class="card-toolbar">
                                        <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#addAddressModal">
                                            Tambah Alamat Baru
                                        </button>
                                    </div>
                                </div>


                                <div class="card-body pt-0">
                                    @forelse ($user->addresses as $address)
                                        <div class="card card-body p-5 mb-5 shadow-sm">
                                            <h5 class="card-title">
                                                {{ $address->nama }}
                                                @if ($address->is_primary)
                                                    <span class="badge bg-primary">Utama</span>
                                                @endif
                                            </h5>
                                            <p class="card-text">{{ $address->phone }}</p>
                                            <p class="card-text">
                                                {{ $address->alamat }}<br>
                                                {{ $address->province->name ?? '' }}<br>
                                                {{ $address->city->nama_kota ?? '' }} {{ $address->zip_code }}<br>
                                                {{ $address->country->nama_negara ?? '' }}
                                            </p>
                                            <div class="mt-3">
                                                <a href="{{ route('id.customer.address.edit', $address->id) }}"
                                                    class="btn btn-sm btn-light btn-active-light-primary me-2">Ubah</a>
                                                <form action="{{ route('id.customer.address.destroy', $address->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger me-2"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">Hapus</button>
                                                </form>
                                                @if (!$address->is_primary)
                                                    <form
                                                        action="{{ route('id.customer.address.set-primary', $address->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-light-primary">Jadikan
                                                            Utama</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center p-10">
                                            <p>Tidak ada alamat pengiriman.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const kotaData = @json($kota);
            const kecamatanData = @json($kecamatan);
            const kelurahanData = @json($kelurahan);
            const kodePosData = @json($kode_pos);

            $('#provinsi').on('change', function() {
                const provinsiId = $(this).val();
                const kotaDropdown = $('#kota');
                kotaDropdown.empty().append('<option value="">Pilih Kota</option>');

                if (provinsiId) {
                    const filteredKota = kotaData.filter(function(k) {
                        return k.provinsi_id == provinsiId;
                    });

                    filteredKota.forEach(function(k) {
                        kotaDropdown.append($('<option>', {
                            value: k.id,
                            text: k.nama
                        }));
                    });
                }
                // Trigger change to re-initialize select2 if needed
                kotaDropdown.trigger('change');
            });

            $('#kota').change(function() {
                const kotaId = $(this).val();
                const kecamatanDropdown = $('#kecamatan');
                kecamatanDropdown.empty().append('<option value="">Pilih Kecamatan</option>');

                if (kotaId) {
                    const filteredKecamatan = kecamatanData.filter(function(k) {
                        return k.kota_id == kotaId;
                    });

                    filteredKecamatan.forEach(function(k) {
                        kecamatanDropdown.append($('<option>', {
                            value: k.id,
                            text: k.nama
                        }));
                    });
                }
                kecamatanDropdown.trigger('change');
            });

            $('#kecamatan').change(function() {
                const kecamatanId = $(this).val();
                const kelurahanDropdown = $('#kelurahan');
                kelurahanDropdown.empty().append('<option value="">Pilih Kelurahan</option>');

                if (kecamatanId) {
                    const filteredKelurahan = kelurahanData.filter(function(k) {
                        return k.kecamatan_id == kecamatanId;
                    });

                    filteredKelurahan.forEach(function(k) {
                        kelurahanDropdown.append($('<option>', {
                            value: k.id,
                            text: k.nama
                        }));
                    });
                }
                kelurahanDropdown.trigger('change');
            });

            $('#kelurahan').change(function() {
                const kelurahanId = $(this).val();
                const zipCodeDropdown = $('#zip_code');
                zipCodeDropdown.empty().append('<option value="">Pilih Kode Pos</option>');

                if (kelurahanId) {
                    const filteredKodePos = kodePosData.filter(function(k) {
                        return k.kelurahan_id == kelurahanId;
                    });

                    filteredKodePos.forEach(function(k) {
                        zipCodeDropdown.append($('<option>', {
                            value: k.kode_pos,
                            text: k.kode_pos
                        }));
                    });
                }
                zipCodeDropdown.trigger('change');
            });
        });
    </script>
@endpush

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addAddressForm" action="{{ route('id.customer.address.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Add New Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Penerima</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label for="provinsi" class="form-label">Provinsi</label>
                                <select class="form-select" data-control="select2" data-placeholder="Pilih Provinsi"
                                    id="provinsi" name="provinsi" required>
                                    <option></option>
                                    @foreach ($provinsi as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="city" class="form-label">Kota</label>
                                <select class="form-select" data-control="select2" id="kota" name="kota"
                                    required>
                                    <option value="">Pilih Kota</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                <select class="form-select" data-control="select2" data-placeholder="Pilih kecamatan"
                                    id="kecamatan" name="kecamatan" required>
                                    <option></option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="kelurahan" class="form-label">Kelurahan</label>
                                <select class="form-select" data-control="select2" data-placeholder="Pilih kelurahan"
                                    id="kelurahan" name="kelurahan" required>
                                    <option></option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="zip_code" class="form-label">Kode Pos</label>
                                <select class="form-select" id="zip_code" name="zip_code" required>
                                    <option value="">Pilih Kode Pos</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="address_type" class="form-label">Address Type</label>
                                <select class="form-select" id="address_type" name="address_type">
                                    <option value="home" selected>Home</option>
                                    <option value="office">Office</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
