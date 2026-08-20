@extends('layouts.internal')
@section('title', 'Executive Orders Overview')
@section('page-title', 'Orders Overview')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Order #</th>
                    <th class="px-6 py-3.5">Customer</th>
                    <th class="px-6 py-3.5">Service</th>
                    <th class="px-6 py-3.5">Branch</th>
                    <th class="px-6 py-3.5">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $o)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">#{{ $o->order_number }}</td>
                    <td class="px-6 py-4 text-slate-800 font-semibold">{{ $o->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-700">{{ $o->printRequest->service ?? '—' }}</td>
                    <td class="px-6 py-4 font-bold text-brand-600">{{ $o->assigned_branch ?? 'Pending' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ match($o->status) { 'ready_for_pickup'=>'bg-emerald-100 text-emerald-800', 'production'=>'bg-cyan-100 text-cyan-800', default=>'bg-slate-100 text-slate-600' } }}">
                            {{ $o->status_label }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
