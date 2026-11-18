@extends('layouts.internal')
@section('title', 'Customer Profile')
@section('page-title', 'Customer Profile & History')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-cyber-card p-6 rounded-2xl border border-cyber shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-blue-600/10 text-blue-600 dark:text-blue-400 font-bold text-xl flex items-center justify-center font-display border border-blue-600/20 shadow-sm shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-cyber-main font-display">{{ $user->name }}</h2>
                <p class="text-xs text-cyber-muted tabular-nums">{{ $user->email }} &middot; {{ $user->phone ?? 'No phone' }}</p>
                <p class="text-xs text-cyber-sub mt-0.5">{{ $user->address ?? 'No address provided' }}</p>
            </div>
        </div>
        <a href="{{ route('staff.customers.index') }}" class="px-4 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main text-xs font-semibold transition inline-flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Directory
        </a>
    </div>

    {{-- Order History --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-cyber-sub bg-cyber-sub/40">
            <h3 class="font-bold text-cyber-main font-display text-sm">Customer Order History</h3>
        </div>
        <div class="overflow-x-auto">
        <table class="w-full text-left text-xs tabular-nums">
            <thead class="bg-cyber-sub text-cyber-muted font-semibold border-b border-cyber text-xs">
                <tr>
                    <th class="px-6 py-3.5">Order #</th>
                    <th class="px-6 py-3.5">Service</th>
                    <th class="px-6 py-3.5">Date</th>
                    <th class="px-6 py-3.5">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cyber-sub text-cyber-main">
                @forelse($recentOrders as $ord)
                <tr class="hover:bg-cyber-sub/50 transition-colors">
                    <td class="px-6 py-3.5 font-bold text-cyber-main tabular-nums">#{{ $ord->order_number }}</td>
                    <td class="px-6 py-3.5 text-cyber-main">{{ $ord->printRequest->service ?? '—' }}</td>
                    <td class="px-6 py-3.5 text-cyber-muted tabular-nums">{{ $ord->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-3.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-cyber-sub border border-cyber text-cyber-main">{{ $ord->status_label }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-cyber-muted">No order history available.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
