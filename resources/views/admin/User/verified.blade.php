@extends('Admin.dashboard')

@section('title', 'Daftar Customer Verified')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">List Customer Verified</h3>

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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $cust)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $cust->username }}</td>
                                        <td>{{ $cust->email }}</td>
                                        <td>

                                            <span
                                                class="badge {{ $cust->priviladges->id == 3 ? 'bg-info' : 'bg-secondary' }}">
                                                {{ $cust->priviladges->priviladges ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $cust->stat->id == 1 ? 'bg-success' : 'bg-warning' }}">
                                                {{ $cust->stat->status ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td><a href="{{ route('admin.user.detail', $cust->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-user"></i> Lihat Profil Customer
                                            </a>
                                            <form action="{{ route('admin.user.destroy', $cust->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
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
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
    </script>
@endpush
