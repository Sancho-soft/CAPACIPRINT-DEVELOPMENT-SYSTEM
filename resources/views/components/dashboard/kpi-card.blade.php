@props([
    'title' => '',
    'value' => '0',
    'icon' => 'fa-solid fa-chart-simple',
    'accent' => 'cyan', // cyan, emerald, amber, rose, indigo, teal, purple, blue
    'trend' => null,
    'trendType' => null,
    'subtitle' => null,
    'link' => null,
    'active' => false,
])

@php
    $iconBg = match($accent) {
        'emerald'          => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
        'amber'            => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
        'rose'             => 'bg-rose-500/10 text-rose-600 dark:text-rose-400',
        'indigo', 'purple' => 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400',
        'teal'             => 'bg-teal-500/10 text-teal-600 dark:text-teal-400',
        default            => 'bg-blue-500/10 text-blue-600 dark:text-blue-400',
    };

    $activeClasses = match($accent) {
        'rose'    => 'ring-2 ring-rose-500/30 border-rose-500/60',
        'amber'   => 'ring-2 ring-amber-500/30 border-amber-500/60',
        'emerald' => 'ring-2 ring-emerald-500/30 border-emerald-500/60',
        default   => 'ring-2 ring-blue-500/30 border-blue-500/60',
    };
@endphp

<{{ $link ? 'a href='.$link : 'div' }} 
    class="bg-cyber-card rounded-2xl border p-5 flex items-center justify-between gap-4 shadow-sm transition-colors duration-150 group {{ $active ? $activeClasses : 'border-cyber ' . ($link ? 'hover:border-blue-500/40 hover:bg-cyber-sub/30' : '') }} {{ $link ? 'cursor-pointer' : '' }}"
>
    <div class="min-w-0 space-y-1.5">
        <div class="flex items-center gap-2.5">
            @if(!empty($icon))
                <span class="h-10 w-10 rounded-xl {{ $iconBg }} flex items-center justify-center shrink-0">
                    <i class="{{ $icon }} text-sm"></i>
                </span>
            @endif
            <div class="min-w-0">
                <span class="text-xs font-sans font-bold uppercase tracking-wider leading-tight text-cyber-muted block truncate">
                    {{ $title }}
                </span>
                @if(!empty($subtitle))
                    <p class="text-[11px] font-sans text-cyber-sub truncate mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @if(!empty($trend))
            <span class="inline-flex items-center gap-1 text-[11px] font-sans font-semibold px-2 py-0.5 rounded-full {{ $trendType === 'down' || $trendType === 'danger' ? 'bg-rose-500/10 text-rose-700 dark:text-rose-400' : ($trendType === 'up' ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' : ($trendType === 'warning' ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400' : 'bg-cyber-sub text-cyber-muted')) }}">
                {{ $trend }}
            </span>
        @endif
    </div>

    <div class="text-right shrink-0">
        @php
            $valStr = (string)$value;
            $isLong = strlen($valStr) > 7;
        @endphp
        <div class="{{ $isLong ? 'text-lg sm:text-xl xl:text-2xl' : 'text-2xl sm:text-3xl' }} font-extrabold font-display text-cyber-main tracking-tight leading-none whitespace-nowrap tabular-nums">
            {{ $value }}
        </div>
    </div>
</{{ $link ? 'a' : 'div' }}>
