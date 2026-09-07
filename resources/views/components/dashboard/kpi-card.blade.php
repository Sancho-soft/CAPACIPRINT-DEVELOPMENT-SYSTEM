@props([
    'title' => '',
    'value' => '0',
    'icon' => 'fa-solid fa-chart-simple',
    'accent' => 'cyan', // cyan, emerald, amber, rose, indigo, teal, purple
    'trend' => null,
    'trendType' => null,
    'subtitle' => null,
    'link' => null,
    'active' => false,
])

@php
    $titleColor = match($accent) {
        'emerald'          => 'text-emerald-600 dark:text-emerald-400',
        'amber'            => 'text-amber-600 dark:text-amber-400',
        'rose'             => 'text-rose-600 dark:text-rose-400',
        'indigo', 'purple' => 'text-indigo-600 dark:text-indigo-400',
        'teal'             => 'text-teal-600 dark:text-teal-400',
        'blue'             => 'text-blue-600 dark:text-blue-400',
        default            => 'text-cyan-600 dark:text-cyan-400',
    };

    $iconColor = 'text-slate-400 dark:text-slate-400';

    $activeClasses = match($accent) {
        'rose'    => 'ring-2 ring-rose-500/40 border-rose-500/60 bg-rose-500/[0.03]',
        'amber'   => 'ring-2 ring-amber-500/40 border-amber-500/60 bg-amber-500/[0.03]',
        'emerald' => 'ring-2 ring-emerald-500/40 border-emerald-500/60 bg-emerald-500/[0.03]',
        default   => 'ring-2 ring-cyan-500/40 border-cyan-500/60 bg-cyan-500/[0.03]',
    };
@endphp

<{{ $link ? 'a href='.$link : 'div' }} 
    class="bg-cyber-card rounded-2xl border p-4 sm:p-5 flex items-center justify-between gap-2 shadow-sm transition-all duration-200 group {{ $active ? $activeClasses : 'border-cyber hover:border-slate-300 dark:hover:border-slate-700' }} {{ $link ? 'cursor-pointer hover:-translate-y-0.5 hover:shadow-md' : '' }}"
>
    <div class="flex items-center gap-3 min-w-0">
        @if(!empty($icon))
            <i class="{{ $icon }} text-2xl {{ $iconColor }} shrink-0 group-hover:scale-110 transition-transform"></i>
        @endif
        <div class="min-w-0">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider leading-tight {{ $titleColor }} block font-sans">
                {{ $title }}
            </span>
        </div>
    </div>

    <div class="text-right shrink-0">
        @php
            $valStr = (string)$value;
            $isLong = strlen($valStr) > 7;
        @endphp
        <div class="{{ $isLong ? 'text-lg sm:text-xl xl:text-2xl' : 'text-2xl sm:text-3xl' }} font-black font-display text-cyber-main tracking-tight leading-none whitespace-nowrap">
            {{ $value }}
        </div>
    </div>
</{{ $link ? 'a' : 'div' }}>
