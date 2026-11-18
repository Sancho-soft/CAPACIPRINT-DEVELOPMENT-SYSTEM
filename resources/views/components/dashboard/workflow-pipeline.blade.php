@props([
'stages' => [],
'title' => 'Commercial Printing Lifecycle & Production Flow',
'subtitle' => 'Live order progression across pre-press, scheduling, press execution, and customer claiming',
'badge' => null,
])

<div class="bg-cyber-card border border-cyber rounded-2xl p-5 sm:p-6 relative overflow-hidden shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-cyber-sub">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-600 dark:bg-blue-500"></span>
                <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">{{ $title }}</h3>
            </div>
            @if(!empty($subtitle))
            <p class="text-xs text-cyber-muted mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if(!empty($badge))
        <div class="flex items-center gap-1.5 text-xs font-sans font-semibold text-cyber-muted">
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            <span>{{ $badge }}</span>
        </div>
        @endif
    </div>

    {{-- Horizontal Scrollable Pipeline Track --}}
    <div class="overflow-x-auto no-scrollbar pt-5 pb-1">
        <div class="flex items-start min-w-[760px] justify-between gap-2 relative">

            {{-- Crisp Hairline Track --}}
            <div class="absolute top-[19px] left-10 right-10 h-px bg-cyber z-0" style="background-color: var(--border-main);"></div>

            @foreach($stages as $index => $stage)
            @php
                $isActive = !empty($stage['active']);
                $count = $stage['count'] ?? 0;
                $hasItems = $count > 0;
                $stageUrl = $stage['url'] ?? null;
                $stepCode = str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT);

                if ($isActive) {
                    $nodeStyle = 'bg-blue-600 text-white border-blue-600 dark:border-blue-500 shadow-sm';
                    $badgeStyle = 'bg-blue-600/10 dark:bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-600/25 dark:border-blue-500/30';
                    $titleStyle = 'text-blue-700 dark:text-blue-400 font-bold';
                } elseif ($hasItems) {
                    $nodeStyle = 'bg-cyber-card border-blue-600/60 dark:border-blue-500/60 text-blue-600 dark:text-blue-400';
                    $badgeStyle = 'bg-cyber-sub text-cyber-main border border-cyber';
                    $titleStyle = 'text-cyber-main font-semibold';
                } else {
                    $nodeStyle = 'bg-cyber-sub border-cyber text-cyber-sub';
                    $badgeStyle = 'bg-transparent text-cyber-sub border border-cyber-sub';
                    $titleStyle = 'text-cyber-muted font-medium';
                }
            @endphp

            <{{ $stageUrl ? 'a href='.$stageUrl : 'div' }} class="flex-1 flex flex-col items-center text-center relative z-10 group px-1 {{ $stageUrl ? 'cursor-pointer' : '' }}">
                {{-- Step Node --}}
                <div class="h-10 w-10 rounded-xl border {{ $nodeStyle }} flex items-center justify-center text-sm transition-colors duration-150 group-hover:border-blue-600 dark:group-hover:border-blue-400">
                    <i class="{{ $stage['icon'] ?? 'fa-solid fa-circle' }}"></i>
                </div>

                {{-- Step Number & Title --}}
                <span class="text-[11px] font-sans font-medium text-cyber-sub mt-2 tabular-nums">{{ $stepCode }}</span>
                <span class="text-xs {{ $titleStyle }} mt-0.5 leading-tight line-clamp-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                    {{ $stage['label'] }}
                </span>

                {{-- Step Count Badge --}}
                <div class="mt-1.5 min-h-[20px] flex items-center justify-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold font-sans tabular-nums {{ $badgeStyle }}">
                        {{ $count }} {{ $count == 1 ? 'item' : 'items' }}
                    </span>
                </div>
            </{{ $stageUrl ? 'a' : 'div' }}>
            @endforeach

        </div>
    </div>
</div>
