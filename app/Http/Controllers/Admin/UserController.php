<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users with search and role filters.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        
        $roles = [
            'superadmin'  => 'Super Admin',
            'admin'       => 'System Admin',
            'management'  => 'Owner / Management',
            'manager'     => 'Branch Manager',
            'planner'     => 'Production Officer',
            'staff'       => 'Customer Service / Sales',
            'designer'    => 'Layout Designer',
            'production'  => 'Production Operator',
            'inventory'   => 'Inventory Staff',
            'customer'    => 'Customer',
        ];

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = [
            'superadmin'  => 'Super Admin',
            'admin'       => 'System Admin',
            'management'  => 'Owner / Management',
            'manager'     => 'Branch Manager',
            'planner'     => 'Production Officer',
            'staff'       => 'Customer Service / Sales',
            'designer'    => 'Layout Designer',
            'production'  => 'Production Operator',
            'inventory'   => 'Inventory Staff',
            'customer'    => 'Customer',
        ];

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'role'     => 'required|string|max:50',
            'phone'    => 'nullable|string|max:50',
            'address'  => 'nullable|string|max:500',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User account created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = [
            'superadmin'  => 'Super Admin',
            'admin'       => 'System Admin',
            'management'  => 'Owner / Management',
            'manager'     => 'Branch Manager',
            'planner'     => 'Production Officer',
            'staff'       => 'Customer Service / Sales',
            'designer'    => 'Layout Designer',
            'production'  => 'Production Operator',
            'inventory'   => 'Inventory Staff',
            'customer'    => 'Customer',
        ];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|string|max:50',
            'phone'    => 'nullable|string|max:50',
            'address'  => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User account updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User account deleted successfully.');
    }
}
