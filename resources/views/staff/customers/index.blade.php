@extends('layouts.internal')
@section('title', 'Customers Directory')
@section('page-title', 'Customer Directory')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-navy-900 font-display">Customers Directory</h2>
        <form method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer name or email..."
                   class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
        </form>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Customer Name</th>
                    <th class="px-6 py-3.5">Email</th>
                    <th class="px-6 py-3.5">Phone</th>
                    <th class="px-6 py-3.5">Total Orders</th>
                    <th class="px-6 py-3.5">Print Requests</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($customers as $c)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">{{ $c->name }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $c->email }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $c->phone ?? '—' }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $c->orders_count }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $c->print_requests_count }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('staff.customers.show', $c) }}" class="text-brand-600 font-bold hover:underline">View History &rarr;</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $customers->links() }}</div>
    </div>
</div>
@endsection
