@props([
    'items' => [], // array of attention items
    'title' => 'Operational Attention Center',
    'subtitle' => 'High-priority items requiring immediate supervision or action',
])

@php
    $hasItems = count($items) > 0;
@endphp

<div class="bg-cyber-card border {{ $hasItems ? 'border-amber-500/40' : 'border-cyber' }} rounded-2xl overflow-hidden shadow-sm">
    <div class="px-5 sm:px-6 py-4 {{ $hasItems ? 'bg-amber-500/10 border-b border-amber-500/20' : 'bg-cyber-sub border-b border-cyber-sub' }} flex items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <div class="h-8 w-8 rounded-xl {{ $hasItems ? 'bg-amber-500/15 text-amber-700 dark:text-amber-400' : 'bg-cyber-card text-cyber-muted border border-cyber' }} flex items-center justify-center text-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="font-bold text-xs sm:text-sm text-cyber-main font-display">{{ $title }}</h3>
                @if(!empty($subtitle))
                    <p class="text-[11px] text-cyber-muted">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        <div>
            @if($hasItems)
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold font-sans bg-amber-500/15 text-amber-800 dark:text-amber-300 border border-amber-500/30 tabular-nums">
                    {{ count($items) }} Action{{ count($items) > 1 ? 's' : '' }} Pending
                </span>
            @else
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold font-sans bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30">
                    <i class="fa-solid fa-check mr-1"></i> All Clear
                </span>
            @endif
        </div>
    </div>

    @if($hasItems)
        <div class="divide-y divide-cyber-sub text-xs">
            @foreach($items as $item)
                @php
                    $severity = $item['severity'] ?? 'warning'; // critical, warning, info
                    $badgeStyle = match($severity) {
                        'critical' => 'bg-rose-500/10 text-rose-700 dark:text-rose-400 border-rose-500/25',
                        'info'     => 'bg-blue-600/10 dark:bg-blue-500/15 text-blue-700 dark:text-blue-300 border-blue-600/25 dark:border-blue-500/30',
                        default    => 'bg-amber-500/10 text-amber-800 dark:text-amber-300 border-amber-500/25',
                    };
                    $iconStyle = match($severity) {
                        'critical' => 'text-rose-600 dark:text-rose-400',
                        'info'     => 'text-blue-600 dark:text-blue-400',
                        default    => 'text-amber-600 dark:text-amber-400',
                    };
                @endphp
                <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-black/[0.02] dark:hover:bg-white/[0.02] transition-colors">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="mt-0.5 shrink-0 {{ $iconStyle }}">
                            <i class="{{ $item['icon'] ?? 'fa-solid fa-circle-exclamation' }}"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-semibold text-cyber-main text-xs sm:text-sm">{{ $item['title'] }}</span>
                                @if(!empty($item['badge']))
                                    <span class="text-[11px] font-semibold font-sans px-2.5 py-0.5 rounded-full border {{ $badgeStyle }}">
                                        {{ $item['badge'] }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-cyber-muted text-xs mt-0.5 leading-relaxed">{!! $item['description'] !!}</p>
                            @if(!empty($item['meta']))
                                <span class="text-[11px] text-cyber-sub block mt-1 font-sans tabular-nums">{!! $item['meta'] !!}</span>
                            @endif
                        </div>
                    </div>

                    @if(!empty($item['action_url']) && !empty($item['action_label']))
                        <div class="shrink-0 flex items-center justify-end">
                            <a href="{{ $item['action_url'] }}" 
                               class="px-3.5 py-2 rounded-xl font-semibold text-xs transition inline-flex items-center gap-1.5 shadow-sm {{ $severity === 'critical' ? 'bg-rose-600 hover:bg-rose-700 text-white' : 'bg-blue-600 hover:bg-blue-700 text-white' }}">
                                <span>{{ $item['action_label'] }}</span>
                                <i class="fa-solid fa-arrow-right text-[11px]"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="p-6 text-center text-cyber-muted text-xs">
            <i class="fa-solid fa-circle-check text-emerald-500 text-xl mb-2"></i>
            <p class="font-semibold text-cyber-main">Operational flow optimal</p>
            <p class="text-[11px] text-cyber-sub mt-0.5">No critical bottlenecks, delay exceptions, or material shortages detected.</p>
        </div>
    @endif
</div>
