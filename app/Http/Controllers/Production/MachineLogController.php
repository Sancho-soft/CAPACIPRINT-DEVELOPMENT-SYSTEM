<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\MachineLog;
use App\Models\Machine;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class MachineLogController extends Controller
{
    public function index()
    {
        $logs = MachineLog::with(['machine.branch', 'reporter'])
            ->latest()
            ->paginate(15);

        $machines = Machine::all();

        return view('production.machines.index', compact('logs', 'machines'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'machine_id'        => ['required', 'exists:machines,id'],
            'log_type'          => ['required', 'in:breakdown,maintenance,inspection,status_change'],
            'issue_description' => ['required', 'string', 'max:1000'],
        ]);

        $log = MachineLog::create([
            'machine_id'        => $data['machine_id'],
            'reported_by'       => auth()->id(),
            'log_type'          => $data['log_type'],
            'issue_description' => $data['issue_description'],
            'status'            => 'open',
        ]);

        // If breakdown, flag machine status as maintenance
        if ($data['log_type'] === 'breakdown') {
            Machine::where('id', $data['machine_id'])->update(['status' => 'maintenance']);
        }

        AuditLog::record(
            'Machine Breakdown Reported',
            'Equipment Management',
            "Machine breakdown logged for Machine #{$data['machine_id']}: {$data['issue_description']}",
            null,
            $log->toArray()
        );

        return redirect()->back()->with('success', 'Equipment problem report submitted to Branch Manager.');
    }
}
