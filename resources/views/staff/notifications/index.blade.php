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
            <button type="submit" class="text-xs text-blue-500 hover:text-blue-400 font-bold flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-500/10 border border-blue-500/20 transition">
                <i class="fa-solid fa-check-double text-xs"></i> Mark all as read
            </button>
        </form>
        @endif
    </div>

    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden divide-y divide-cyber">
        @forelse($notifications as $n)
        <div class="p-5 flex items-start justify-between gap-4 {{ $n->is_read ? 'bg-cyber-card' : 'bg-blue-500/5' }} hover:bg-cyber-sub/40 transition">
            <div class="flex items-start gap-4 flex-1 min-w-0">
                <div class="h-11 w-11 rounded-xl flex items-center justify-center shrink-0 {{ !$n->is_read ? 'bg-brand-600/10 text-brand-600 dark:text-blue-400 border border-brand-600/20 shadow-sm' : 'bg-cyber-sub text-cyber-muted border border-cyber' }}">
                    <i class="fa-solid {{ $n->type_icon }} text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-cyber-main text-sm truncate">{{ $n->title }}</h4>
                    <p class="text-xs text-cyber-muted mt-1 leading-relaxed">{{ $n->body }}</p>
                    <span class="text-[11px] text-cyber-muted font-medium mt-1.5 block">{{ $n->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @if(!$n->is_read)
            <form method="POST" action="{{ route('staff.notifications.markRead', $n->id) }}">
                @csrf
                <button type="submit" class="text-[11px] font-bold text-blue-500 hover:text-blue-400 px-2.5 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20 transition shrink-0">Mark read</button>
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
