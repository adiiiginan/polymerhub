@extends('layouts.app')

@section('title', 'Profile Customer')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                    <!--begin::Toolbar wrapper-->

                    <!--end::Toolbar wrapper-->
                </div>
                <!--end::Toolbar container-->
            </div>
            <!--end::Toolbar-->
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <!--begin::Navbar-->
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-body pt-9 pb-0">
                            <!--begin::Details-->
                            <div class="d-flex flex-wrap flex-sm-nowrap">
                                <!--begin: Pic-->
                                <div class="me-7 mb-4">
                                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                        <img src="{{ asset('/backend/assets/media/avatars/blank.png') }}" alt="image" />
                                        <div
                                            class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                                        </div>
                                    </div>
                                </div>
                                <!--end::Pic-->
                                <!--begin::Info-->
                                <div class="flex-grow-1">
                                    <!--begin::Title-->
                                    <div
                                        class="d-flex justify-content-between align-items-start flex-wrap mb-2"style="
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    margin-top: 30px;">
                                        <!--begin::User-->
                                        <div class="d-flex flex-column">
                                            <!--begin::Name-->
                                            <div class="d-flex align-items-center mb-2">
                                                <a href="#"
                                                    class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $user->detail->nama ?? '-' }}</a>
                                                <a href="#">
                                                    <i class="ki-outline ki-verify fs-1 text-primary"></i>
                                                </a>
                                            </div>
                                            <!--end::Name-->
                                            <!--begin::Info-->
                                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                                <a href="#"
                                                    class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                                    <i class="ki-outline ki-profile-circle fs-4 me-1"></i>
                                                    {{ $user->detail->perusahaan ?? '-' }}</a>

                                                <a href="#"
                                                    class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                                    <i class="ki-outline ki-briefcase fs-4 me-1"></i>
                                                    {{ $user->detail->jabatan ?? 'Jabatan tidak tersedia' }}</a>
                                                <a href="#"
                                                    class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                                    <i class="ki-outline ki-geolocation fs-4 me-1"></i>
                                                    {{ $user->detail->negara->nama_negara ?? '-' }}</a>
                                                <a href="#"
                                                    class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                                                    <i class="ki-outline ki-sms fs-4"></i> {{ $user->email ?? '-' }}</a>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::User-->
                                        <!--begin::Actions-->
                                        <div class="d-flex my-4 align-items-center gap-2">
                                            {{-- Tombol Verified --}}
                                            @if ((int) $user->status === 1)
                                                <span class="badge bg-success btn-sm d-flex align-items-center">
                                                    <i class="fas fa-check fs-2 me-1"></i> Sudah Diverifikasi
                                                </span>
                                            @else
                                                <a href="{{ route('user.verify', $user->id) }}"
                                                    class="btn btn-success btn-sm"
                                                    onclick="return confirm('Yakin ingin memverifikasi user ini?')">
                                                    <i class="fas fa-check me-1"></i> Verifikasi
                                                </a>

                                                {{-- Tombol Decline - hanya tampil jika belum verified --}}
                                                <a href="#" class="btn btn-sm btn-primary d-flex align-items-center"
                                                    data-bs-toggle="modal" data-bs-target="#kt_modal_offer_a_deal">
                                                    <i class="ki-outline ki-cross fs-2 me-1"></i> Decline
                                                </a>
                                            @endif
                                        </div>


                                        <!--end::Actions-->
                                    </div>
                                    <!--end::Title-->
                                    <!--begin::Stats-->
                                    <div class="d-flex flex-wrap flex-stack">
                                        <!--begin::Wrapper-->
                                        <div class="d-flex flex-column flex-grow-1 pe-8">
                                            <!--begin::Actions-->
                                            <div class="d-flex flex-wrap">

                                            </div>
                                            <!--end::Actions-->
                                        </div>
                                    </div>

                                    <!--end::Stats-->
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Details-->
                            <!--begin::Navs-->
                            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                                <li class="nav-item mt-2">
                                    <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab"
                                        href="#kt_profile_details_view">Profile Details</a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab"
                                        href="#kt_shipping_address">Alamat Pengiriman</a>
                                </li>
                            </ul>
                            <!--end::Navs-->
                        </div>
                    </div>
                    <!--end::Navbar-->
                    <!--begin::Tab content-->
                    <div class="tab-content" id="myTabContent">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade show active" id="kt_profile_details_view" role="tabpanel">
                            <div class="card mb-5 mb-xl-10">
                                <!--begin::Card header-->
                                <div class="card-header cursor-pointer">
                                    <!--begin::Card title-->
                                    <div class="card-title m-0">
                                        <h3 class="fw-bold m-0">Profile Details</h3>
                                    </div>
                                    <!--end::Card title-->
                                    <!--begin::Action-->

                                </div>
                                <!--begin::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body p-9">
                                    <!--begin::Row-->
                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">Nama Lengkap</label>
                                        <div class="col-lg-8">
                                            <span class="fw-bold fs-6 text-gray-800">{{ $user->detail->nama ?? '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">Perusahaan </label>
                                        <div class="col-lg-8 fv-row">
                                            <span
                                                class="fw-semibold text-gray-800 fs-6">{{ $user->detail->perusahaan ?? '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">No Handphone
                                            <span class="ms-1" data-bs-toggle="tooltip"
                                                title="Phone number must be active">
                                                <i class="ki-outline ki-information fs-7"></i>
                                            </span>
                                        </label>
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <span
                                                class="fw-bold fs-6 text-gray-800 me-2">{{ $user->detail->no_hp ?? '-' }}</span>

                                        </div>
                                    </div>

                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">Alamat Perusahaan</label>
                                        <div class="col-lg-8">
                                            <a href="{{ $user->detail->alamat ?? '-' }}" target="_blank"
                                                class="fw-semibold fs-6 text-gray-800 text-hover-primary">
                                                {{ $user->detail->alamat ?? '-' }}
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">Jenis Perusahaan
                                            <span class="ms-1" data-bs-toggle="tooltip" title="Country of origination">
                                                <i class="ki-outline ki-information fs-7"></i>
                                            </span>
                                        </label>
                                        <div class="col-lg-8">
                                            <span
                                                class="fw-bold fs-6 text-gray-800">{{ $user->detail->perusahaan ?? '-' }}</span>

                                        </div>
                                    </div>

                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">Kualifikasi</label>
                                        <div class="col-lg-8">
                                            <span
                                                class="fw-bold fs-6 text-gray-800">{{ $user->detail->klasifikasi->nama ?? '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">NPWP</label>
                                        <div class="col-lg-8">
                                            <span class="fw-bold fs-6 text-gray-800">
                                                {{ $user->detail->vat ?? '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">Negara</label>
                                        <div class="col-lg-8">
                                            <span class="fw-bold fs-6 text-gray-800">
                                                {{ $user->detail->negara->nama_negara ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">Kota</label>
                                        <div class="col-lg-8">
                                            <span class="fw-bold fs-6 text-gray-800">
                                                {{ $user->detail->city ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">kodePos</label>
                                        <div class="col-lg-8">
                                            <span class="fw-bold fs-6 text-gray-800">
                                                {{ $user->detail->zip ?? '-' }}</span>
                                        </div>
                                    </div>


                                </div>



                            </div>
                        </div>

                    </div>
                    <!--end::Tab pane-->
                    <!--begin::Tab pane-->
                    <div class="tab-pane fade" id="kt_shipping_address" role="tabpanel">
                        <div class="card mb-5 mb-xl-10">
                            <!--begin::Card header-->
                            <div class="card-header cursor-pointer">
                                <!--begin::Card title-->
                                <div class="card-title m-0">
                                    <h3 class="fw-bold m-0">Shipping Addres </h3>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--begin::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body p-9">
                                <div class="row g-6 g-xl-9">
                                    @foreach ($user->addresses as $address)
                                        <div class="col-md-6">
                                            <!--begin::Card-->
                                            <div class="card card-dashed h-100">
                                                <!--begin::Card body-->
                                                <div class="card-body d-flex flex-column p-9">
                                                    <!--begin::Content-->
                                                    <div class="d-flex flex-stack mb-5">
                                                        <!--begin::Title-->
                                                        <div class="d-flex flex-column">
                                                            <div class="fs-6 fw-bold text-gray-800">
                                                                {{ $address->nama ?? 'N/A' }}|
                                                                {{ $address->phone ?? 'N/A' }}
                                                            </div>
                                                        </div>
                                                        <!--end::Title-->
                                                        <!--begin::Toolbar-->
                                                        <div class="card-toolbar">
                                                            <!--begin::Menu-->
                                                            <a href="#"
                                                                class="btn btn-sm btn-light btn-active-light-primary"
                                                                data-kt-menu-trigger="click"
                                                                data-kt-menu-placement="bottom-end">Aksi
                                                                <i class="ki-outline ki-down fs-5 ms-1"></i>
                                                            </a>
                                                            <!--begin::Menu 2-->
                                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                                                data-kt-menu="true">
                                                                <!--begin::Menu item-->
                                                                <div class="menu-item px-3">
                                                                    <a href="{{ route('customer.address.edit', $address) }}"
                                                                        class="menu-link px-3">Edit</a>
                                                                </div>
                                                                <!--end::Menu item-->
                                                                <!--begin::Menu item-->
                                                                <div class="menu-item px-3">
                                                                    <form
                                                                        action="{{ route('customer.address.delete', $address) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="menu-link px-3 btn btn-link text-danger">Hapus</button>
                                                                    </form>
                                                                </div>
                                                                <!--end::Menu item-->
                                                            </div>
                                                            <!--end::Menu 2-->
                                                            <!--end::Menu-->
                                                        </div>
                                                        <!--end::Toolbar-->
                                                    </div>
                                                    <div class="fs-7 text-muted mb-5">
                                                        {{ $address->nama }}<br>
                                                        {{ $address->alamat }}<br>
                                                        {{ $address->city }}, {{ $address->zip_code }}<br>
                                                        {{ $address->country->nama_negara ?? 'N/A' }},
                                                        {{ $address->phone }}
                                                    </div>
                                                    <!--end::Content-->
                                                    <!--begin::Footer-->
                                                    <div class="d-flex flex-stack mt-auto">
                                                        <!--begin::Actions-->
                                                        @if (!$address->is_primary)
                                                            <form
                                                                action="{{ route('customer.address.set-primary', $address) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-light-primary">Atur sebagai
                                                                    utama</button>
                                                            </form>
                                                        @endif
                                                        <!--end::Actions-->
                                                        <!--begin::Tags-->
                                                        <div>
                                                            @if ($address->is_primary)
                                                                <span class="badge badge-light-success me-2">Utama</span>
                                                            @endif
                                                            @if ($address->is_store_address)
                                                                <span class="badge badge-light-info">Alamat Toko</span>
                                                            @endif
                                                        </div>
                                                        <!--end::Tags-->
                                                    </div>
                                                    <!--end::Footer-->
                                                </div>
                                                <!--end::Card body-->
                                            </div>
                                            <!--end::Card-->
                                        </div>
                                    @endforeach
                                    <div class="col-md-6">
                                        <!--begin::Card-->
                                        <div class="card card-dashed h-100">
                                            <!--begin::Card body-->
                                            <div class="card-body d-flex flex-center">
                                                <!--begin::Button-->
                                                <button type="button"
                                                    class="btn btn-clear d-flex flex-column flex-center"
                                                    data-bs-toggle="modal" data-bs-target="#kt_modal_add_address">
                                                    <!--begin::Illustration-->
                                                    <img src="{{ asset('/backend/assets/media/illustrations/sketchy-1/4.png') }}"
                                                        alt="" class="mw-100 mh-150px">
                                                    <!--end::Illustration-->
                                                    <!--begin::Label-->
                                                    <div class="fw-bold fs-3 text-gray-600 text-hover-primary">Tambah
                                                        Alamat Baru</div>
                                                    <!--end::Label-->
                                                </button>
                                                <!--end::Button-->
                                            </div>
                                            <!--end::Card body-->
                                        </div>
                                        <!--end::Card-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                    </div>
                    <!--end::Tab pane-->
                </div>
                <!--end::Tab content-->
                <!--begin::Row-->

                <!--end::Row-->
                <!--begin::Row-->

                <!--end::Row-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
    <!--begin::Footer-->

    <!--end::Footer-->
    </div>
@endsection

@push('modals')
    <!--begin::Modal - Add Address-->
    <div class="modal fade" id="kt_modal_add_address" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
                <form class="form" action="{{ route('customer.address.store') }}" method="POST"
                    id="kt_modal_add_address_form">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_add_address_header">
                        <!--begin::Modal title-->
                        <h2>Tambah Alamat Baru</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body py-10 px-lg-17">
                        <!--begin::Scroll-->
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_add_address_scroll" data-kt-scroll="true"
                            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_modal_add_address_header"
                            data-kt-scroll-wrappers="#kt_modal_add_address_scroll" data-kt-scroll-offset="300px">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Nama Penerima</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" placeholder=""
                                    name="nama" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Nomor Telepon</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" placeholder=""
                                    name="phone" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Alamat Lengkap</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea name="alamat" class="form-control form-control-solid" rows="3"></textarea>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row g-9 mb-7">
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2">Kota</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" placeholder=""
                                        name="city" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row g-9 mb-7">
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2">Kode Pos</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" placeholder=""
                                        name="zip_code" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2">Negara</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="idcountry" aria-label="Pilih Negara" data-control="select2"
                                        data-placeholder="Pilih Negara..." data-dropdown-parent="#kt_modal_add_address"
                                        class="form-select form-select-solid fw-bold">
                                        <option value="">Pilih Negara...</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->nama_negara }}</option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_primary"
                                        name="is_primary" />
                                    <label class="form-check-label" for="is_primary">
                                        Jadikan Alamat Utama
                                    </label>
                                </div>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_add_address_cancel" class="btn btn-light me-3">Batal</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" id="kt_modal_add_address_submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <!--end::Modal - Add Address-->
@endpush
