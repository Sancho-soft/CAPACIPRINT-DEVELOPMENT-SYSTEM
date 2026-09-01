@extends('layouts.internal')
@section('title', 'Staff Notifications')
@section('page-title', 'Staff Notifications')

@section('content')
<div class="w-full space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-cyber-main font-display">Staff Notifications</h2>
            <p class="text-sm text-cyber-muted mt-1">Operational alerts, workflow triggers, and production updates.</p>
        </div>
        @if($unreadCount > 0)
        <form method="POST" action="{{ route('staff.notifications.markAllRead') }}">
            @csrf
            <button type="submit" class="text-xs text-cyan-500 hover:text-cyan-400 font-bold flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-cyan-500/10 border border-cyan-500/20 transition">
                <i class="fa-solid fa-check-double text-xs"></i> Mark all as read
            </button>
        </form>
        @endif
    </div>

    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-xl overflow-hidden divide-y divide-cyber">
        @forelse($notifications as $n)
        <div class="p-5 flex items-start justify-between gap-4 {{ $n->is_read ? 'bg-cyber-card' : 'bg-cyan-500/5' }} hover:bg-cyber-sub/40 transition">
            <div class="flex items-start gap-4 flex-1 min-w-0">
                <div class="h-11 w-11 rounded-xl flex items-center justify-center shrink-0 {{ !$n->is_read ? 'bg-cyan-500/15 text-cyan-400 border border-cyan-500/30 shadow-[0_0_10px_rgba(6,182,212,0.2)]' : 'bg-cyber-sub text-cyber-muted border border-cyber' }}">
                    <i class="fa-solid {{ $n->type_icon }} text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-cyber-main text-sm truncate">{{ $n->title }}</h4>
                    <p class="text-xs text-cyber-muted mt-1 leading-relaxed">{{ $n->body }}</p>
                    <span class="text-[10px] text-cyber-muted font-medium mt-1.5 block">{{ $n->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @if(!$n->is_read)
            <form method="POST" action="{{ route('staff.notifications.markRead', $n->id) }}">
                @csrf
                <button type="submit" class="text-[11px] font-bold text-cyan-500 hover:text-cyan-400 px-2.5 py-1 rounded-lg bg-cyan-500/10 border border-cyan-500/20 transition shrink-0">Mark read</button>
            </form>
            @endif
        </div>
        @empty
        <div class="p-16 text-center">
            <div class="h-16 w-16 mx-auto rounded-2xl bg-cyber-sub text-cyber-muted border border-cyber flex items-center justify-center text-2xl mb-4">
                <i class="fa-solid fa-bell-slash"></i>
            </div>
            <h4 class="font-bold text-cyber-main text-base">No notifications</h4>
            <p class="text-xs text-cyber-muted mt-1">You're all caught up with your alerts.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
