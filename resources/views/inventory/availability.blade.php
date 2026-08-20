@extends('layouts.internal')
@section('title', 'Material Availability')
@section('page-title', 'Branch Material Availability Matrix')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
        <h3 class="font-bold text-navy-900 text-sm">Material Availability by Branch</h3>
        <p class="text-xs text-slate-500">Cross-branch stock check to support production capacity decisions.</p>

        @foreach($inventory as $branchName => $items)
        <div class="border border-slate-100 rounded-xl p-4 space-y-2">
            <h4 class="font-bold text-brand-600 text-xs uppercase tracking-wider">{{ $branchName }}</h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                @foreach($items as $item)
                <div class="p-3 rounded-lg border border-slate-100 flex items-center justify-between">
                    <div>
                        <strong class="text-slate-800 block">{{ $item->material->name ?? '—' }}</strong>
                        <span class="text-[10px] text-slate-400">{{ number_format($item->quantity, 2) }} {{ $item->material->unit ?? 'units' }}</span>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $item->status_badge_class }}">
                        {{ $item->status_label }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
