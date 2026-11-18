@extends('layouts.customer')
@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')
<div class="space-y-6 w-full">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight">My Orders</h2>
            <p class="text-xs sm:text-sm text-cyber-muted mt-1">Monitor all your print orders and their live production progress.</p>
        </div>
        <a href="{{ route('customer.print-requests.create') }}"
           class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-4 py-2.5 rounded-lg text-xs transition-colors flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i> New Request
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('customer.orders.index') }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ !request('status') && !request('filter') ? 'bg-brand-600 text-white' : 'bg-cyber-card border border-cyber text-cyber-muted hover:text-cyber-main' }}">
            All Orders
        </a>
        <a href="{{ route('customer.orders.index', ['status' => 'active']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ request('status') === 'active' || request('filter') === 'active' ? 'bg-brand-600 text-white' : 'bg-cyber-card border border-cyber text-cyber-muted hover:text-cyber-main' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ request('status') === 'active' || request('filter') === 'active' ? 'bg-white' : 'bg-brand-600 dark:bg-blue-400' }}"></span> Active Orders
        </a>
        <a href="{{ route('customer.orders.index', ['status' => 'completed']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 {{ request('status') === 'completed' || request('filter') === 'completed' ? 'bg-emerald-600 text-white' : 'bg-cyber-card border border-cyber text-cyber-muted hover:text-cyber-main' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ request('status') === 'completed' || request('filter') === 'completed' ? 'bg-white' : 'bg-emerald-600' }}"></span> Completed Orders
        </a>
    </div>

    <div class="bg-cyber-card border border-cyber rounded-xl overflow-hidden">
        @if($orders->isEmpty())
        <div class="p-14 text-center">
            <div class="h-14 w-14 mx-auto rounded-xl bg-cyber-sub text-cyber-muted border border-cyber flex items-center justify-center text-xl mb-4">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h4 class="font-bold text-cyber-main text-base font-display">No orders yet</h4>
            <p class="text-xs text-cyber-muted mt-1">Submit a print request and approved jobs will appear here.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyber-sub text-xs tabular-nums">
                <thead class="bg-cyber-sub border-b border-cyber">
                    <tr>
                        <th class="px-5 py-3 text-left text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Order No.</th>
                        <th class="px-5 py-3 text-left text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Service</th>
                        <th class="px-5 py-3 text-left text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Branch</th>
                        <th class="px-5 py-3 text-left text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Est. Completion</th>
                        <th class="px-5 py-3 text-left text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Payment</th>
                        <th class="px-5 py-3 text-left text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Status</th>
                        <th class="px-5 py-3 text-right text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main">
                    @foreach($orders as $order)
                    <tr class="hover:bg-cyber-sub/50 transition-colors">
                        <td class="px-5 py-3.5 font-sans tabular-nums font-bold text-cyber-main tabular-nums">#{{ $order->order_number }}</td>
                        <td class="px-5 py-3.5 text-cyber-main font-medium">{{ $order->printRequest->service ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-cyber-muted">{{ $order->assigned_branch ?? 'Routing...' }}</td>
                        <td class="px-5 py-3.5 font-sans tabular-nums text-cyber-muted tabular-nums">{{ $order->estimated_completion?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-0.5 text-[11px] font-sans tabular-nums font-semibold rounded uppercase {{ $order->payment_status === 'confirmed' ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20' : ($order->payment_status === 'submitted' ? 'bg-blue-500/10 text-brand-700 dark:text-blue-400 border border-blue-500/20' : 'bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20') }}">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-xs font-sans tabular-nums font-semibold uppercase
                                {{ match($order->status) {
                                    'submitted'        => 'text-brand-600 dark:text-blue-400',
                                    'quotation'        => 'text-amber-600 dark:text-amber-400',
                                    'payment'          => 'text-orange-600 dark:text-orange-400',
                                    'production'       => 'text-brand-700 dark:text-blue-400',
                                    'completed'        => 'text-emerald-700 dark:text-emerald-400',
                                    'ready_for_pickup' => 'text-teal-700 dark:text-teal-400',
                                    'claimed'          => 'text-cyber-muted',
                                    default            => 'text-cyber-muted',
                                } }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-2">
                                <a href="{{ route('customer.orders.tracking', $order) }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition inline-flex items-center justify-center">
                                    Track
                                </a>
                                <a href="{{ route('customer.orders.show', $order) }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white transition inline-flex items-center justify-center">
                                    Details
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-cyber">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
