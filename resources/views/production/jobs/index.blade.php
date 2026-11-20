@extends('layouts.internal')
@section('title', 'My Jobs')
@section('page-title', 'My Production Jobs')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black font-display tracking-tight text-slate-900 dark:text-white">My Production Jobs</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Review assigned print runs, inspect client artwork, and record floor progress.</p>
        </div>

        <form method="GET" action="{{ route('production.jobs.index') }}" class="flex items-center">
            <div class="relative flex items-center w-full sm:w-80 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-1 shadow-sm focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition">
                <div class="pl-3 pr-1 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs text-blue-500"></i>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search Job #, customer, service..."
                       autocomplete="off"
                       class="w-full bg-transparent border-0 px-2.5 py-1.5 text-xs font-medium text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-0">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-3.5 py-1.5 rounded-lg text-xs font-bold transition shadow-sm shrink-0 cursor-pointer">
                    Search
                </button>
            </div>
        </form>
    </div>

    <div id="production-jobs-table-card" class="bg-white dark:bg-[#151922] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="bg-slate-50 dark:bg-[#101319] text-slate-500 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-800 text-[11px]">
                    <tr>
                        <th class="px-6 py-3.5">Job #</th>
                        <th class="px-6 py-3.5">Customer &amp; Service</th>
                        <th class="px-6 py-3.5">Machine</th>
                        <th class="px-6 py-3.5">Priority</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-200">
                    @forelse($jobs as $j)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white font-sans tabular-nums">#{{ $j->job_number }}</td>
                        <td class="px-6 py-4">
                            <strong class="text-slate-900 dark:text-white block">{{ $j->order->printRequest->service ?? '—' }}</strong>
                            <span class="block text-[11px] text-slate-400 mt-0.5 font-medium">Cust: {{ $j->order->user->name ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300 font-medium">{{ $j->machine->name ?? 'Not Set' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-semibold uppercase {{ match(strtolower($j->priority)) {
                                'urgent', 'rush' => 'text-amber-600 dark:text-amber-400',
                                default          => 'text-emerald-600 dark:text-emerald-400',
                            } }}">{{ $j->priority }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-semibold uppercase {{ match($j->status) {
                                'in_production', 'production' => 'text-blue-600 dark:text-blue-400',
                                'completed'                   => 'text-emerald-600 dark:text-emerald-400',
                                'delayed'                     => 'text-rose-600 dark:text-rose-400',
                                'quality_checking'            => 'text-indigo-600 dark:text-indigo-400',
                                default                       => 'text-slate-500 dark:text-slate-400',
                            } }}">{{ $j->status_label }}</span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-2.5">
                                <a href="{{ route('production.jobs.show', $j) }}" 
                                   class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1 inline-flex items-center justify-center" 
                                   title="View Specs &amp; Artwork">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('production.jobs.status-form', $j) }}" 
                                   class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-3 py-1.5 rounded-lg text-xs shadow-sm transition inline-flex items-center justify-center">
                                    Update Status
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                            No production jobs found matching your search.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-800">{{ $jobs->links() }}</div>
    </div>
</div>
@endsection
