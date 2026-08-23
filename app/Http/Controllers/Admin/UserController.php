<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('branch');

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(20);
        $branches = Branch::all();

        return view('admin.users.index', compact('users', 'branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role'      => ['required', 'string', 'in:customer,staff,manager,production,inventory,management,admin'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'password'  => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'branch_id' => $data['branch_id'] ?? null,
            'password'  => Hash::make($data['password']),
        ]);

        AuditLog::record(
            'User Account Created',
            'User Management',
            "Created user account {$user->name} ({$user->email}) with role {$user->role}",
            null,
            $user->toArray()
        );

        return redirect()->back()->with('success', "User account {$user->name} created successfully.");
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role'      => ['required', 'string', 'in:customer,staff,manager,production,inventory,management,admin'],
            'branch_id' => ['nullable', 'exists:branches,id'],
        ]);

        $old = ['role' => $user->role, 'branch_id' => $user->branch_id];
        $user->update($data);

        AuditLog::record(
            'User Access Level Updated',
            'User Management',
            "Updated role for {$user->name} to {$user->role}",
            $old,
            $data
        );

        return redirect()->back()->with('success', "User {$user->name} access updated.");
    }
}
