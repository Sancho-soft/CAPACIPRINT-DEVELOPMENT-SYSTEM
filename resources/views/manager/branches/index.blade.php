@extends('layouts.internal')
@section('title', 'Branches')
@section('page-title', 'Branch Network')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 max-w-7xl">
    @foreach($branches as $b)
    <div class="bg-cyber-card p-6 rounded-2xl border border-cyber shadow-sm space-y-4">
        <div>
            <h3 class="font-bold text-cyber-main text-lg font-display">{{ $b->name }}</h3>
            <p class="text-xs text-cyber-muted">{{ $b->address }}</p>
        </div>
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div class="bg-cyber-sub p-3 rounded-xl"><span class="text-cyber-muted block text-[11px]">Machines</span><strong class="text-cyber-main text-sm">{{ $b->machines_count }}</strong></div>
            <div class="bg-cyber-sub p-3 rounded-xl"><span class="text-cyber-muted block text-[11px]">Employees</span><strong class="text-cyber-main text-sm">{{ $b->employees_count }}</strong></div>
        </div>
        <a href="{{ route('manager.branches.show', $b) }}" class="block text-center bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold py-2 rounded-xl text-xs transition">View Branch Profile &rarr;</a>
    </div>
    @endforeach
</div>
@endsection
