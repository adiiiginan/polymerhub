<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserPrivileges;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\KodeUserService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifiedMail;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function userDetail($id)
    {
        $user = User::with('userDetail', 'priviladges', 'stat', 'addresses.city', 'addresses.province', 'transaksi.invoice')->findOrFail($id);
        return view('Admin.user.detail', compact('user'));
    }

    public function updateUserStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user,id',
            'status' => 'required|integer',
        ]);

        $user = User::with('userDetail')->findOrFail($request->user_id);
        $user->status = $request->status;
        $user->save();

        if ($request->status == 1) { // 1 is the 'approved' status
            // Send email to user
            Mail::to($user->email)->send(new VerifiedMail($user->userDetail->nama, $user->email, $user->userDetail->perusahaan));
        }

        return redirect()->route('admin.user.customer')->with('success', 'User status has been updated.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        // Logic for bulk update will go here
        return response()->json(['success' => true]);
    }

    public function customer()
    {
        $customers = User::where('id_priviladges', 3)
            ->where('status', 2) // Filter for unverified users
            ->with('priviladges', 'stat')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.User.customer', compact('customers'));
    }

    public function verified()
    {
        $users = User::where('id_priviladges', 3)
            ->where('status', 1) // Filter for verified users
            ->with('priviladges', 'stat')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Admin.User.verified', compact('users'));
    }

    public function index()
    {
        // Filter to get all users except for customers (id_priviladges = 3)
        $users = User::with('userDetail')
            ->where('id_priviladges', '!=', 3)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('Admin.user.index', compact('users'));
    }

    public function create()
    {
        $privileges = UserPrivileges::all();
        return view('Admin.user.create', compact('privileges'));
    }

    public function store(Request $request, KodeUserService $kodeUserService)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user',
            'email' => 'required|string|email|max:255|unique:user',
            'password' => 'required|string|min:6',
            'id_priviladges' => 'required|exists:user_priviladges,id',
            'status' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            $kode_user = 'USER' . now()->format('YmdHis') . strtoupper(Str::random(3));

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
                'id_priviladges' => $request->id_priviladges,
                'status' => $request->status,
                'kode_user' => $kode_user,
            ]);

            UserDetail::create([
                'kode_user' => $user->kode_user,
                'nama' => $request->nama,
                'email' => $request->email, // Menambahkan email
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]);

            DB::commit();

            return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal menambahkan user: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(User $user)
    {
        return 'This is the page to edit a user.';
    }

    public function update(Request $request, User $user)
    {
        // Logic to update the user will go here
        return redirect()->route('admin.user.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
