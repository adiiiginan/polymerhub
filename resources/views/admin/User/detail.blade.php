@extends('Admin.dashboard')

@section('title', 'User Detail')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Detail: {{ $user->username }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- User Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">User Information</h4>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Username</dt>
                                    <dd class="col-sm-8">{{ $user->username }}</dd>

                                    <dt class="col-sm-4">Email</dt>
                                    <dd class="col-sm-8">{{ $user->email }}</dd>

                                    <dt class="col-sm-4">Role</dt>
                                    <dd class="col-sm-8">{{ $user->priviladges->priviladges ?? 'No Role' }}</dd>

                                    <dt class="col-sm-4">Status</dt>
                                    <dd class="col-sm-8"><span
                                            class="badge badge-{{ $user->status_badge }}">{{ $user->stat->status ?? $user->status_text }}</span>
                                    </dd>

                                    <dt class="col-sm-4">Joined</dt>
                                    <dd class="col-sm-8">{{ $user->created_at->format('d F Y H:i:s') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Contact Information</h4>
                            </div>
                            <div class="card-body">
                                @if ($user->userDetail)
                                    <dl class="row">
                                        <dt class="col-sm-4">Nama</dt>
                                        <dd class="col-sm-8">{{ $user->userDetail->nama }}</dd>

                                        <dt class="col-sm-4">No HP</dt>
                                        <dd class="col-sm-8">{{ $user->userDetail->no_hp }}</dd>

                                        <dt class="col-sm-4">Perusahaan</dt>
                                        <dd class="col-sm-8">{{ $user->userDetail->perusahaan }}</dd>
                                    </dl>
                                @else
                                    <p>No contact details available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Address Information</h4>
                            </div>
                            <div class="card-body">
                                @if ($user->addresses->isNotEmpty())
                                    @foreach ($user->addresses as $address)
                                        <div class="address-block {{ !$loop->last ? 'mb-3 border-bottom pb-3' : '' }}">
                                            <h5>
                                                Address {{ $loop->iteration }}
                                                @if ($address->is_primary)
                                                    <span class="badge badge-primary">Primary</span>
                                                @endif
                                            </h5>
                                            <dl class="row">
                                                <dt class="col-sm-3">Alamat</dt>
                                                <dd class="col-sm-9">{{ $address->alamat }}</dd>

                                                <dt class="col-sm-3">City</dt>
                                                <dd class="col-sm-9">{{ $address->city->nama_kota ?? '' }}</dd>

                                                <dt class="col-sm-3">State</dt>
                                                <dd class="col-sm-9">{{ $address->province->name ?? '' }}</dd>

                                                <dt class="col-sm-3">Zip Code</dt>
                                                <dd class="col-sm-9">{{ $address->zip_code }}</dd>

                                                <dt class="col-sm-3">Country</dt>
                                                <dd class="col-sm-9">{{ $address->country->nama_negara ?? 'N/A' }}</dd>
                                            </dl>
                                        </div>
                                    @endforeach
                                @else
                                    <p>No addresses available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <div>
                    @if ($user->status == 2)
                        <form action="{{ route('admin.user.update.status') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>
                        <form action="{{ route('admin.user.update.status') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="status" value="9">
                            <button type="submit" class="btn btn-danger">Decline</button>
                        </form>
                    @endif
                </div>
                <a href="{{ route('admin.user.customer') }}" class="btn btn-secondary">Back to Customer List</a>
            </div>
        </div>
    </div>
@endsection
