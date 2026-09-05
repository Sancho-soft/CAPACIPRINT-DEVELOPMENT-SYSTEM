@props([
    'stages' => [],
    'title' => 'Commercial Printing Lifecycle & Production Flow',
    'subtitle' => 'Live order progression across pre-press, scheduling, press execution, and customer claiming',
])

{{-- 
Expected $stages array format:
[
    ['key' => 'intake', 'label' => 'Intake & Proofing', 'count' => 5, 'icon' => 'fa-solid fa-file-arrow-up', 'color' => 'cyan'],
    ['key' => 'quotation', 'label' => 'Quotation Matrix', 'count' => 3, 'icon' => 'fa-solid fa-file-invoice-dollar', 'color' => 'indigo'],
    ['key' => 'payment', 'label' => 'Payment Verification', 'count' => 2, 'icon' => 'fa-solid fa-credit-card', 'color' => 'amber'],
    ['key' => 'routing', 'label' => 'Capacity Routing', 'count' => 4, 'icon' => 'fa-solid fa-network-wired', 'color' => 'blue'],
    ['key' => 'production', 'label' => 'On Press Execution', 'count' => 6, 'icon' => 'fa-solid fa-industry', 'color' => 'teal', 'active' => true],
    ['key' => 'qc', 'label' => 'Quality & Packaging', 'count' => 1, 'icon' => 'fa-solid fa-microscope', 'color' => 'purple'],
    ['key' => 'ready', 'label' => 'Ready / Claiming', 'count' => 8, 'icon' => 'fa-solid fa-box-open', 'color' => 'emerald'],
]
--}}

<div class="bg-cyber-card border border-cyber rounded-3xl p-5 sm:p-6 shadow-xl relative overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-cyber/80">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">{{ $title }}</h3>
            </div>
            <p class="text-[11px] sm:text-xs text-cyber-muted mt-0.5">{{ $subtitle }}</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted">
                Real-time Flow
            </span>
        </div>
    </div>

    {{-- Horizontal Scrollable Pipeline Track --}}
    <div class="overflow-x-auto no-scrollbar pt-5 pb-2">
        <div class="flex items-center min-w-[760px] justify-between gap-2 relative">
            
            {{-- Background Connecting Line --}}
            <div class="absolute top-5 left-8 right-8 h-0.5 bg-slate-800 dark:bg-slate-800/80 -z-0"></div>

            @foreach($stages as $index => $stage)
                @php
                    $isActive = !empty($stage['active']);
                    $count = $stage['count'] ?? 0;
                    $hasItems = $count > 0;

                    // Executive enterprise color language:
                    // Active/Focus stage: refined Primary Blue/Sky
                    // Stages with backlog/items: professional navy/slate with crisp contrast
                    // Idle stages: subdued muted slate
                    if ($isActive) {
                        $circleStyle = 'bg-sky-500 text-white border-sky-400 shadow-md ring-4 ring-sky-500/20';
                        $badgeStyle  = 'bg-sky-500/15 text-sky-600 dark:text-sky-300 border-sky-500/30';
                        $titleStyle  = 'text-sky-600 dark:text-sky-400 font-extrabold';
                    } elseif ($hasItems) {
                        $circleStyle = 'bg-cyber-card border-slate-400 dark:border-slate-600 text-slate-700 dark:text-slate-200 group-hover:border-sky-500 group-hover:text-sky-500';
                        $badgeStyle  = 'bg-slate-200/60 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700';
                        $titleStyle  = 'text-cyber-main font-bold';
                    } else {
                        $circleStyle = 'bg-cyber-sub border-cyber text-cyber-muted opacity-60';
                        $badgeStyle  = 'bg-cyber-sub text-cyber-muted border-cyber opacity-60';
                        $titleStyle  = 'text-cyber-muted font-medium';
                    }
                @endphp

                <div class="flex-1 flex flex-col items-center text-center relative z-10 group px-1">
                    {{-- Step Node --}}
                    <div class="h-10 w-10 rounded-2xl border-2 {{ $circleStyle }} flex items-center justify-center text-sm transition-all duration-200 group-hover:scale-105">
                        <i class="{{ $stage['icon'] ?? 'fa-solid fa-circle' }}"></i>
                    </div>

                    {{-- Step Title --}}
                    <span class="text-[11px] {{ $titleStyle }} mt-2.5 leading-tight line-clamp-1 group-hover:text-sky-500 dark:group-hover:text-sky-400 transition-colors">
                        {{ $stage['label'] }}
                    </span>

                    {{-- Step Count Badge --}}
                    <div class="mt-1.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black font-mono border {{ $badgeStyle }}">
                            {{ $count }} {{ $count == 1 ? 'item' : 'items' }}
                        </span>
                    </div>
                </div>

                {{-- Arrow connector if not last --}}
                @if(!$loop->last)
                    <div class="shrink-0 text-cyber-sub/60 -mt-7 z-10">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </div>
                @endif
            @endforeach

        </div>
    </div>
</div>
