@extends('layouts.internal')
@section('title', 'Staff Notifications')
@section('page-title', 'Staff Notifications')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-navy-900 font-display">Notifications Alert</h2>
        @if($unreadCount > 0)
        <form method="POST" action="{{ route('staff.notifications.markAllRead') }}">
            @csrf
            <button type="submit" class="text-xs font-bold text-brand-600 hover:underline">Mark all as read</button>
        </form>
        @endif
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden divide-y divide-slate-100">
        @forelse($notifications as $n)
        <div class="p-4 flex items-start justify-between gap-4 {{ $n->is_read ? 'bg-white' : 'bg-brand-50/20' }}">
            <div class="flex items-start gap-3">
                <div class="h-9 w-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $n->type_icon }}"></i>
                </div>
                <div>
                    <h4 class="font-bold text-navy-900 text-xs">{{ $n->title }}</h4>
                    <p class="text-xs text-slate-600 mt-0.5">{{ $n->body }}</p>
                    <span class="text-[10px] text-slate-400 mt-1 block">{{ $n->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @if(!$n->is_read)
            <form method="POST" action="{{ route('staff.notifications.markRead', $n->id) }}">
                @csrf
                <button type="submit" class="text-[10px] font-bold text-slate-400 hover:text-slate-700">Dismiss</button>
            </form>
            @endif
        </div>
        @empty
        <div class="p-8 text-center text-slate-400 text-xs">No notifications.</div>
        @endforelse
    </div>
</div>
@endsection
