@extends('layouts.internal')
@section('title', 'Production Overview')
@section('page-title', 'Production Overview')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Job #</th>
                    <th class="px-6 py-3.5">Service</th>
                    <th class="px-6 py-3.5">Branch</th>
                    <th class="px-6 py-3.5">Staff Assigned</th>
                    <th class="px-6 py-3.5">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($jobs as $j)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">#{{ $j->job_number }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $j->order->printRequest->service ?? '—' }}</td>
                    <td class="px-6 py-4 font-bold text-brand-600">{{ $j->branch->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $j->assignedTo->name ?? 'Unassigned' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $j->status_badge_class }}">{{ $j->status_label }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">No production jobs found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $jobs->links() }}</div>
    </div>
</div>
@endsection
