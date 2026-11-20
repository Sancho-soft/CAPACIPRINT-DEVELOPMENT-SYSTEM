@props([
    'stages' => [],
    'title' => 'Commercial Printing Lifecycle & Production Flow',
    'subtitle' => null,
    'badge' => null,
])

<div class="bg-cyber-card border border-cyber rounded-xl overflow-hidden">
    <div class="px-5 sm:px-6 py-4 border-b border-cyber flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-cyber-sub">
        <div>
            <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">{{ $title }}</h3>
            @if(!empty($subtitle))
                <p class="text-xs text-cyber-muted mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if(!empty($badge))
            <div class="flex items-center gap-1.5 text-xs font-sans font-semibold text-cyber-muted">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                <span>{{ $badge }}</span>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 divide-y sm:divide-y-0 sm:divide-x divide-cyber">
        @foreach($stages as $index => $stage)
            @php
                $isActive = !empty($stage['active']);
                $count = $stage['count'] ?? 0;
                $hasItems = $count > 0;
                $stageUrl = $stage['url'] ?? null;
                $stepCode = str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT);
            @endphp

            <{{ $stageUrl ? 'a href='.$stageUrl : 'div' }}
                class="p-4 flex flex-col justify-between gap-2.5 transition-colors group {{ $isActive ? 'bg-[#2F7BFF]/8' : ($stageUrl ? 'hover:bg-cyber-sub/60 cursor-pointer' : '') }}"
            >
                <div class="flex items-center justify-between gap-2">
                    <span class="font-mono text-xs {{ $isActive || $hasItems ? 'text-[#2F7BFF] dark:text-[#60A5FA] font-semibold' : 'text-cyber-sub' }}">{{ $stepCode }}</span>
                    @if($isActive)
                        <span class="w-1.5 h-1.5 rounded-full bg-[#2F7BFF]"></span>
                    @endif
                </div>

                <div class="space-y-1">
                    <span class="text-[13px] font-semibold block leading-snug {{ $isActive ? 'text-[#2F7BFF] dark:text-[#60A5FA]' : ($hasItems ? 'text-cyber-main' : 'text-cyber-muted') }} group-hover:text-cyber-main transition-colors">
                        {{ $stage['label'] }}
                    </span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="font-mono text-xl font-bold tabular-nums {{ $hasItems ? 'text-cyber-main' : 'text-cyber-sub' }}">{{ $count }}</span>
                        <span class="text-xs text-cyber-muted">{{ $count == 1 ? 'job' : 'jobs' }}</span>
                    </div>
                </div>
            </{{ $stageUrl ? 'a' : 'div' }}>
        @endforeach
    </div>
</div>
