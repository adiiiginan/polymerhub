@extends('Admin.dashboard')

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
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <!--begin::Navbar-->
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-body pt-9 pb-0">
                            <!--begin::Details-->
                            <div class="d-flex flex-wrap flex-sm-nowrap">
                                <!--begin: Pic-->
                                <div class="me-7 mb-4">
                                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                        <img src="{{ asset('/backend/assets/media/avatars/300-1.jpg') }}" alt="image" />
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

                            <!--begin::Navs-->
                        </div>
                    </div>
                    <!--end::Navbar-->
                    <!--begin::details View-->
                    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
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
                                    <span class="ms-1" data-bs-toggle="tooltip" title="Phone number must be active">
                                        <i class="ki-outline ki-information fs-7"></i>
                                    </span>
                                </label>
                                <div class="col-lg-8 d-flex align-items-center">
                                    <span class="fw-bold fs-6 text-gray-800 me-2">{{ $user->detail->no_hp ?? '-' }}</span>

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
                                    <span class="fw-bold fs-6 text-gray-800">{{ $user->detail->perusahaan ?? '-' }}</span>

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
                                    <span class="fw-bold fs-6 text-gray-800"> {{ $user->detail->vat ?? '-' }}</span>
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

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Request</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">
                                        {{ $user->comment ?? '-' }}</span>
                                </div>
                            </div>


                            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                                <i class="ki-outline ki-information fs-2tx text-warning me-4"></i>
                                <div class="d-flex flex-stack flex-grow-1">
                                    <div class="fw-semibold">
                                        <h4 class="text-gray-900 fw-bold">We need your attention!</h4>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end::Card body-->
                    </div>
                    <!--end::details View-->
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
        <div id="kt_app_footer" class="app-footer">
            <!--begin::Footer container-->
            <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
                <!--begin::Copyright-->
                <div class="text-gray-900 order-2 order-md-1">
                    <span class="text-muted fw-semibold me-1">2025&copy;</span>
                    <a href="https://keenthemes.com" target="_blank" class="text-gray-800 text-hover-primary">Jaya Niaga
                        Semesta</a>
                </div>
                <!--end::Copyright-->
                <!--begin::Menu-->

            </div>
            <!--end::Footer container-->
        </div>
        <!--end::Footer-->
    </div>
@endsection
