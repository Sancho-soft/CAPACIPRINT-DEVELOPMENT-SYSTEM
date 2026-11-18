@extends('layouts.internal')
@section('title', 'Evaluate Capacity')
@section('page-title', 'Multi-Factor Capacity Algorithm')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Request Overview Header Card --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-5 sm:p-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="h-11 w-11 rounded-xl bg-brand-600/10 border border-brand-600/20 text-brand-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-lg sm:text-xl font-bold font-display text-cyber-main tracking-tight">
                            Print Request <span class="font-sans tabular-nums">#PR-{{ str_pad($printRequest->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-sans font-semibold bg-brand-600/10 text-brand-700 dark:text-blue-400 border border-brand-600/25">
                            {{ $printRequest->service }}
                        </span>
                    </div>
                    <p class="text-xs text-cyber-muted mt-1">
                        <strong class="font-sans tabular-nums text-cyber-main">{{ number_format($printRequest->quantity) }} copies</strong> &middot;
                        {{ $printRequest->size ?? 'Standard' }} &middot;
                        {{ $printRequest->material ?? 'Default Stock' }} &middot;
                        Customer: <span class="text-cyber-main font-semibold">{{ $printRequest->user->name ?? 'Direct Client' }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 shrink-0 w-full lg:w-auto justify-end">
                <a href="{{ route('manager.capacity.index') }}" class="px-4 py-2.5 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-semibold text-xs transition shadow-sm">
                    <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to Queue
                </a>
                <form method="POST" action="{{ route('manager.capacity.run-evaluation', $printRequest) }}">
                    @csrf
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs transition flex items-center gap-2 cursor-pointer shadow-sm">
                        <i class="fa-solid fa-bolt text-xs"></i>
                        <span>Compute &amp; Save Recommendation</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Multi-Factor Algorithm Results --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-5 sm:p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyber-sub pb-4">
            <div>
                <h3 class="font-bold text-cyber-main text-base font-display">Branch Multi-Factor Score Matrix</h3>
                <p class="text-xs text-cyber-muted mt-0.5">Algorithm calculates fitness based on equipment line readiness, floor load, staff roster, substrate stock, and deadline feasibility (Max 100 pts).</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-cyber-muted font-sans tabular-nums shrink-0">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-600"></span> Qualified &ge; 70
                <span class="inline-block w-2 h-2 rounded-full bg-amber-600 ml-2"></span> Warning &ge; 50
                <span class="inline-block w-2 h-2 rounded-full bg-rose-600 ml-2"></span> Restricted &lt; 50
            </div>
        </div>

        {{-- Radar Chart Visualization --}}
        <div class="w-full flex justify-center bg-cyber-sub rounded-2xl border border-cyber p-4">
            <div class="relative w-full max-w-2xl h-80">
                <canvas id="capacityRadarChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5">
            @foreach($evaluations as $eval)
            @php
                $statusClass = match($eval['capacity_status']) {
                    'qualified'     => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border-emerald-500/30',
                    'near_capacity' => 'bg-amber-500/15 text-amber-800 dark:text-amber-400 border-amber-500/30',
                    default         => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border-rose-500/30',
                };
                $totalPct = min(100, max(0, $eval['total_score']));
                $barColor = $totalPct >= 70 ? 'bg-emerald-600' : ($totalPct >= 50 ? 'bg-amber-600' : 'bg-rose-600');
            @endphp
            <div class="p-5 sm:p-6 rounded-2xl border border-cyber bg-cyber-sub/50 space-y-5">
                {{-- Branch Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-cyber-sub pb-4">
                    <div class="flex items-center gap-3.5">
                        <div class="h-10 w-10 rounded-xl bg-cyber-card border border-cyber text-brand-600 dark:text-blue-400 flex items-center justify-center text-base font-bold shrink-0">
                            <i class="fa-solid fa-store"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-cyber-main text-base font-display">{{ $eval['branch']->name }}</h4>
                                <span class="text-xs text-cyber-muted font-sans tabular-nums">ID #{{ $eval['branch']->id }}</span>
                            </div>
                            <p class="text-xs text-cyber-muted mt-0.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-[11px] text-cyber-muted"></i>
                                {{ $eval['branch']->location }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="px-3 py-1 rounded-full text-xs font-sans font-semibold border {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $eval['capacity_status'])) }}
                        </span>
                        <div class="text-right shrink-0 tabular-nums">
                            <span class="text-2xl sm:text-3xl font-bold font-display text-cyber-main">{{ $eval['total_score'] }}</span>
                            <span class="text-xs text-cyber-muted font-sans tabular-nums">/100</span>
                        </div>
                    </div>
                </div>

                {{-- Overall Branch Score Progress Track --}}
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-semibold text-cyber-muted">
                        <span>Overall Composite Capacity Score</span>
                        <span class="font-sans text-cyber-main tabular-nums">{{ $eval['total_score'] }}%</span>
                    </div>
                    <div class="h-2.5 w-full bg-cyber-card rounded-full overflow-hidden border border-cyber">
                        <div class="h-full {{ $barColor }} rounded-full transition-all duration-700" style="width: {{ $totalPct }}%"></div>
                    </div>
                </div>

                {{-- 5 Detailed Scoring Factor Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 tabular-nums">
                    {{-- Machine Factor --}}
                    @php $mPct = ($eval['machine_score'] / 30) * 100; @endphp
                    <div class="bg-cyber-card p-3.5 rounded-xl border border-cyber flex flex-col justify-between space-y-2">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-sans font-semibold text-cyber-muted">Machine (30)</span>
                            <i class="fa-solid fa-print text-xs text-brand-600 dark:text-blue-400"></i>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-lg font-bold font-display text-cyber-main">{{ $eval['machine_score'] }}</span>
                            <span class="text-[11px] text-cyber-muted font-sans">pts</span>
                        </div>
                        <div class="h-1.5 w-full bg-cyber-sub rounded-full overflow-hidden">
                            <div class="h-full bg-brand-600 dark:bg-blue-500 rounded-full" style="width: {{ $mPct }}%"></div>
                        </div>
                        <p class="text-[11px] text-cyber-muted truncate">{{ $eval['available_machines'] }} available</p>
                    </div>

                    {{-- Workload Factor --}}
                    @php $wPct = ($eval['workload_score'] / 20) * 100; @endphp
                    <div class="bg-cyber-card p-3.5 rounded-xl border border-cyber flex flex-col justify-between space-y-2">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-sans font-semibold text-cyber-muted">Workload (20)</span>
                            <i class="fa-solid fa-gauge-high text-xs text-amber-600 dark:text-amber-400"></i>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-lg font-bold font-display text-cyber-main">{{ $eval['workload_score'] }}</span>
                            <span class="text-[11px] text-cyber-muted font-sans">pts</span>
                        </div>
                        <div class="h-1.5 w-full bg-cyber-sub rounded-full overflow-hidden">
                            <div class="h-full bg-amber-600 dark:bg-amber-400 rounded-full" style="width: {{ $wPct }}%"></div>
                        </div>
                        <p class="text-[11px] text-cyber-muted truncate">{{ $eval['workload_pct'] }}% floor load</p>
                    </div>

                    {{-- Employee Factor --}}
                    @php $ePct = ($eval['employee_score'] / 20) * 100; @endphp
                    <div class="bg-cyber-card p-3.5 rounded-xl border border-cyber flex flex-col justify-between space-y-2">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-sans font-semibold text-cyber-muted">Staff (20)</span>
                            <i class="fa-solid fa-users text-xs text-emerald-600 dark:text-emerald-400"></i>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-lg font-bold font-display text-cyber-main">{{ $eval['employee_score'] }}</span>
                            <span class="text-[11px] text-cyber-muted font-sans">pts</span>
                        </div>
                        <div class="h-1.5 w-full bg-cyber-sub rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-600 dark:bg-emerald-400 rounded-full" style="width: {{ $ePct }}%"></div>
                        </div>
                        <p class="text-[11px] text-cyber-muted truncate">Technicians on duty</p>
                    </div>

                    {{-- Material Factor --}}
                    @php $matPct = ($eval['material_score'] / 20) * 100; @endphp
                    <div class="bg-cyber-card p-3.5 rounded-xl border border-cyber flex flex-col justify-between space-y-2">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-sans font-semibold text-cyber-muted">Stock (20)</span>
                            <i class="fa-solid fa-boxes-stacked text-xs text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-lg font-bold font-display text-cyber-main">{{ $eval['material_score'] }}</span>
                            <span class="text-[11px] text-cyber-muted font-sans">pts</span>
                        </div>
                        <div class="h-1.5 w-full bg-cyber-sub rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-600 dark:bg-indigo-400 rounded-full" style="width: {{ $matPct }}%"></div>
                        </div>
                        <p class="text-[11px] text-cyber-muted truncate">Media inventory ready</p>
                    </div>

                    {{-- Deadline Factor --}}
                    @php $dPct = ($eval['deadline_score'] / 10) * 100; @endphp
                    <div class="bg-cyber-card p-3.5 rounded-xl border border-cyber flex flex-col justify-between space-y-2">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-sans font-semibold text-cyber-muted">Deadline (10)</span>
                            <i class="fa-solid fa-clock text-xs {{ $eval['deadline_feasible'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}"></i>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-lg font-bold font-display {{ $eval['deadline_feasible'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ $eval['deadline_score'] }}</span>
                            <span class="text-[11px] text-cyber-muted font-sans">pts</span>
                        </div>
                        <div class="h-1.5 w-full bg-cyber-sub rounded-full overflow-hidden">
                            <div class="h-full {{ $eval['deadline_feasible'] ? 'bg-emerald-600 dark:bg-emerald-400' : 'bg-rose-600 dark:bg-rose-400' }} rounded-full" style="width: {{ $dPct }}%"></div>
                        </div>
                        <p class="text-[11px] text-cyber-muted truncate">Est: {{ $eval['estimated_completion'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const evaluations = @json($evaluations);
        const isDark = document.documentElement.classList.contains('dark') || document.documentElement.classList.contains('dark-theme');
        
        // Prepare datasets for Radar Chart
        const colors = [
            { border: 'rgba(37, 99, 235, 1)', bg: 'rgba(37, 99, 235, 0.2)' },
            { border: 'rgba(59, 130, 246, 0.85)', bg: 'rgba(59, 130, 246, 0.12)' },
            { border: 'rgba(99, 102, 241, 0.85)', bg: 'rgba(99, 102, 241, 0.12)' },
            { border: 'rgba(16, 185, 129, 0.75)', bg: 'rgba(16, 185, 129, 0.08)' },
            { border: 'rgba(148, 163, 184, 0.6)', bg: 'rgba(148, 163, 184, 0.06)' }
        ];

        const datasets = evaluations.map((eval, index) => {
            const color = colors[index % colors.length];
            return {
                label: eval.branch.name,
                data: [
                    eval.machine_score,
                    eval.workload_score,
                    eval.employee_score,
                    eval.material_score,
                    eval.deadline_score
                ],
                backgroundColor: color.bg,
                borderColor: color.border,
                pointBackgroundColor: color.border,
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: color.border,
                borderWidth: 2,
            };
        });

        const ctx = document.getElementById('capacityRadarChart').getContext('2d');
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: [
                    'Machine (Max 30)', 
                    'Workload (Max 20)', 
                    'Staff (Max 20)', 
                    'Material (Max 20)', 
                    'Deadline (Max 10)'
                ],
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(15, 23, 42, 0.1)' },
                        grid: { color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(15, 23, 42, 0.1)' },
                        pointLabels: {
                            color: isDark ? '#cbd5e1' : '#334155',
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: 'bold' }
                        },
                        ticks: {
                            display: false,
                            min: 0,
                            max: 30
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: isDark ? '#e2e8f0' : '#334155',
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: isDark ? 'rgba(15, 23, 42, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                        titleColor: isDark ? '#f8fafc' : '#0f172a',
                        bodyColor: isDark ? '#cbd5e1' : '#475569',
                        borderColor: isDark ? '#334155' : '#e2e8f0',
                        borderWidth: 1
                    }
                }
            }
        });
    });
</script>
@endsection
