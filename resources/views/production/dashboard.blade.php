@extends('layouts.internal')
@section('title', 'Press Floor Overview — Production')
@section('page-title', 'Press Floor Overview')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-[1.75rem] font-bold font-display tracking-tight text-cyber-main">Press Floor Overview</h1>
            <p class="text-sm text-cyber-muted mt-1">Assigned shift runs, live press line status, and hardware readiness.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('production.jobs.index') }}" class="px-4 py-2 rounded-lg bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-list-check text-xs"></i>
                <span>Production Queue</span>
            </a>
            <a href="{{ route('production.machines.index') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-print text-xs text-[#2F7BFF]"></i>
                <span>Equipment Status</span>
            </a>
        </div>
    </div>

    {{-- 4 OPERATIONAL SHIFT METRICS (Unboxed direct KPI strip, no double-wrapped card) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="Assigned Runs"
            :value="$assignedCount"
            accent="blue"
            :link="route('production.jobs.index')"
        />

        <x-dashboard.kpi-card 
            title="Actively on Press"
            :value="$inProductionCount"
            accent="blue"
            :link="route('production.jobs.index')"
        />

        <x-dashboard.kpi-card 
            title="Due Today"
            :value="$dueTodayCount"
            accent="amber"
        />

        <x-dashboard.kpi-card 
            title="Completed This Shift"
            :value="$completedCount"
            accent="emerald"
        />
    </div>

    {{-- SHOP FLOOR PRODUCTION PIPELINE --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Shop Floor Production Lifecycle"
        :subtitle="null"
    />

    {{-- ACTIVE ORDERS & PRESS LINE QUEUE --}}
    <div class="w-full">
        <x-dashboard.production-table 
            :jobs="$myJobs"
            title="Active Orders &amp; Press Line Queue"
            :subtitle="null"
            :viewAllUrl="route('production.jobs.index')"
            viewAllLabel="Full Floor Queue"
        />
    </div>

    {{-- PRESS EQUIPMENT FLEET STATUS --}}
    @if(isset($pressMachines) && $pressMachines->isNotEmpty())
    <div id="press-equipment-fleet" class="bg-cyber-card border border-cyber rounded-xl overflow-hidden flex flex-col transition-opacity duration-200 scroll-mt-6">
        <div class="px-5 sm:px-6 py-4 border-b border-cyber flex items-center justify-between bg-cyber-sub">
            <div>
                <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">Press Equipment Fleet</h3>
                <p class="text-xs text-cyber-muted mt-0.5">Live machine availability and press floor readiness across facility units.</p>
            </div>
            <a href="{{ route('production.machines.index') }}" class="text-xs font-semibold text-[#2F7BFF] dark:text-[#60A5FA] hover:underline flex items-center gap-1.5">
                <span>Equipment Logs</span>
                <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="bg-cyber-sub text-cyber-muted font-sans font-semibold border-b border-cyber text-xs">
                    <tr>
                        <th class="px-6 py-3">Machine</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3">Model Specifications</th>
                        <th class="px-6 py-3 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main">
                    @foreach($pressMachines as $m)
                        @php
                            $dotColor = match($m->status) {
                                'available' => 'bg-[#10B981]',
                                'in_use'    => 'bg-[#2F7BFF]',
                                default     => 'bg-[#FFC531]',
                            };
                            $textTone = match($m->status) {
                                'available' => 'text-emerald-700 dark:text-[#10B981]',
                                'in_use'    => 'text-[#2F7BFF] dark:text-[#60A5FA]',
                                default     => 'text-amber-700 dark:text-[#FFC531]',
                            };
                        @endphp
                        <tr class="hover:bg-black/[0.02] dark:hover:bg-white/[0.025] transition-colors">
                            <td class="px-6 py-3.5 font-semibold text-cyber-main whitespace-nowrap">
                                {{ $m->name }}
                            </td>
                            <td class="px-6 py-3.5 text-cyber-muted whitespace-nowrap">
                                {{ $m->type }}
                            </td>
                            <td class="px-6 py-3.5 text-cyber-muted font-mono text-xs whitespace-nowrap">
                                {{ !empty($m->model) ? $m->model : 'Standard Unit' }}
                            </td>
                            <td class="px-6 py-3.5 text-right whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 font-semibold text-xs {{ $textTone }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                    {{ ucfirst(str_replace('_', ' ', $m->status)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($pressMachines->hasPages())
            <div class="px-6 py-3.5 border-t border-cyber bg-cyber-sub">
                {{ $pressMachines->appends(request()->except('machines_page'))->fragment('press-equipment-fleet')->links() }}
            </div>
        @endif
    </div>
    @endif

</div>

{{-- In-Place Pagination Script (Zero Jump / No Header Scrolling for both tables) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const link = e.target.closest('#press-equipment-fleet nav a, #press-equipment-fleet .pagination a, #press-equipment-fleet a[href*="machines_page="], #press-line-queue nav a, #press-line-queue .pagination a, #press-line-queue a[href*="jobs_page="]');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href || href === '#' || href.startsWith('javascript:')) return;

        e.preventDefault();

        const container = link.closest('#press-equipment-fleet, #press-line-queue');
        if (!container) {
            window.location.href = href;
            return;
        }

        const containerId = container.id;
        const currentScrollY = window.scrollY;

        container.style.opacity = '0.5';
        container.style.pointerEvents = 'none';

        fetch(href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Network error');
            return res.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContainer = doc.getElementById(containerId);
            if (newContainer) {
                container.innerHTML = newContainer.innerHTML;
                window.history.pushState({ path: href }, '', href);
                window.scrollTo({ top: currentScrollY, behavior: 'instant' });
            } else {
                window.location.href = href;
            }
        })
        .catch(() => {
            window.location.href = href;
        })
        .finally(() => {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        });
    });
});
</script>
@endsection
