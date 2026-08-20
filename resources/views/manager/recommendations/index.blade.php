@extends('layouts.internal')
@section('title', 'Branch Recommendations')
@section('page-title', 'Branch Assignment Recommendations')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-navy-900 font-display">Branch Recommendations</h2>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Req #</th>
                    <th class="px-6 py-3.5">Customer</th>
                    <th class="px-6 py-3.5">Recommended Branch</th>
                    <th class="px-6 py-3.5">Score</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recommendations as $rec)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">#PR-{{ str_pad($rec->printRequest->id ?? 0, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $rec->printRequest->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-bold text-brand-600">{{ $rec->recommendedBranch->name ?? 'None' }}</td>
                    <td class="px-6 py-4 font-black text-navy-900">{{ $rec->recommendation_score }}/100</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ match($rec->status) { 'confirmed'=>'bg-emerald-100 text-emerald-800', 'overridden'=>'bg-amber-100 text-amber-800', default=>'bg-slate-100 text-slate-600' } }}">
                            {{ $rec->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('manager.recommendations.show', $rec) }}" class="text-brand-600 font-bold hover:underline">Review &amp; Confirm &rarr;</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">No branch recommendations found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $recommendations->links() }}</div>
    </div>
</div>
@endsection
