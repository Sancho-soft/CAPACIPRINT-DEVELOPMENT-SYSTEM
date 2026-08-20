@extends('layouts.customer')
@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="space-y-6 max-w-3xl">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-900 font-display">Notifications</h2>
            <p class="text-sm text-slate-500 mt-1">Stay updated with your order progress and important alerts.</p>
        </div>
        @if($unreadCount > 0)
        <form method="POST" action="{{ route('customer.notifications.markAllRead') }}">
            @csrf
            <button type="submit" class="text-xs text-brand-500 hover:text-brand-700 font-semibold flex items-center gap-1">
                <i class="fa-solid fa-check-double"></i> Mark all as read
            </button>
        </form>
        @endif
    </div>

    <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden divide-y divide-slate-100">
        @forelse($notifications as $notif)
        <div class="{{ !$notif->is_read ? 'bg-brand-50/20' : 'bg-white' }} p-4 flex items-start gap-4 hover:bg-slate-50/50 transition">
            <div class="h-10 w-10 rounded-lg flex items-center justify-center shrink-0 {{ !$notif->is_read ? 'bg-brand-100 text-brand-600' : 'bg-slate-100 text-slate-500' }}">
                <i class="fa-solid {{ $notif->type_icon }}"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between gap-4">
                    <h4 class="text-sm font-bold text-navy-900">{{ $notif->title }}</h4>
                    <span class="text-[10px] text-slate-400 shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-xs text-slate-600 mt-1">{{ $notif->body }}</p>
            </div>
            @if(!$notif->is_read)
            <form method="POST" action="{{ route('customer.notifications.markRead', $notif->id) }}">
                @csrf
                <button type="submit" class="text-[10px] text-brand-500 hover:text-brand-700 font-semibold shrink-0 mt-1">Mark read</button>
            </form>
            @endif
        </div>
        @empty
        <div class="p-12 text-center">
            <i class="fa-solid fa-bell-slash text-slate-300 text-4xl mb-3"></i>
            <h4 class="font-bold text-navy-900">No notifications</h4>
            <p class="text-sm text-slate-500 mt-1">You're all caught up!</p>
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div>{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
