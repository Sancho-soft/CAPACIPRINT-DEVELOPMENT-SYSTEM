@extends('layouts.internal')
@section('title', 'Customer Profile')
@section('page-title', 'Customer Profile & History')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-full bg-brand-500 text-white font-bold text-xl flex items-center justify-center font-display">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-navy-900">{{ $user->name }}</h2>
                <p class="text-xs text-slate-500">{{ $user->email }} &middot; {{ $user->phone ?? 'No phone' }}</p>
                <p class="text-xs text-slate-400 mt-0.5">{{ $user->address ?? 'No address provided' }}</p>
            </div>
        </div>
        <a href="{{ route('staff.customers.index') }}" class="text-xs font-semibold text-slate-500">&larr; Back to Directory</a>
    </div>

    {{-- Order History --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-navy-900 text-sm">Customer Order History</h3>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">Order #</th>
                    <th class="px-6 py-3">Service</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentOrders as $ord)
                <tr>
                    <td class="px-6 py-3 font-bold text-navy-900">#{{ $ord->order_number }}</td>
                    <td class="px-6 py-3 text-slate-700">{{ $ord->printRequest->service ?? '—' }}</td>
                    <td class="px-6 py-3 text-slate-500">{{ $ord->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700">{{ $ord->status_label }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-6 text-center text-slate-400">No order history available.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
