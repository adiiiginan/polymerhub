@extends('admin.dashboard')

@section('title', 'User Detail')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Left Column: Profile Card -->
            <div class="col-lg-4 col-md-5">
                <div class="card card-user-profile">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img src="{{ asset('backend/assets/media/avatars/blank.png') }}" alt="User Avatar"
                                class="rounded-circle" width="120" height="120">
                        </div>
                        <h4 class="mb-1">{{ $user->userDetail->nama ?? $user->username }}</h4>
                        <p class="text-muted mb-2">{{ $user->email }}</p>
                        <span
                            class="badge badge-{{ $user->status_badge }}">{{ $user->stat->status ?? $user->status_text }}</span>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex flex-column align-items-stretch">
                            @if ($user->status == 2)
                                <form action="{{ route('admin.user.update.status') }}" method="POST" class="d-grid mb-2">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <input type="hidden" name="status" value="1">
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                                <form action="{{ route('admin.user.update.status') }}" method="POST" class="d-grid mb-2">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <input type="hidden" name="status" value="9">
                                    <button type="submit" class="btn btn-danger">Decline</button>
                                </form>
                            @endif
                            <a href="{{ route('admin.user.customer') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: User Details -->
            <div class="col-lg-8 col-md-7">
                <!-- User Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">User Information</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">Username</dt>
                            <dd class="col-sm-9">{{ $user->username }}</dd>

                            <dt class="col-sm-3">Role</dt>
                            <dd class="col-sm-9">{{ $user->priviladges->priviladges ?? 'No Role' }}</dd>

                            <dt class="col-sm-3">Joined</dt>
                            <dd class="col-sm-9">{{ $user->created_at->format('d F Y H:i:s') }}</dd>
                        </dl>
                    </div>
                </div>

                <!-- Contact & Company Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Contact & Company Information</h5>
                    </div>
                    <div class="card-body">
                        @if ($user->userDetail)
                            <dl class="row">
                                <dt class="col-sm-3">Full Name</dt>
                                <dd class="col-sm-9">{{ $user->userDetail->nama }}</dd>

                                <dt class="col-sm-3">Phone Number</dt>
                                <dd class="col-sm-9">{{ $user->userDetail->no_hp }}</dd>

                                <dt class="col-sm-3">Company</dt>
                                <dd class="col-sm-9">{{ $user->userDetail->perusahaan }}</dd>
                            </dl>
                        @else
                            <p class="text-muted mb-0">No contact or company details available.</p>
                        @endif
                    </div>
                </div>

                <!-- Address Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Address Information</h5>
                    </div>
                    <div class="card-body">
                        @if ($user->addresses->isNotEmpty())
                            @foreach ($user->addresses as $address)
                                <div class="address-block">
                                    <h6 class="d-flex justify-content-between">
                                        <span>Address {{ $loop->iteration }}</span>
                                        @if ($address->is_primary)
                                            <span class="badge badge-primary">Primary</span>
                                        @endif
                                    </h6>
                                    <dl class="row">
                                        <dt class="col-sm-3">Address</dt>
                                        <dd class="col-sm-9">{{ $address->alamat }}</dd>

                                        <dt class="col-sm-3">City</dt>
                                        <dd class="col-sm-9">{{ $address->city->nama_kota ?? 'N/A' }}</dd>

                                        <dt class="col-sm-3">State</dt>
                                        <dd class="col-sm-9">{{ $address->province->name ?? 'N/A' }}</dd>

                                        <dt class="col-sm-3">Zip Code</dt>
                                        <dd class="col-sm-9">{{ $address->zip_code }}</dd>

                                        <dt class="col-sm-3">Country</dt>
                                        <dd class="col-sm-9">{{ $address->country->nama_negara ?? 'N/A' }}</dd>
                                    </dl>
                                    @if (!$loop->last)
                                        <hr>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted mb-0">No addresses available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
