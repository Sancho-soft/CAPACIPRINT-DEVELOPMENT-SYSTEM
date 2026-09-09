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

        $machine = Machine::findOrFail($data['machine_id']);

        // If breakdown or maintenance log, update machine status and schedule next maintenance
        if (in_array($data['log_type'], ['breakdown', 'maintenance'])) {
            $machine->update([
                'status'                => 'maintenance',
                'last_maintenance_date' => now()->toDateString(),
                'next_maintenance_date' => now()->addDays(30)->toDateString(), // Default: 30-day interval
            ]);

            // Notify Branch Manager(s)
            \App\Models\InternalNotification::create([
                'user_id' => null, // Broadcast to all managers
                'role'    => 'manager',
                'title'   => '⚠️ Machine Maintenance Alert',
                'body'    => "Machine \"{$machine->name}\" at {$machine->branch->name} has been logged as {$data['log_type']}. "
                           . "Next scheduled maintenance: " . now()->addDays(30)->format('M d, Y') . ". "
                           . "Issue: {$data['issue_description']}",
                'type'    => 'capacity_alert',
                'link'    => route('production.machines.index'),
            ]);
        }

        if ($data['log_type'] === 'inspection') {
            // Inspection passed — reset schedule
            $machine->update([
                'last_maintenance_date' => now()->toDateString(),
                'next_maintenance_date' => now()->addDays(30)->toDateString(),
            ]);
        }

        AuditLog::record(
            'Machine Log Recorded',
            'Equipment Management',
            "Machine log ({$data['log_type']}) recorded for {$machine->name}: {$data['issue_description']}",
            null,
            $log->toArray()
        );

        return redirect()->back()->with('success', 'Equipment report submitted. Branch Manager has been notified.');
    }
}
