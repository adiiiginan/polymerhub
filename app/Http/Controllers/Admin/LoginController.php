<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Negara;
use App\Models\Klasifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Perusahaan;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use App\Mail\AccountUnderReviewMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\UserAddress;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.login.login');
    }

    public function authenticate(Request $request)
    { {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $credentials = [
                $loginField => $request->username,
                'password' => $request->password,
            ];

            if (Auth::guard('admin')->attempt($credentials)) {
                $user = Auth::guard('admin')->user();

                // Check if the user is an admin and active
                if (in_array($user->id_priviladges, [1, 2, 4, 5, 6, 7]) && $user->status == 1) {
                    return redirect()->route('admin.dashboard');
                }

                Auth::guard('admin')->logout();
                return back()->withErrors(['username' => 'Access is restricted to active admins.']);
            }

            return back()->withErrors(['username' => 'Login gagal: Kombinasi username dan password tidak cocok.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // Dashboard Admin
    public function dashboard()
    {
        $adminUser = Auth::user();

        // Statistik untuk dashboard
        $stats = [
            'total_users' => DB::table('user')->count(),
            'active_users' => DB::table('user')->where('status', 1)->count(),
            'pending_users' => DB::table('user')->where('status', 2)->count(),
            'total_companies' => DB::table('perusahaan')->count(),
            'total_classifications' => DB::table('klasifikasi')->count(),
        ];

        // Recent activities atau data terbaru
        $recentUsers = DB::table('user')
            ->join('user_detail', 'user.id', '=', 'user_detail.id')
            ->select('user.*', 'user_detail.nama', 'user_detail.perusahaan')
            ->orderBy('user.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('adminUser', 'stats', 'recentUsers'));
    }

    // Menampilkan daftar semua user
    public function users(Request $request)
    {
        $query = DB::table('user')
            ->join('user_detail', 'user.id', '=', 'user_detail.id')
            ->join('perusahaan', 'user_detail.idperusahaan', '=', 'perusahaan.id')
            ->join('klasifikasi', 'user_detail.idkuali', '=', 'klasifikasi.id')
            ->select(
                'user.*',
                'user_detail.nama',
                'user_detail.no_hp',
                'user_detail.perusahaan as nama_perusahaan',
                'perusahaan.nama as perusahaan_master',
                'klasifikasi.nama as klasifikasi_nama'
            );

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('user.status', $request->status);
        }

        // Filter berdasarkan privilege jika ada
        if ($request->has('privilege') && $request->privilege != '') {
            $query->where('user.id_priviladges', $request->privilege);
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_detail.nama', 'LIKE', "%{$search}%")
                    ->orWhere('user.email', 'LIKE', "%{$search}%")
                    ->orWhere('user.kode_user', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    // Detail user
    public function userDetail($id)
    {
        $user = DB::table('user')
            ->join('user_detail', 'user.id', '=', 'user_detail.id')
            ->join('perusahaan', 'user_detail.idperusahaan', '=', 'perusahaan.id')
            ->join('klasifikasi', 'user_detail.idkuali', '=', 'klasifikasi.id')
            ->select(
                'user.*',
                'user_detail.*',
                'perusahaan.nama as perusahaan_nama',
                'klasifikasi.nama as klasifikasi_nama'
            )
            ->where('user.id', $id)
            ->first();

        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User tidak ditemukan.');
        }

        return view('admin.users.detail', compact('user'));
    }

    // Update status user (approve/reject)
    public function updateUserStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:1,2,0', // 1=active, 2=pending, 0=inactive
        ]);

        $user = DB::table('user')->where('id', $id)->first();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        DB::table('user')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

        $statusText = $request->status == 1 ? 'diaktifkan' : ($request->status == 2 ? 'pending' : 'dinonaktifkan');

        return back()->with('success', "Status user berhasil {$statusText}.");
    }

    // Bulk update status untuk multiple users
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:user,id',
            'status' => 'required|in:1,2,0',
        ]);

        DB::table('user')
            ->whereIn('id', $request->user_ids)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

        $count = count($request->user_ids);
        $statusText = $request->status == 1 ? 'diaktifkan' : ($request->status == 2 ? 'pending' : 'dinonaktifkan');

        return back()->with('success', "{$count} user berhasil {$statusText}.");
    }

    // Hapus user
    public function deleteUser($id)
    {
        $user = DB::table('user')->where('id', $id)->first();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Hapus dari kedua tabel menggunakan transaction
        DB::transaction(function () use ($id) {
            DB::table('user_detail')->where('id', $id)->delete();
            DB::table('user')->where('id', $id)->delete();
        });

        return redirect()->route('Admin.users')->with('success', 'User berhasil dihapus.');
    }

    // Pending users (users yang menunggu approval)
    public function pendingUsers()
    {
        $pendingUsers = DB::table('user')
            ->join('user_detail', 'user.id', '=', 'user_detail.id')
            ->join('perusahaan', 'user_detail.idperusahaan', '=', 'perusahaan.id')
            ->join('klasifikasi', 'user_detail.idkuali', '=', 'klasifikasi.id')
            ->select(
                'user.*',
                'user_detail.nama',
                'user_detail.no_hp',
                'user_detail.perusahaan as nama_perusahaan',
                'perusahaan.nama as perusahaan_master',
                'klasifikasi.nama as klasifikasi_nama'
            )
            ->where('user.status', 2)
            ->orderBy('user.created_at', 'desc')
            ->paginate(10);

        return view('admin.users.pending', compact('pendingUsers'));
    }

    // Settings/Profile admin
    public function settings()
    {
        $adminUser = Auth::user();

        // Data untuk settings seperti perusahaan dan klasifikasi
        $companies = DB::table('perusahaan')->get();
        $classifications = DB::table('klasifikasi')->get();

        return view('admin.settings', compact('adminUser', 'companies', 'classifications'));
    }

    // Update profile admin
    public function updateProfile(Request $request)
    {
        $adminUser = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255|unique:user,username,' . $adminUser->id,
            'email' => 'required|email|unique:user,email,' . $adminUser->id,
        ]);

        DB::table('user')
            ->where('id', $adminUser->id)
            ->update([
                'username' => $request->username,
                'email' => $request->email,
                'updated_at' => now()
            ]);

        // Update session jika username berubah
        if ($request->username !== $adminUser->username) {
            Session::put('admin_name', $request->username);
        }

        return back()->with('success', 'Profile berhasil diupdate.');
    }

    // Change password admin
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $adminUser = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $adminUser->password)) {
            return back()->withErrors(['current_password' => 'Incorrect current password.']);
        }

        // Update password
        $hashedPassword = Hash::make($request->password);

        DB::table('user')
            ->where('id', $adminUser->id)
            ->update([
                'password' => $hashedPassword,
                'updated_at' => now()
            ]);

        return back()->with('success', 'Password changed successfully.');
    }

    // Reports/Analytics
    public function reports()
    {
        // Data untuk reports
        $userStats = [
            'total_users' => DB::table('user')->count(),
            'active_users' => DB::table('user')->where('status', 1)->count(),
            'pending_users' => DB::table('user')->where('status', 2)->count(),
            'inactive_users' => DB::table('user')->where('status', 0)->count(),
        ];

        // User registration per month (last 6 months)
        $registrationStats = DB::table('user')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Users by company
        $usersByCompany = DB::table('user')
            ->join('user_detail', 'user.id', '=', 'user_detail.id')
            ->join('perusahaan', 'user_detail.idperusahaan', '=', 'perusahaan.id')
            ->select('perusahaan.nama', DB::raw('COUNT(*) as count'))
            ->groupBy('perusahaan.id', 'perusahaan.nama')
            ->orderBy('count', 'desc')
            ->get();

        return view('admin.reports', compact('userStats', 'registrationStats', 'usersByCompany'));
    }

    // Manage Companies
    public function companies()
    {
        $companies = DB::table('perusahaan')
            ->orderBy('nama', 'asc')
            ->paginate(10);

        return view('admin.companies.index', compact('companies'));
    }

    // Add new company
    public function addCompany(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:perusahaan,nama',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
        ]);

        DB::table('perusahaan')->insert([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Perusahaan berhasil ditambahkan.');
    }

    // Manage Classifications
    public function classifications()
    {
        $classifications = DB::table('klasifikasi')
            ->orderBy('nama', 'asc')
            ->paginate(10);

        return view('admin.classifications.index', compact('classifications'));
    }

    // Add new classification
    public function addClassification(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:klasifikasi,nama',
            'deskripsi' => 'nullable|string',
        ]);

        DB::table('klasifikasi')->insert([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Klasifikasi berhasil ditambahkan.');
    }

    public function registerform()
    {
        $perusahaan = Perusahaan::all();
        $negara = Negara::all();
        $klasifikasi = DB::table('klasifikasi')->get();
        return view('register', compact('perusahaan', 'klasifikasi', 'negara'));
    }

    public function registerUser(Request $request)
    {
        // 🔹 1. Validasi input
        $validated = $request->validate([
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6|confirmed',
            'nama'          => 'required|string|max:255',
            'lengkap'       => 'required|string|max:255',
            'no_hp'         => 'required|string|max:15',
            'jabatan'       => 'required|string|max:255',
            'idperusahaan'  => 'required|exists:perusahaan,id',
            'perusahaan'    => 'nullable|string|max:255',
            'idkuali'       => 'required|exists:klasifikasi,id',
            'country'       => 'required|string|exists:negara,kode_iso',
            'alamat'        => 'required|string',
            'city'          => 'required|string|max:255',
            'zip'           => 'required|string|max:10',
            'vat_number'    => 'required|string|max:255',
            'agree'         => 'accepted',
            'comment'       => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 🔹 2. Generate kode user
            $kodeUser = $this->kodeuser();

            // 🔹 3. Dapatkan ID negara dari kode ISO
            $countryCode = $validated['country'];
            $negara = Negara::where('kode_iso', $countryCode)->first();
            // We know $negara is not null because of the 'exists' validation rule.
            $countryId = $negara->id;

            // 🔹 4. Simpan ke tabel user
            $user = User::create([
                'username'       => $validated['nama'],
                'email'          => $validated['email'],
                'password_hash'  => Hash::make($validated['password']),
                'auth_key'       => Str::random(32),
                'id_priviladges' => 3,
                'status'         => 2, // 2 = pending / belum aktif
                'kode_user'      => $kodeUser,
                'comment'        => $validated['comment'],
            ]);

            // 🔹 5. Simpan ke tabel user_detail
            UserDetail::create([
                'id_user'       => $user->id, // gunakan id_user, bukan id
                'kode_user'     => $kodeUser,
                'email'         => $validated['email'],
                'nama'          => $validated['nama'],
                'no_hp'         => $validated['no_hp'],
                'jabatan'       => $validated['jabatan'],
                'alamat'        => $validated['alamat'],
                'idkuali'       => $validated['idkuali'],
                'foto'          => null,
                'idperusahaan'  => $validated['idperusahaan'],
                'perusahaan'    => $validated['perusahaan'],
                'lengkap'       => $validated['lengkap'],
                'zip'           => $validated['zip'],
                'city'          => $validated['city'],
                'idcountry'     => $countryId,
                'vat'           => $validated['vat_number'],
            ]);

            // 🔹 5.1. Simpan ke tabel user_addresses
            UserAddress::create([
                'user_id' => $user->id,
                'nama' => $validated['lengkap'],
                'phone' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'city' => $validated['city'],
                'zip_code' => $validated['zip'],
                'idcountry' => $countryId,
                'is_primary' => 1,
                'is_store_address' => 0,
            ]);

            // 🔹 6. Kirim email notifikasi (opsional)
            try {
                Mail::to($user->email)->send(new AccountUnderReviewMail($user));
            } catch (\Exception $e) {
                Log::warning('Email gagal dikirim: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('register')
                ->with('success', 'Registrasi berhasil! Akun Anda sedang ditinjau.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('QueryException: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Terjadi kesalahan pada database: ' . $e->getMessage()])->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Exception: ' . $e->getMessage());
            return back()->withErrors(['general' => 'Registrasi gagal. Silakan coba lagi.'])->withInput();
        }
    }


    private function kodeuser()
    {
        // Ambil ID terakhir dari tabel user
        $lastId = \App\Models\User::max('id') ?? 0;

        // Format: USR-00001, USR-00002, dst
        return 'USR-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
    }
}
