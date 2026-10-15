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
        // Only allow managing executive roles
        $query = User::with('branch')->whereIn('role', ['owner', 'management', 'admin', 'system_admin', 'manager']);

        if ($request->boolean('archived')) {
            $query->where('is_archived', true);
        } else {
            $query->where('is_archived', false);
        }

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(7);
        $branches = Branch::all();

        return view('admin.users.index', compact('users', 'branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role'      => ['required', 'string', 'in:owner,management,admin,system_admin,manager'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'password'  => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'role'        => $data['role'],
            'branch_id'   => $data['branch_id'] ?: null,
            'password'    => Hash::make($data['password']),
            'is_archived' => false,
        ]);

        AuditLog::record(
            'Executive Account Created',
            'User Management',
            "Created executive account {$user->name} ({$user->email}) with role {$user->role}",
            null,
            $user->toArray()
        );

        return redirect()->back()->with('success', "Executive account {$user->name} created successfully.");
    }

    public function update(Request $request, User $user)
    {
        if (!in_array($user->role, ['owner', 'management', 'admin', 'system_admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'      => ['required', 'string', 'in:owner,management,admin,system_admin,manager'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'password'  => ['nullable', 'string', 'min:8'],
        ]);

        $old = $user->toArray();

        $updateData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'branch_id' => $data['branch_id'] ?: null,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        AuditLog::record(
            'Executive Account Updated',
            'User Management',
            "Updated account details for {$user->name}",
            $old,
            $user->fresh()->toArray()
        );

        return redirect()->back()->with('success', "Account for {$user->name} updated successfully.");
    }

    public function resetPassword(Request $request, User $user)
    {
        if (!in_array($user->role, ['owner', 'management', 'admin', 'system_admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        AuditLog::record(
            'Executive Account Password Reset',
            'User Management',
            "Reset password for {$user->name}",
            null,
            ['id' => $user->id]
        );

        return redirect()->back()->with('success', "Password for {$user->name} reset successfully.");
    }

    public function toggleArchive(User $user)
    {
        if (!in_array($user->role, ['owner', 'management', 'admin', 'system_admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $user->is_archived = !$user->is_archived;
        $user->save();

        $actionText = $user->is_archived ? 'Archived' : 'Restored';

        AuditLog::record(
            "Executive Account {$actionText}",
            'User Management',
            "{$actionText} account {$user->name} ({$user->email})",
            null,
            ['is_archived' => $user->is_archived]
        );

        return redirect()->back()->with('success', "Account {$user->name} {$actionText} successfully.");
    }
}