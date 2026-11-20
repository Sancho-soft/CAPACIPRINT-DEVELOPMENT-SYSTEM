@extends('layouts.internal')
@section('title', 'Production Overview')
@section('page-title', 'Production Overview')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs tabular-nums">
            <thead class="bg-cyber-sub text-cyber-muted font-bold">
                <tr>
                    <th class="px-6 py-3.5">Job #</th>
                    <th class="px-6 py-3.5">Service</th>
                    <th class="px-6 py-3.5">Branch</th>
                    <th class="px-6 py-3.5">Staff Assigned</th>
                    <th class="px-6 py-3.5">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cyber-sub">
                @forelse($jobs as $j)
                <tr class="hover:bg-cyber-sub/60 transition">
                    <td class="px-6 py-4 font-bold text-cyber-main">#{{ $j->job_number }}</td>
                    <td class="px-6 py-4 font-semibold text-cyber-main">{{ $j->order->printRequest->service ?? '—' }}</td>
                    <td class="px-6 py-4 font-bold text-brand-600">{{ $j->branch->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-cyber-muted">{{ $j->assignedTo->name ?? 'Unassigned' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-semibold uppercase {{ match($j->status) {
                            'in_production', 'production' => 'text-blue-600 dark:text-blue-400',
                            'completed'                   => 'text-emerald-600 dark:text-emerald-400',
                            'delayed'                     => 'text-rose-600 dark:text-rose-400',
                            'quality_checking'            => 'text-purple-600 dark:text-purple-400',
                            default                       => 'text-cyber-muted',
                        } }}">{{ $j->status_label }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-cyber-muted">No production jobs found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-cyber">{{ $jobs->links() }}</div>
    </div>
</div>
@endsection
