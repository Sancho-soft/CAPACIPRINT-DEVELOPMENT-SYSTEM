@extends('layouts.internal')
@section('title', 'Production Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 text-xs">
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden divide-y divide-slate-100">
        @forelse($notifications as $n)
        <div class="p-4 flex items-start justify-between gap-4 {{ $n->is_read ? 'bg-white' : 'bg-brand-50/20' }}">
            <div>
                <h4 class="font-bold text-navy-900">{{ $n->title }}</h4>
                <p class="text-slate-600 mt-0.5">{{ $n->body }}</p>
                <span class="text-[10px] text-slate-400 block mt-1">{{ $n->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-slate-400">No notifications.</div>
        @endforelse
    </div>
</div>
@endsection
