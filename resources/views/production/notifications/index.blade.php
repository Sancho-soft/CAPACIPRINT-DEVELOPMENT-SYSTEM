@extends('layouts.internal')
@section('title', 'Production Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 text-xs">
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden divide-y divide-cyber-sub">
        @forelse($notifications as $n)
        <div class="p-4 flex items-start justify-between gap-4 {{ $n->is_read ? 'bg-white' : 'bg-brand-50/20' }}">
            <div>
                <h4 class="font-bold text-cyber-main">{{ $n->title }}</h4>
                <p class="text-cyber-muted mt-0.5">{{ $n->body }}</p>
                <span class="text-[11px] text-cyber-muted block mt-1">{{ $n->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-cyber-muted">No notifications.</div>
        @endforelse
    </div>
</div>
@endsection
