@extends('layouts.internal')
@section('title', 'Evaluate Capacity')
@section('page-title', 'Multi-Factor Capacity Algorithm')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Request Overview Header Card --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 shadow-xl overflow-hidden">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-lg sm:text-xl font-black font-display text-cyber-main">
                            Print Request #PR-{{ str_pad($printRequest->id, 5, '0', STR_PAD_LEFT) }}
                        </h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-cyan-500/15 text-cyan-400 border border-cyan-500/30 uppercase">
                            {{ $printRequest->service }}
                        </span>
                    </div>
                    <p class="text-xs text-cyber-muted mt-1">
                        <strong>{{ number_format($printRequest->quantity) }} copies</strong> &middot;
                        {{ $printRequest->size ?? 'Standard' }} &middot;
                        {{ $printRequest->material ?? 'Default Stock' }} &middot;
                        Customer: <span class="text-cyber-main font-semibold">{{ $printRequest->user->name ?? 'Direct Client' }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0 w-full lg:w-auto justify-end">
                <a href="{{ route('manager.capacity.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition">
                    <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to Queue
                </a>
                <form method="POST" action="{{ route('manager.capacity.run-evaluation', $printRequest) }}">
                    @csrf
                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black px-6 py-2.5 rounded-xl text-xs shadow-[0_0_15px_rgba(6,182,212,0.35)] transition flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-bolt text-xs"></i>
                        <span>Compute &amp; Save Recommendation</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Multi-Factor Algorithm Results --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl p-6 sm:p-7 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyber/80 pb-4">
            <div>
                <h3 class="font-black text-cyber-main text-base font-display">Branch Multi-Factor Score Matrix</h3>
                <p class="text-xs text-cyber-muted mt-0.5">Algorithm calculates fitness based on equipment line readiness, floor load, staff roster, substrate stock, and deadline feasibility (Max 100 pts).</p>
            </div>
            <div class="flex items-center gap-2 text-[11px] text-cyber-muted font-mono shrink-0">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span> Qualified &ge; 70
                <span class="inline-block w-2 h-2 rounded-full bg-amber-400 ml-2"></span> Warning &ge; 50
                <span class="inline-block w-2 h-2 rounded-full bg-rose-400 ml-2"></span> Restricted &lt; 50
            </div>
        </div>

        {{-- Radar Chart Visualization --}}
        <div class="w-full flex justify-center bg-cyber-sub/20 rounded-2xl border border-cyber/50 p-4">
            <div class="relative w-full max-w-2xl h-80">
                <canvas id="capacityRadarChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5">
            @foreach($evaluations as $eval)
            @php
                $statusClass = match($eval['capacity_status']) {
                    'qualified'     => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                    'near_capacity' => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                    default         => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                };
                $totalPct = min(100, max(0, $eval['total_score']));
                $barColor = $totalPct >= 70 ? 'bg-emerald-500' : ($totalPct >= 50 ? 'bg-amber-500' : 'bg-rose-500');
            @endphp
            <div class="p-5 sm:p-6 rounded-2xl border border-cyber bg-cyber-sub/50 hover:bg-cyber-sub/70 transition space-y-5">
                {{-- Branch Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-cyber/60 pb-4">
                    <div class="flex items-center gap-3.5">
                        <div class="h-11 w-11 rounded-xl bg-cyber-card border border-cyber text-cyan-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fa-solid fa-store"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-black text-cyber-main text-base font-display">{{ $eval['branch']->name }}</h4>
                                <span class="text-[10px] text-cyber-sub font-mono">ID #{{ $eval['branch']->id }}</span>
                            </div>
                            <p class="text-xs text-cyber-muted mt-0.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-[10px] text-cyber-sub"></i>
                                {{ $eval['branch']->location }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider border {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $eval['capacity_status'])) }}
                        </span>
                        <div class="text-right shrink-0">
                            <span class="text-2xl sm:text-3xl font-black font-display text-cyber-main">{{ $eval['total_score'] }}</span>
                            <span class="text-xs text-cyber-muted font-bold">/100</span>
                        </div>
                    </div>
                </div>

                {{-- Overall Branch Score Progress Track --}}
                <div class="space-y-1">
                    <div class="flex justify-between text-[11px] font-bold text-cyber-muted">
                        <span>Overall Composite Capacity Score</span>
                        <span class="font-mono text-cyber-main">{{ $eval['total_score'] }}%</span>
                    </div>
                    <div class="h-2 w-full bg-cyber-card rounded-full overflow-hidden border border-cyber">
                        <div class="h-full {{ $barColor }} rounded-full transition-all duration-700 shadow-sm" style="width: {{ $totalPct }}%"></div>
                    </div>
                </div>

                {{-- 5 Detailed Scoring Factor Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    {{-- Machine Factor --}}
                    @php $mPct = ($eval['machine_score'] / 30) * 100; @endphp
                    <div class="bg-cyber-card p-3.5 rounded-xl border border-cyber flex flex-col justify-between space-y-2">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-cyber-muted">Machine (30)</span>
                            <i class="fa-solid fa-print text-xs text-cyan-400"></i>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-lg font-black font-display text-cyber-main">{{ $eval['machine_score'] }}</span>
                            <span class="text-[10px] text-cyber-sub font-mono">pts</span>
                        </div>
                        <div class="h-1.5 w-full bg-cyber-sub rounded-full overflow-hidden">
                            <div class="h-full bg-cyan-400 rounded-full" style="width: {{ $mPct }}%"></div>
                        </div>
                        <p class="text-[10px] text-cyber-muted truncate">{{ $eval['available_machines'] }} available</p>
                    </div>

                    {{-- Workload Factor --}}
                    @php $wPct = ($eval['workload_score'] / 20) * 100; @endphp
                    <div class="bg-cyber-card p-3.5 rounded-xl border border-cyber flex flex-col justify-between space-y-2">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-cyber-muted">Workload (20)</span>
                            <i class="fa-solid fa-gauge-high text-xs text-amber-400"></i>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-lg font-black font-display text-cyber-main">{{ $eval['workload_score'] }}</span>
                            <span class="text-[10px] text-cyber-sub font-mono">pts</span>
                        </div>
                        <div class="h-1.5 w-full bg-cyber-sub rounded-full overflow-hidden">
                            <div class="h-full bg-amber-400 rounded-full" style="width: {{ $wPct }}%"></div>
                        </div>
                        <p class="text-[10px] text-cyber-muted truncate">{{ $eval['workload_pct'] }}% floor load</p>
                    </div>

                    {{-- Employee Factor --}}
                    @php $ePct = ($eval['employee_score'] / 20) * 100; @endphp
                    <div class="bg-cyber-card p-3.5 rounded-xl border border-cyber flex flex-col justify-between space-y-2">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-cyber-muted">Staff (20)</span>
                            <i class="fa-solid fa-users text-xs text-emerald-400"></i>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-lg font-black font-display text-cyber-main">{{ $eval['employee_score'] }}</span>
                            <span class="text-[10px] text-cyber-sub font-mono">pts</span>
                        </div>
                        <div class="h-1.5 w-full bg-cyber-sub rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-400 rounded-full" style="width: {{ $ePct }}%"></div>
                        </div>
                        <p class="text-[10px] text-cyber-muted truncate">Technicians on duty</p>
                    </div>

                    {{-- Material Factor --}}
                    @php $matPct = ($eval['material_score'] / 20) * 100; @endphp
                    <div class="bg-cyber-card p-3.5 rounded-xl border border-cyber flex flex-col justify-between space-y-2">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-cyber-muted">Stock (20)</span>
                            <i class="fa-solid fa-boxes-stacked text-xs text-indigo-400"></i>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-lg font-black font-display text-cyber-main">{{ $eval['material_score'] }}</span>
                            <span class="text-[10px] text-cyber-sub font-mono">pts</span>
                        </div>
                        <div class="h-1.5 w-full bg-cyber-sub rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-400 rounded-full" style="width: {{ $matPct }}%"></div>
                        </div>
                        <p class="text-[10px] text-cyber-muted truncate">Media inventory ready</p>
                    </div>

                    {{-- Deadline Factor --}}
                    @php $dPct = ($eval['deadline_score'] / 10) * 100; @endphp
                    <div class="bg-cyber-card p-3.5 rounded-xl border border-cyber flex flex-col justify-between space-y-2">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-cyber-muted">Deadline (10)</span>
                            <i class="fa-solid fa-clock text-xs {{ $eval['deadline_feasible'] ? 'text-emerald-400' : 'text-rose-400' }}"></i>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-lg font-black font-display {{ $eval['deadline_feasible'] ? 'text-emerald-400' : 'text-rose-400' }}">{{ $eval['deadline_score'] }}</span>
                            <span class="text-[10px] text-cyber-sub font-mono">pts</span>
                        </div>
                        <div class="h-1.5 w-full bg-cyber-sub rounded-full overflow-hidden">
                            <div class="h-full {{ $eval['deadline_feasible'] ? 'bg-emerald-400' : 'bg-rose-400' }} rounded-full" style="width: {{ $dPct }}%"></div>
                        </div>
                        <p class="text-[10px] text-cyber-muted truncate">Est: {{ $eval['estimated_completion'] }}</p>
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
        
        // Prepare datasets for Radar Chart
        const colors = [
            { border: 'rgba(6, 182, 212, 1)', bg: 'rgba(6, 182, 212, 0.2)' }, // Cyan
            { border: 'rgba(56, 189, 248, 0.8)', bg: 'rgba(56, 189, 248, 0.1)' }, // Light Blue
            { border: 'rgba(167, 139, 250, 0.8)', bg: 'rgba(167, 139, 250, 0.1)' }, // Violet
            { border: 'rgba(244, 114, 182, 0.6)', bg: 'rgba(244, 114, 182, 0.05)' }, // Pink
            { border: 'rgba(148, 163, 184, 0.5)', bg: 'rgba(148, 163, 184, 0.05)' } // Slate
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
                        angleLines: { color: 'rgba(255, 255, 255, 0.1)' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        pointLabels: {
                            color: 'rgba(255, 255, 255, 0.7)',
                            font: { family: "'Inter', sans-serif", size: 11, weight: 'bold' }
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
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: { family: "'Inter', sans-serif", size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(6, 182, 212, 0.3)',
                        borderWidth: 1
                    }
                }
            }
        });
    });
</script>
@endsection
