@extends('layouts.internal')
@section('title', 'Orders Audit Report')
@section('page-title', 'Orders Audit Report')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Order #</th>
                    <th class="px-6 py-3.5">Customer</th>
                    <th class="px-6 py-3.5">Date Placed</th>
                    <th class="px-6 py-3.5">Branch</th>
                    <th class="px-6 py-3.5">Order Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($orders as $o)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">#{{ $o->order_number }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $o->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $o->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 font-bold text-brand-600">{{ $o->assigned_branch ?? 'Pending' }}</td>
                    <td class="px-6 py-4"><span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-800">{{ $o->status_label }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
