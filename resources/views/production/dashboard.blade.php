@extends('layouts.internal')
@section('title', 'Dashboard Overview - Production')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Dashboard Overview</h1>
        </div>

        {{-- Right Quick Navigation Actions --}}
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('production.jobs.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-list-check text-xs text-sky-500 dark:text-sky-400"></i> Production Queue
            </a>
            <a href="{{ route('production.machines.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-print text-xs text-sky-500 dark:text-sky-400"></i> Equipment Status
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 OPERATIONAL SHIFT METRICS (PARENT CARD WRAPPER) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl p-5 sm:p-6 shadow-xl">
        <div class="mb-4">
            <h3 class="text-sm font-black text-cyber-main font-display tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-chart-simple text-sky-500"></i> Operational Shift Performance
            </h3>
            <p class="text-xs text-cyber-muted">Real-time throughput, equipment loads, and fulfillment deadlines.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <x-dashboard.kpi-card 
                title="ASSIGNED RUNS"
                :value="$assignedCount"
                icon="fa-solid fa-list-check"
                accent="cyan"
                :link="route('production.jobs.index')"
            />

            <x-dashboard.kpi-card 
                title="ACTIVELY ON PRESS"
                :value="$inProductionCount"
                icon="fa-solid fa-industry"
                accent="indigo"
                :link="route('production.jobs.index')"
            />

            <x-dashboard.kpi-card 
                title="DUE TODAY (DEADLINE)"
                :value="$dueTodayCount"
                icon="fa-solid fa-clock"
                accent="amber"
            />

            <x-dashboard.kpi-card 
                title="COMPLETED THIS SHIFT"
                :value="$completedCount"
                icon="fa-solid fa-circle-check"
                accent="emerald"
            />
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: SHOP FLOOR PRODUCTION PIPELINE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Shop Floor Production Lifecycle"
        :subtitle="null"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 3: ACTIVE ORDERS & PRESS LINE QUEUE (FULL WIDTH)   --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="w-full">
        <x-dashboard.production-table 
            :jobs="$myJobs"
            title="Active Orders & Press Line Queue"
            :subtitle="null"
            :viewAllUrl="route('production.jobs.index')"
            viewAllLabel="Full Floor Queue"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 4: PRESS EQUIPMENT FLEET STATUS (FULL WIDTH)       --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if(isset($pressMachines) && $pressMachines->isNotEmpty())
    <div id="press-equipment-fleet" class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col transition-opacity duration-200 scroll-mt-6">
        <div class="px-5 sm:px-6 py-4 border-b border-cyber/80 flex items-center justify-between bg-cyber-sub/70">
            <div>
                <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">Press Equipment Fleet</h3>
                <p class="text-xs text-cyber-muted mt-0.5">Live machine availability and press floor readiness across facility units.</p>
            </div>
            <a href="{{ route('production.machines.index') }}" class="text-xs font-bold text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 flex items-center gap-1">
                Equipment Logs <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left text-xs">
                <thead class="bg-cyber-base/80 text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                    <tr>
                        <th class="px-6 py-3.5">Machine</th>
                        <th class="px-6 py-3.5">Type</th>
                        <th class="px-6 py-3.5">Model Specifications</th>
                        <th class="px-6 py-3.5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber/60 text-cyber-main">
                    @foreach($pressMachines as $m)
                        <tr class="hover:bg-cyber-hover/50 transition">
                            <td class="px-6 py-4 font-bold text-cyber-main whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-sky-500/10 text-sky-500 dark:text-sky-400 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </div>
                                    <span>{{ $m->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-cyber-muted whitespace-nowrap">
                                <span>{{ $m->type }}</span>
                            </td>
                            <td class="px-6 py-4 text-cyber-muted font-mono text-[11px] whitespace-nowrap">
                                {{ !empty($m->model) ? $m->model : 'Standard Unit' }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                @php
                                    $dotColor = match($m->status) {
                                        'available'   => 'bg-emerald-500',
                                        'in_use'      => 'bg-cyan-500',
                                        'maintenance' => 'bg-amber-500',
                                        default       => 'bg-rose-500',
                                    };
                                    $badgeStyle = match($m->status) {
                                        'available'   => 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30',
                                        'in_use'      => 'bg-cyan-500/15 text-cyan-400 border border-cyan-500/30',
                                        'maintenance' => 'bg-amber-500/15 text-amber-400 border border-amber-500/30',
                                        default       => 'bg-rose-500/15 text-rose-400 border border-rose-500/30',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold uppercase tracking-wider text-[10px] {{ $badgeStyle }} font-mono">
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
            <div class="px-6 py-3.5 border-t border-cyber/60 bg-cyber-sub/40">
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

        // Lock current scroll position
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
                // Keep the exact scroll position untouched so the page does not jump to header
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
