@props([
    'title' => '',
    'value' => '0',
    'icon' => 'fa-solid fa-chart-simple',
    'accent' => 'cyan', // cyan, emerald, amber, rose, indigo, teal
    'trend' => null,
    'trendType' => 'neutral', // up, down, warning, neutral
    'subtitle' => null,
    'link' => null,
])

@php
    $accentStyles = match($accent) {
        'emerald' => [
            'border' => 'border-l-emerald-500',
            'badge'  => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
            'icon'   => 'text-emerald-600 dark:text-emerald-400 group-hover:text-emerald-500',
        ],
        'amber' => [
            'border' => 'border-l-amber-500',
            'badge'  => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
            'icon'   => 'text-amber-600 dark:text-amber-400 group-hover:text-amber-500',
        ],
        'rose' => [
            'border' => 'border-l-rose-500',
            'badge'  => 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20',
            'icon'   => 'text-rose-600 dark:text-rose-400 group-hover:text-rose-500',
        ],
        default => [ // cyan / brand primary
            'border' => 'border-l-sky-500',
            'badge'  => 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border-sky-500/20',
            'icon'   => 'text-sky-600 dark:text-sky-400 group-hover:text-sky-500',
        ],
    };

    $trendClass = match($trendType) {
        'up'      => 'text-emerald-600 dark:text-emerald-400 font-bold',
        'warning' => 'text-amber-600 dark:text-amber-400 font-bold',
        'danger'  => 'text-rose-600 dark:text-rose-400 font-bold',
        default   => 'text-slate-500 dark:text-slate-400 font-medium',
    };
@endphp

<{{ $link ? 'a href='.$link : 'div' }} 
    class="relative bg-cyber-card border border-cyber border-l-4 {{ $accentStyles['border'] }} rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md hover:border-slate-300 dark:hover:border-slate-700 transition-all duration-200 ease-out group hover:-translate-y-0.5 flex flex-col justify-between overflow-hidden {{ $link ? 'cursor-pointer' : '' }}"
>
    <div class="flex items-start justify-between gap-3 mb-2">
        <div class="min-w-0">
            <span class="text-[11px] font-black tracking-wider uppercase text-cyber-muted block truncate font-sans">
                {{ $title }}
            </span>
            @if($subtitle)
                <span class="text-[10px] text-cyber-sub block truncate mt-0.5">{{ $subtitle }}</span>
            @endif
        </div>
        <div class="h-10 w-10 rounded-xl bg-cyber-sub/80 border border-cyber flex items-center justify-center shrink-0 transition-transform duration-200 group-hover:scale-105">
            <i class="{{ $icon }} text-base {{ $accentStyles['icon'] }} transition-colors"></i>
        </div>
    </div>

    <div class="flex items-baseline justify-between gap-2 mt-1">
        <span class="text-2xl sm:text-3xl font-black font-display text-cyber-main tracking-tight">
            {{ $value }}
        </span>
        @if($trend)
            <span class="text-[11px] {{ $trendClass }} flex items-center gap-1 font-mono">
                {{ $trend }}
            </span>
        @endif
    </div>
</{{ $link ? 'a' : 'div' }}>
