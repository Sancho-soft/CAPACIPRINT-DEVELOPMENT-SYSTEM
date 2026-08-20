@extends('layouts.internal')
@section('title', 'Production Planning')
@section('page-title', 'Production Scheduling & Assignment')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-navy-900 font-display">Production Planning Jobs</h2>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Job #</th>
                    <th class="px-6 py-3.5">Customer</th>
                    <th class="px-6 py-3.5">Assigned Branch</th>
                    <th class="px-6 py-3.5">Machine</th>
                    <th class="px-6 py-3.5">Assigned Staff</th>
                    <th class="px-6 py-3.5">Priority</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($jobs as $j)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">#{{ $j->job_number }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $j->order->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-bold text-brand-600">{{ $j->branch->name ?? 'Unassigned' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $j->machine->name ?? 'Not Set' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $j->assignedTo->name ?? 'Unassigned' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $j->priority_badge_class }}">
                            {{ $j->priority }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('manager.production-planning.show', $j) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-3 py-1.5 rounded-lg text-xs">Schedule &rarr;</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">No active production jobs.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $jobs->links() }}</div>
    </div>
</div>
@endsection
