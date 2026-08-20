@extends('layouts.internal')
@section('title', 'My Jobs')
@section('page-title', 'My Production Jobs')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Job #</th>
                    <th class="px-6 py-3.5">Customer &amp; Service</th>
                    <th class="px-6 py-3.5">Machine</th>
                    <th class="px-6 py-3.5">Priority</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($jobs as $j)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">#{{ $j->job_number }}</td>
                    <td class="px-6 py-4 text-slate-800">
                        <strong>{{ $j->order->printRequest->service ?? '—' }}</strong>
                        <span class="block text-[10px] text-slate-400">Cust: {{ $j->order->user->name ?? '—' }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $j->machine->name ?? 'Not Set' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $j->priority_badge_class }}">{{ $j->priority }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $j->status_badge_class }}">{{ $j->status_label }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('production.jobs.status-form', $j) }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-3.5 py-1.5 rounded-lg text-xs">Update Status</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">No jobs assigned.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $jobs->links() }}</div>
    </div>
</div>
@endsection
