@props([
    'title' => 'No records found',
    'description' => 'There are currently no items to display in this section.',
    'icon' => 'fa-solid fa-folder-open',
    'actionUrl' => null,
    'actionLabel' => null,
])

<div class="p-8 sm:p-12 text-center flex flex-col items-center justify-center space-y-3 bg-cyber-card border border-cyber rounded-xl">
    <div class="h-12 w-12 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted flex items-center justify-center text-lg">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="max-w-md">
        <h4 class="font-bold text-cyber-main text-sm sm:text-base font-display">{{ $title }}</h4>
        <p class="text-xs text-cyber-muted mt-1 leading-relaxed">{{ $description }}</p>
    </div>
    @if($actionUrl && $actionLabel)
        <div class="pt-2">
            <a href="{{ $actionUrl }}" class="px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs transition inline-flex items-center gap-2">
                <span>{{ $actionLabel }}</span>
                <i class="fa-solid fa-plus text-xs"></i>
            </a>
        </div>
    @endif
</div>
