@props([
    'items' => [], // array of attention items
    'title' => 'Operational Attention Center',
    'subtitle' => 'High-priority items requiring immediate supervision or action',
])

@php
    $hasItems = count($items) > 0;
@endphp

<div class="bg-cyber-card border {{ $hasItems ? 'border-[#FFC531]/40' : 'border-cyber' }} rounded-xl overflow-hidden">
    <div class="px-5 sm:px-6 py-4 bg-cyber-sub border-b border-cyber flex items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full {{ $hasItems ? 'bg-[#FFC531]' : 'bg-[#10B981]' }}"></span>
                <h3 class="font-bold text-sm text-cyber-main font-display">{{ $title }}</h3>
            </div>
            @if(!empty($subtitle))
                <p class="text-xs text-cyber-muted mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        <div>
            @if($hasItems)
                <span class="font-mono text-xs font-semibold text-amber-700 dark:text-[#FFC531] tabular-nums">
                    {{ count($items) }} Pending
                </span>
            @else
                <span class="font-mono text-xs font-semibold text-emerald-700 dark:text-[#10B981]">
                    All Clear
                </span>
            @endif
        </div>
    </div>

    @if($hasItems)
        <div class="divide-y divide-cyber-sub text-xs">
            @foreach($items as $item)
                @php
                    $severity = $item['severity'] ?? 'warning'; // critical, warning, info -> strict 3-status colors
                    $iconStyle = $severity === 'info'
                        ? 'text-[#2F7BFF] dark:text-[#60A5FA]'
                        : 'text-amber-600 dark:text-[#FFC531]';
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
                                    <span class="font-mono text-xs font-semibold {{ $iconStyle }}">
                                        &middot; {{ $item['badge'] }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-cyber-muted text-xs mt-0.5 leading-relaxed">{!! $item['description'] !!}</p>
                            @if(!empty($item['meta']))
                                <span class="text-xs text-cyber-sub block mt-1 font-mono tabular-nums">{!! $item['meta'] !!}</span>
                            @endif
                        </div>
                    </div>

                    @if(!empty($item['action_url']) && !empty($item['action_label']))
                        <div class="shrink-0 flex items-center justify-end">
                            <a href="{{ $item['action_url'] }}" 
                               class="px-3.5 py-2 rounded-lg font-semibold text-xs transition inline-flex items-center gap-1.5 bg-[#2F7BFF] hover:bg-[#1F68EB] text-white">
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
            <i class="fa-solid fa-circle-check text-[#10B981] text-xl mb-2"></i>
            <p class="font-semibold text-cyber-main">Operational flow optimal</p>
            <p class="text-xs text-cyber-muted mt-0.5">No bottlenecks, delay exceptions, or material shortages detected.</p>
        </div>
    @endif
</div>
