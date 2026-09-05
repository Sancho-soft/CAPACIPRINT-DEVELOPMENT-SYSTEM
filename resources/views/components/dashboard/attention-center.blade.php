@props([
    'items' => [], // array of attention items
    'title' => 'Operational Attention Center',
    'subtitle' => 'High-priority items requiring immediate supervision or action',
])

@php
    $hasItems = count($items) > 0;
@endphp

<div class="bg-cyber-card border {{ $hasItems ? 'border-amber-500/30' : 'border-cyber' }} rounded-3xl shadow-xl overflow-hidden">
    <div class="px-5 sm:px-6 py-4 {{ $hasItems ? 'bg-amber-500/10 border-b border-amber-500/20' : 'bg-cyber-sub border-b border-cyber' }} flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="h-7 w-7 rounded-xl {{ $hasItems ? 'bg-amber-500/20 text-amber-400' : 'bg-cyber-card text-cyber-muted' }} flex items-center justify-center text-xs">
                <i class="fa-solid fa-triangle-exclamation {{ $hasItems ? 'animate-bounce' : '' }}"></i>
            </div>
            <div>
                <h3 class="font-black text-xs sm:text-sm text-cyber-main font-display">{{ $title }}</h3>
                <p class="text-[10px] sm:text-[11px] text-cyber-muted">{{ $subtitle }}</p>
            </div>
        </div>
        <div>
            @if($hasItems)
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black font-mono uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30">
                    {{ count($items) }} Action{{ count($items) > 1 ? 's' : '' }} Pending
                </span>
            @else
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-mono uppercase bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                    <i class="fa-solid fa-check mr-1"></i> All Clear
                </span>
            @endif
        </div>
    </div>

    @if($hasItems)
        <div class="divide-y divide-cyber/60 text-xs">
            @foreach($items as $item)
                @php
                    $severity = $item['severity'] ?? 'warning'; // critical, warning, info
                    $badgeStyle = match($severity) {
                        'critical' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                        'info'     => 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30',
                        default    => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                    };
                    $iconStyle = match($severity) {
                        'critical' => 'text-rose-400',
                        'info'     => 'text-cyan-400',
                        default    => 'text-amber-400',
                    };
                @endphp
                <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-cyber-hover/50 transition">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="mt-0.5 shrink-0 {{ $iconStyle }}">
                            <i class="{{ $item['icon'] ?? 'fa-solid fa-circle-exclamation' }}"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-cyber-main text-xs sm:text-sm">{{ $item['title'] }}</span>
                                @if(!empty($item['badge']))
                                    <span class="text-[9px] font-black font-mono uppercase px-2 py-0.5 rounded-md border {{ $badgeStyle }}">
                                        {{ $item['badge'] }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-cyber-muted text-xs mt-0.5 leading-relaxed">{!! $item['description'] !!}</p>
                            @if(!empty($item['meta']))
                                <span class="text-[10px] text-cyber-sub block mt-1 font-mono">{!! $item['meta'] !!}</span>
                            @endif
                        </div>
                    </div>

                    @if(!empty($item['action_url']) && !empty($item['action_label']))
                        <div class="shrink-0 flex items-center justify-end">
                            <a href="{{ $item['action_url'] }}" 
                               class="px-3.5 py-1.5 rounded-xl font-bold text-xs transition inline-flex items-center gap-1.5 shadow-sm {{ $severity === 'critical' ? 'bg-rose-600 hover:bg-rose-500 text-white' : 'bg-sky-600 hover:bg-sky-500 text-white' }}">
                                <span>{{ $item['action_label'] }}</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="p-6 text-center text-cyber-muted text-xs">
            <i class="fa-solid fa-circle-check text-emerald-400 text-2xl mb-2"></i>
            <p class="font-medium text-cyber-main">Operational flow optimal</p>
            <p class="text-[11px] text-cyber-sub mt-0.5">No critical bottlenecks, delay exceptions, or material shortages detected.</p>
        </div>
    @endif
</div>
