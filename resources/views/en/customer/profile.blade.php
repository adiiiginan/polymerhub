@extends('en.layouts.app')

@section('title', 'Profile Customer')

@section('content')
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush
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
                                                    <p class="mb-0"><strong>Name:</strong></p>
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
                                                    <p class="mb-0"><strong>Phone:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0">{{ $user->detail?->no_hp ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="row py-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Address:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0">{{ $user->detail?->alamat ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="row py-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Company:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0">{{ $user->detail?->perusahaan ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="row py-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Position:</strong></p>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p class="text-muted mb-0">{{ $user->detail?->jabatan ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="row py-2">
                                                <div class="col-sm-4">
                                                    <p class="mb-0"><strong>Country:</strong></p>
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
                                        <h2>Shipping Address</h2>
                                    </div>
                                    <div class="card-toolbar">
                                        <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#addAddressModal">
                                            Add New Address
                                        </button>
                                    </div>
                                </div>


                                <div class="card-body pt-0">
                                    @forelse ($user->addresses as $address)
                                        <div class="card card-body p-5 mb-5 shadow-sm">
                                            <h5 class="card-title">
                                                {{ $address->nama }}
                                                @if ($address->is_primary)
                                                    <span class="badge bg-primary">Primary</span>
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

                                                <form action="{{ route('en.customer.address.destroy', $address->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger me-2"
                                                        onclick="return confirm('Are you sure you want to delete this address?')">Delete</button>
                                                </form>
                                                @if (!$address->is_primary)
                                                    <form
                                                        action="{{ route('en.customer.address.set-primary', $address->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-light-primary">Set as
                                                            Primary</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center p-10">
                                            <p>No shipping addresses found.</p>
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

    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Add New Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('en.customer.address.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nama" class="form-label">Name</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select custom-select2" id="country_select" name="idcountry"
                                data-control="select2" data-placeholder="Select a country..." required>
                                <option></option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" data-country-iso="{{ $country->iso2 }}">
                                        {{ $country->country_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select custom-select2" id="state_select" name="state"
                                data-control="select2" data-placeholder="Select a state..." required>
                                <option></option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <select class="form-select custom-select2" id="city_select" name="city"
                                data-control="select2" data-placeholder="Select a city..." required>
                                <option></option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="zip_code" class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code"
                                placeholder="Enter zip code" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Address</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_alamat" class="form-label">Address Type</label>
                            <select class="form-select" id="jenis_alamat" name="jenis_alamat" required>
                                <option value="home">Home</option>
                                <option value="office">Office</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // This block is being replaced by the more complete country_select change handler below
            // which handles states, cities, and zip codes.

            // Initialize Select2
            function formatState(state) {
                if (!state.id) {
                    return state.text;
                }
                var $state = $(
                    '<span style="color: black;">' + state.text + '</span>'
                );
                return $state;
            };

            $('#country_select').select2({
                dropdownParent: $('#addAddressModal'),
                templateResult: formatState,
                templateSelection: formatState
            });

            $('#state_select').select2({
                dropdownParent: $('#addAddressModal'),
                templateResult: formatState,
                templateSelection: formatState
            });

            $('#city_select').select2({
                dropdownParent: $('#addAddressModal'),
                templateResult: formatState,
                templateSelection: formatState
            });

            $('#country_select').change(function() {
                var countryIso = $(this).find(':selected').data('country-iso');
                console.log('Country changed, fetching data for:', countryIso); // For debugging

                // Clear dependent dropdowns
                $('#state_select').empty().append('<option value=""></option>').trigger('change');
                $('#city_select').empty().append('<option value=""></option>').trigger('change');

                if (countryIso) {
                    // Fetch states
                    $.ajax({
                        url: '/en/customer/get-states/' + countryIso,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#state_select').append('<option value="' + value
                                    .state_code + '">' + value.state_name +
                                    '</option>');
                            });
                        }
                    });
                }
            });

            $('#state_select').change(function() {
                var stateCode = $(this).val();
                var countryIso = $('#country_select').find(':selected').data('country-iso');
                console.log('State changed. countryIso:', countryIso, 'stateCode:',
                    stateCode); // Debugging line
                if (stateCode && countryIso) {
                    $.ajax({
                        url: '/en/customer/get-cities/' + countryIso + '/' + stateCode,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#city_select').empty().append('<option value=""></option>');
                            $.each(data, function(key, value) {
                                $('#city_select').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
