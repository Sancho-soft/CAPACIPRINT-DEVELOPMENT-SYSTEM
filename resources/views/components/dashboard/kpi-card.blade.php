@props([
    'title' => '',
    'value' => '0',
    'icon' => null,
    'accent' => 'blue', // mapped strictly to: blue (#2F7BFF), emerald (#10B981), amber (#FFC531)
    'trend' => null,
    'trendType' => null,
    'subtitle' => null,
    'link' => null,
    'active' => false,
])

@php
    // Strict 3-status color limit: Brand Blue, Live Green, Warm Yellow
    $tone = match($accent) {
        'emerald', 'green' => 'emerald',
        'amber', 'orange', 'rose', 'red' => 'amber',
        default => 'blue',
    };

    $dotClass = match($tone) {
        'emerald' => 'bg-[#10B981]',
        'amber'   => 'bg-[#FFC531]',
        default   => 'bg-[#2F7BFF]',
    };

    $activeClasses = match($tone) {
        'emerald' => 'ring-1 ring-[#10B981]/50 border-[#10B981]/60',
        'amber'   => 'ring-1 ring-[#FFC531]/50 border-[#FFC531]/60',
        default   => 'ring-1 ring-[#2F7BFF]/50 border-[#2F7BFF]/60',
    };

    $trendClass = match($trendType) {
        'up'              => 'text-emerald-600 dark:text-[#10B981]',
        'down', 'danger', 'warning' => 'text-amber-600 dark:text-[#FFC531]',
        default           => 'text-cyber-muted',
    };

    // Convert cramped ALL-CAPS titles into readable sentence-title case while preserving acronyms
    $rawTitle = trim((string) $title);
    if ($rawTitle !== '' && mb_strtoupper($rawTitle) === $rawTitle) {
        $displayTitle = ucwords(mb_strtolower($rawTitle));
        $displayTitle = str_replace(['Qr ', 'Id ', 'Qc '], ['QR ', 'ID ', 'QC '], $displayTitle);
    } else {
        $displayTitle = $rawTitle;
    }

    $valStr = (string) $value;
    $isLong = strlen($valStr) > 9;
@endphp

<{{ $link ? 'a href='.$link : 'div' }} 
    class="bg-cyber-card rounded-xl border p-4 sm:p-5 flex flex-col justify-between gap-2.5 transition-colors duration-150 group {{ $active ? $activeClasses : 'border-cyber ' . ($link ? 'hover:border-[#2F7BFF]/50 hover:bg-cyber-sub/40' : '') }} {{ $link ? 'cursor-pointer' : '' }}"
>
    <div class="flex items-center justify-between gap-2">
        <span class="text-[13px] font-sans font-medium text-cyber-muted truncate group-hover:text-cyber-main transition-colors">
            {{ $displayTitle }}
        </span>
        <span class="w-2 h-2 rounded-full {{ $dotClass }} shrink-0" aria-hidden="true"></span>
    </div>

    <div class="flex items-baseline justify-between gap-3">
        <div class="{{ $isLong ? 'text-xl sm:text-2xl' : 'text-2xl sm:text-[1.75rem]' }} font-bold font-display text-cyber-main tracking-tight leading-none whitespace-nowrap tabular-nums">
            {{ $value }}
        </div>

        @if(!empty($trend))
            <span class="text-xs font-mono font-medium {{ $trendClass }} truncate">
                {{ $trend }}
            </span>
        @elseif($link)
            <i class="fa-solid fa-arrow-right text-[11px] text-cyber-sub group-hover:text-[#2F7BFF] transition-colors shrink-0" aria-hidden="true"></i>
        @endif
    </div>

    @if(!empty($subtitle))
        <p class="text-xs font-sans text-cyber-muted truncate">{{ $subtitle }}</p>
    @endif
</{{ $link ? 'a' : 'div' }}>
