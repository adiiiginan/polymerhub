@extends('id.layouts.app')

@section('title', 'Profil Pelanggan')

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Fraunces:ital,wght@0,300;0,600;1,300&display=swap');

        .profile-wrapper {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            padding: 32px 0;
        }

        /* ── Hero Banner ── */
        .profile-hero {
            background: linear-gradient(135deg, #0f1c2e 0%, #1a3a5c 60%, #1e5799 100%);
            border-radius: 20px;
            padding: 36px 40px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

        .profile-hero::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
            pointer-events: none;
        }

        .profile-hero::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: 30%;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .03);
            pointer-events: none;
        }

        .hero-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .25);
            flex-shrink: 0;
        }

        .hero-online-dot {
            width: 18px;
            height: 18px;
            background: #22c55e;
            border-radius: 50%;
            border: 3px solid #fff;
            position: absolute;
            bottom: 2px;
            right: 2px;
        }

        .hero-name {
            font-family: 'Fraunces', serif;
            font-size: 22px;
            font-weight: 600;
            color: #fff;
            margin: 0;
        }

        .hero-meta-item {
            font-size: 13px;
            color: rgba(255, 255, 255, .55);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .hero-meta-item i {
            width: 14px;
            text-align: center;
        }

        .badge-verified {
            background: #fff;
            color: #3b6fd4;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: .3px;
        }

        .hero-stat-box {
            background: rgba(255, 255, 255, .1);
            border-radius: 14px;
            padding: 16px 20px;
            backdrop-filter: blur(4px);
        }

        .hero-stat-label {
            font-size: 11px;
            color: rgba(255, 255, 255, .5);
            font-weight: 500;
            margin-bottom: 4px;
        }

        .hero-stat-value {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 10px;
        }

        .badge-active {
            background: #22c55e;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 14px;
            border-radius: 20px;
            display: inline-block;
        }

        /* ── Profile Card ── */
        .profile-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 32px rgba(0, 0, 0, .08);
            padding: 28px 32px;
            position: relative;
            z-index: 2;
        }


        .user-name {
            font-family: 'Fraunces', serif;
            font-size: 22px;
            font-weight: 600;
            color: #0f1c2e;
            margin: 0 0 2px;
        }

        .user-role {
            font-size: 13px;
            color: #6b7280;
            font-weight: 400;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #f0f4ff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #3b6fd4;
            font-size: 14px;
        }

        .info-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #9ca3af;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #1f2937;
            margin: 0;
        }

        /* ── Section Card ── */
        .section-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 32px rgba(0, 0, 0, .08);
            overflow: hidden;
        }

        .section-header {
            padding: 22px 28px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            font-family: 'Fraunces', serif;
            font-size: 18px;
            font-weight: 600;
            color: #0f1c2e;
            margin: 0;
        }

        .section-body {
            padding: 20px 28px;
        }

        /* ── Address Card ── */
        .address-card {
            border: 1.5px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 14px;
            transition: border-color .2s, box-shadow .2s;
            position: relative;
        }

        .address-card:hover {
            border-color: #3b6fd4;
            box-shadow: 0 4px 16px rgba(59, 111, 212, .1);
        }

        .address-card.is-primary {
            border-color: #3b6fd4;
            background: #f8faff;
        }

        .address-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f1c2e;
            margin-bottom: 2px;
        }

        .address-phone {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .address-detail {
            font-size: 13px;
            color: #374151;
            line-height: 1.7;
            margin-bottom: 14px;
        }

        .badge-primary-addr {
            position: absolute;
            top: 16px;
            right: 16px;
            background: #3b6fd4;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .6px;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 20px;
        }

        /* ── Buttons ── */
        .btn-add-address {
            background: #3b6fd4;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background .2s, transform .1s;
        }

        .btn-add-address:hover {
            background: #2d5bb5;
            transform: translateY(-1px);
        }

        .btn-addr {
            border: none;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: .15s;
        }

        .btn-addr-edit {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-addr-edit:hover {
            background: #e5e7eb;
        }

        .btn-addr-del {
            background: #fef2f2;
            color: #ef4444;
        }

        .btn-addr-del:hover {
            background: #fee2e2;
        }

        .btn-addr-primary {
            background: #eff6ff;
            color: #3b6fd4;
        }

        .btn-addr-primary:hover {
            background: #dbeafe;
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #9ca3af;
        }

        .empty-state svg {
            margin-bottom: 14px;
            opacity: .4;
        }

        .empty-state p {
            font-size: 14px;
            margin: 0;
        }

        /* ── Modal Redesign ── */
        .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(0, 0, 0, .18);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .modal-header {
            background: linear-gradient(135deg, #0f1c2e, #1a3a5c);
            padding: 24px 28px;
            border: none;
        }

        .modal-title {
            font-family: 'Fraunces', serif;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
        }

        .modal-header .btn-close {
            filter: invert(1) brightness(2);
            opacity: .7;
        }

        .modal-body {
            padding: 28px;
            background: #fff;
        }

        .modal-footer {
            background: #f9fafb;
            border-top: 1px solid #f3f4f6;
            padding: 16px 28px;
        }

        .form-section-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f3f4f6;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-control,
        .form-select {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1f2937;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3b6fd4;
            box-shadow: 0 0 0 3px rgba(59, 111, 212, .12);
            outline: none;
        }

        textarea.form-control {
            resize: none;
        }

        .btn-modal-cancel {
            background: #f3f4f6;
            color: #374151;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
        }

        .btn-modal-save {
            background: #3b6fd4;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 24px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: background .2s;
        }

        .btn-modal-save:hover {
            background: #2d5bb5;
        }
    </style>

    <div class="profile-wrapper">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div id="kt_app_content_container" class="app-container container-xxl">

                        {{-- ── Hero ── --}}
                        <div class="profile-hero mb-4">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="d-flex align-items-center gap-4">
                                        <!-- Avatar -->
                                        <div class="position-relative flex-shrink-0">
                                            <img src="{{ asset('/backend/assets/media/avatars/blank.png') }}"
                                                class="hero-avatar" alt="avatar">
                                            <span class="hero-online-dot"></span>
                                        </div>

                                        <!-- User Info -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <h4 class="hero-name">
                                                    {{ $user->detail?->nama ?? $user->name }}
                                                </h4>
                                                <span class="badge-verified">Verified</span>
                                            </div>

                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <span class="hero-meta-item">
                                                        <i class="fas fa-building"></i>
                                                        {{ $user->detail?->perusahaan ?? '-' }}
                                                    </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span class="hero-meta-item">
                                                        <i class="fas fa-briefcase"></i>
                                                        {{ $user->detail?->jabatan ?? '-' }}
                                                    </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span class="hero-meta-item">
                                                        <i class="fas fa-phone"></i>
                                                        {{ $user->detail?->no_hp ?? '-' }}
                                                    </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <span class="hero-meta-item">
                                                        <i class="fas fa-envelope"></i>
                                                        {{ $user->detail?->email ?? $user->email }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Side Info -->
                                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                                    <div class="hero-stat-box">


                                        <p class="hero-stat-label mb-1">Status Akun</p>
                                        <span class="badge-active">Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Main Grid ── --}}
                        <div class="row g-4" style="position:relative;z-index:2;">

                            {{-- ── Left: Extra Profile Details ── --}}
                            <div class="col-md-5">
                                <div class="profile-card h-100">
                                    <div class="d-flex align-items-center gap-2 mb-4 pb-3"
                                        style="border-bottom:1.5px solid #f3f4f6">
                                        <div style="width:6px;height:22px;background:#3b6fd4;border-radius:3px;"></div>
                                        <h6
                                            style="font-family:'Fraunces',serif;font-size:16px;font-weight:600;color:#0f1c2e;margin:0;">
                                            Informasi Detail</h6>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-icon"><i class="fas fa-envelope" style="font-size:13px;"></i></div>
                                        <div>
                                            <p class="info-label">Email</p>
                                            <p class="info-value">{{ $user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-icon"><i class="fas fa-phone" style="font-size:13px;"></i></div>
                                        <div>
                                            <p class="info-label">Telepon</p>
                                            <p class="info-value">{{ $user->detail?->no_hp ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-icon"><i class="fas fa-map-marker-alt" style="font-size:13px;"></i>
                                        </div>
                                        <div>
                                            <p class="info-label">Alamat</p>
                                            <p class="info-value">{{ $user->detail?->alamat ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-icon"><i class="fas fa-building" style="font-size:13px;"></i></div>
                                        <div>
                                            <p class="info-label">Perusahaan</p>
                                            <p class="info-value">{{ $user->detail?->perusahaan ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-icon"><i class="fas fa-briefcase" style="font-size:13px;"></i>
                                        </div>
                                        <div>
                                            <p class="info-label">Jabatan</p>
                                            <p class="info-value">{{ $user->detail?->jabatan ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-icon"><i class="fas fa-globe" style="font-size:13px;"></i></div>
                                        <div>
                                            <p class="info-label">Negara</p>
                                            <p class="info-value">{{ $user->detail?->negara?->nama_negara ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Right: Shipping Addresses ── --}}
                            <div class="col-md-7">
                                <div class="section-card h-100">
                                    <div class="section-header">
                                        <h2 class="section-title">Alamat Pengiriman</h2>
                                        <button type="button" class="btn-add-address" data-bs-toggle="modal"
                                            data-bs-target="#addAddressModal">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5">
                                                <line x1="12" y1="5" x2="12" y2="19" />
                                                <line x1="5" y1="12" x2="19" y2="12" />
                                            </svg>
                                            Tambah Alamat
                                        </button>
                                    </div>

                                    <div class="section-body">
                                        @forelse ($user->addresses as $address)
                                            <div class="address-card {{ $address->is_primary ? 'is-primary' : '' }}">
                                                @if ($address->is_primary)
                                                    <span class="badge-primary-addr">⭐ Utama</span>
                                                @endif

                                                <p class="address-name">{{ $address->nama }}</p>
                                                <p class="address-phone">{{ $address->phone }}</p>
                                                <p class="address-detail">
                                                    {{ $address->alamat }}<br>
                                                    {{ $address->province->name ?? '' }},
                                                    {{ $address->city->nama_kota ?? '' }} {{ $address->zip_code }}<br>
                                                    {{ $address->country->nama_negara ?? '' }}
                                                </p>

                                                <div class="d-flex flex-wrap gap-2">
                                                    <a href="{{ route('id.customer.address.edit', $address->id) }}"
                                                        class="btn-addr btn-addr-edit">✏ Ubah</a>

                                                    <form
                                                        action="{{ route('id.customer.address.destroy', $address->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn-addr btn-addr-del"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                                            🗑 Hapus
                                                        </button>
                                                    </form>

                                                    @if (!$address->is_primary)
                                                        <form
                                                            action="{{ route('id.customer.address.set-primary', $address->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="btn-addr btn-addr-primary">
                                                                ⭐ Jadikan Utama
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="empty-state">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                                    stroke="#9ca3af" stroke-width="1.5">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                                    <circle cx="12" cy="10" r="3" />
                                                </svg>
                                                <p>Belum ada alamat pengiriman.<br>Tambahkan alamat pertama Anda.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                        </div>{{-- end row --}}
                    </div>
                </div>
            </div>
        </div>
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
                    kotaData.filter(k => k.provinsi_id == provinsiId)
                        .forEach(k => kotaDropdown.append($('<option>', {
                            value: k.id,
                            text: k.nama
                        })));
                }
                kotaDropdown.trigger('change');
            });

            $('#kota').change(function() {
                const kotaId = $(this).val();
                const kecamatanDropdown = $('#kecamatan');
                kecamatanDropdown.empty().append('<option value="">Pilih Kecamatan</option>');
                if (kotaId) {
                    kecamatanData.filter(k => k.kota_id == kotaId)
                        .forEach(k => kecamatanDropdown.append($('<option>', {
                            value: k.id,
                            text: k.nama
                        })));
                }
                kecamatanDropdown.trigger('change');
            });

            $('#kecamatan').change(function() {
                const kecamatanId = $(this).val();
                const kelurahanDropdown = $('#kelurahan');
                kelurahanDropdown.empty().append('<option value="">Pilih Kelurahan</option>');
                if (kecamatanId) {
                    kelurahanData.filter(k => k.kecamatan_id == kecamatanId)
                        .forEach(k => kelurahanDropdown.append($('<option>', {
                            value: k.id,
                            text: k.nama
                        })));
                }
                kelurahanDropdown.trigger('change');
            });

            $('#kelurahan').change(function() {
                const kelurahanId = $(this).val();
                const zipCodeDropdown = $('#zip_code');
                zipCodeDropdown.empty().append('<option value="">Pilih Kode Pos</option>');
                if (kelurahanId) {
                    kodePosData.filter(k => k.kelurahan_id == kelurahanId)
                        .forEach(k => zipCodeDropdown.append($('<option>', {
                            value: k.kode_pos,
                            text: k.kode_pos
                        })));
                }
                zipCodeDropdown.trigger('change');
            });
        });
    </script>
@endpush

{{-- ════════════════════════════════════════
     Modal: Tambah Alamat (routes & logic unchanged)
════════════════════════════════════════ --}}
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="addAddressForm" action="{{ route('id.customer.address.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Tambah Alamat Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        {{-- Left col --}}
                        <div class="col-md-6">
                            <p class="form-section-label">Informasi Penerima</p>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Penerima</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Masukkan nama penerima" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="Contoh: 08123456789" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="4" placeholder="Jalan, nomor, RT/RW, dll."
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="address_type" class="form-label">Tipe Alamat</label>
                                <select class="form-select" id="address_type" name="address_type">
                                    <option value="home" selected>🏠 Rumah</option>
                                    <option value="office">🏢 Kantor</option>
                                </select>
                            </div>
                        </div>

                        {{-- Right col --}}
                        <div class="col-md-6">
                            <p class="form-section-label">Lokasi Pengiriman</p>
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
                                <label for="kota" class="form-label">Kota / Kabupaten</label>
                                <select class="form-select" data-control="select2" id="kota" name="kota"
                                    required>
                                    <option value="">Pilih Kota</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                <select class="form-select" data-control="select2" data-placeholder="Pilih Kecamatan"
                                    id="kecamatan" name="kecamatan" required>
                                    <option></option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="kelurahan" class="form-label">Kelurahan</label>
                                <select class="form-select" data-control="select2" data-placeholder="Pilih Kelurahan"
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
                        </div>
                    </div>
                </div>

                <div class="modal-footer gap-2">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-modal-save">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>
