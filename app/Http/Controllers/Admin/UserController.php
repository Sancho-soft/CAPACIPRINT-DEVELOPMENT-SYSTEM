<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('branch');

        if ($request->boolean('archived')) {
            $query->where('is_archived', true);
        } else {
            $query->where('is_archived', false);
        }

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(6);
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
            'name'        => $data['name'],
            'email'       => $data['email'],
            'role'        => $data['role'],
            'branch_id'   => $data['branch_id'] ?? null,
            'password'    => Hash::make($data['password']),
            'is_archived' => false,
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

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'      => ['required', 'string', 'in:customer,staff,manager,production,inventory,management,admin'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'password'  => ['nullable', 'string', 'min:8'],
        ]);

        $old = $user->toArray();

        $updateData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'branch_id' => $data['branch_id'] ?? null,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        AuditLog::record(
            'User Account Updated',
            'User Management',
            "Updated account details for {$user->name}",
            $old,
            $user->toArray()
        );

        return redirect()->back()->with('success', "User account {$user->name} updated successfully.");
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

    public function toggleArchive(User $user)
    {
        $user->is_archived = !$user->is_archived;
        $user->save();

        $actionText = $user->is_archived ? 'Archived' : 'Restored';

        AuditLog::record(
            "User Account {$actionText}",
            'User Management',
            "{$actionText} user account {$user->name} ({$user->email})",
            null,
            ['is_archived' => $user->is_archived]
        );

        return redirect()->back()->with('success', "User account {$user->name} {$actionText} successfully.");
    }
}
