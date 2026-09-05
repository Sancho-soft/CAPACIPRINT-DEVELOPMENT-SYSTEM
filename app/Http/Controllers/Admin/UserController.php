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

        $users = $query->latest()->paginate(6);
        $branches = Branch::all();

        return view('admin.users.index', compact('users', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        // Ensure user being edited is an executive
        if (!in_array($user->role, ['owner', 'management', 'admin', 'system_admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'password'  => ['required', 'string', 'min:8'],
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
