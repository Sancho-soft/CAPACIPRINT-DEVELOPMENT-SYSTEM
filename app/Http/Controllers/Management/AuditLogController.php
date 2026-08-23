<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($module = $request->get('module')) {
            $query->where('module', $module);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('event', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->paginate(25);
        $modules = AuditLog::select('module')->distinct()->pluck('module');

        return view('management.audit-logs.index', compact('logs', 'modules'));
    }
}
