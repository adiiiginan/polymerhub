@extends('Admin.dashboard')

@section('title', 'Daftar User')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">List Customer Approval</h3>

                    </div>

                    <div class="card-body">
                        <table id="usersTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Tanggal & Waktu Masuk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $key => $customer)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $customer->username }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>
                                            @if ($customer->priviladges)
                                                <span class="badge bg-{{ $customer->privilege_badge }}">
                                                    {{ $customer->priviladges->priviladges }}
                                                </span>
                                            @else
                                                <span class="badge bg-light">No Role</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($customer->stat)
                                                <span class="badge bg-{{ $customer->status_badge }}">
                                                    {{ $customer->stat->status }}
                                                </span>
                                            @else
                                                <span class="badge bg-light">No Status</span>
                                            @endif
                                        </td>
                                        <td>{{ $customer->created_at->format('d F Y H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('admin.user.detail', $customer->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-user"></i> Lihat Profil Customer
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data customer yang perlu disetujui.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                responsive: true,
                autoWidth: false,
                ordering: false,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
    </script>
@endpush
