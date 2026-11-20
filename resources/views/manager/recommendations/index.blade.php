@extends('layouts.internal')
@section('title', 'Branch Recommendations')
@section('page-title', 'Branch Assignment Recommendations')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-cyber-main font-display">Branch Recommendations</h2>
    </div>

    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs tabular-nums">
            <thead class="bg-cyber-sub text-cyber-muted font-bold">
                <tr>
                    <th class="px-6 py-3.5">Req #</th>
                    <th class="px-6 py-3.5">Customer</th>
                    <th class="px-6 py-3.5">Recommended Branch</th>
                    <th class="px-6 py-3.5">Score</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cyber-sub">
                @forelse($recommendations as $rec)
                <tr class="hover:bg-cyber-sub/60 transition">
                    <td class="px-6 py-4 font-bold text-cyber-main">#PR-{{ str_pad($rec->printRequest->id ?? 0, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 font-semibold text-cyber-main">{{ $rec->printRequest->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-bold text-brand-600">{{ $rec->recommendedBranch->name ?? 'None' }}</td>
                    <td class="px-6 py-4 font-black text-cyber-main">{{ $rec->recommendation_score }}/100</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-semibold uppercase {{ match($rec->status) { 'confirmed'=>'text-emerald-600 dark:text-emerald-400', 'overridden'=>'text-amber-600 dark:text-amber-400', default=>'text-cyber-muted' } }}">
                            {{ $rec->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('manager.recommendations.show', $rec) }}" class="text-brand-600 font-bold hover:underline">Review &amp; Confirm &rarr;</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-cyber-muted">No branch recommendations found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-cyber">{{ $recommendations->links() }}</div>
    </div>
</div>
@endsection
