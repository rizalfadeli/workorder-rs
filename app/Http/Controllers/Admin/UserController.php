<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Daftar semua user dengan fitur pencarian & filter unit
     */
    public function index(Request $request)
    {
        $query = User::with('role')->orderBy('name');

        // Pencarian berdasarkan nama atau email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan unit/poli
        if ($request->filled('unit')) {
            $query->where('unit', 'like', "%{$request->unit}%");
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->whereHas('role', fn($q) => $q->where('name', $request->role));
        }

        $users = $query->paginate(15)->withQueryString();

        // Ambil semua unit unik untuk dropdown filter
        $units = User::whereNotNull('unit')
            ->distinct()
            ->orderBy('unit')
            ->pluck('unit');

        $totalUsers = User::count();
        $totalAdmin = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->count();
        $totalUser  = User::whereHas('role', fn($q) => $q->where('name', 'user'))->count();

        return view('admin.users.index', compact('users', 'units', 'totalUsers', 'totalAdmin', 'totalUser'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.form', [
            'user'  => new User(),
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'min:6', 'confirmed'],
            'role_id'               => ['required', 'exists:roles,id'],
            'unit'                  => ['nullable', 'string', 'max:255'],
        ], [
            'name.required'         => 'Nama wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'role_id.required'      => 'Role wajib dipilih.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
            'unit'     => $request->unit,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.form', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'unique:users,email,' . $user->id],
            'password'           => ['nullable', 'min:6', 'confirmed'],
            'role_id'            => ['required', 'exists:roles,id'],
            'unit'               => ['nullable', 'string', 'max:255'],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan akun lain.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role_id.required'   => 'Role wajib dipilih.',
        ]);

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'role_id' => $request->role_id,
            'unit'    => $request->unit,
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Tidak boleh hapus akun sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }
}