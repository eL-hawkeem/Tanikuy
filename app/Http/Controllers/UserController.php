<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $query = User::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, fn($q) => $q->where('role', $role));

        $users = $query->latest()->paginate(10)->withQueryString();
        $totalAdmins = User::where('role', UserRole::ADMIN->value)->count();
        $totalKasir = User::where('role', UserRole::KASIR->value)->count();

        return view('users.index', compact('users', 'search', 'role', 'totalAdmins', 'totalKasir'));
    }

    public function create()
    {
        $roles = UserRole::cases();
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);

        return redirect()->route('users.index')
            ->with('success', "Pengguna {$user->name} dengan peran " . $user->role_label . " berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        $roles = UserRole::cases();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        // Safeguard: Don't allow changing role if it's the current user or last admin
        if ($user->isAdmin() && $validated['role'] !== UserRole::ADMIN->value) {
            $adminCount = User::where('role', UserRole::ADMIN->value)->count();
            if ($adminCount <= 1) {
                return back()->withInput()->with('error', 'Tidak dapat mengubah peran admin terakhir dalam sistem.');
            }
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        if ($user->isAdmin()) {
            $adminCount = User::where('role', UserRole::ADMIN->value)->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Tidak dapat menghapus Administrator terakhir dalam sistem.');
            }
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Pengguna {$userName} berhasil dihapus.");
    }
}
