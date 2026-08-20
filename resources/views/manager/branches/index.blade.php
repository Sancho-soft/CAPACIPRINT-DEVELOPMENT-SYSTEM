@extends('layouts.internal')
@section('title', 'Branches')
@section('page-title', 'Branch Network')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 max-w-7xl">
    @foreach($branches as $b)
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
        <div>
            <h3 class="font-bold text-navy-900 text-lg font-display">{{ $b->name }}</h3>
            <p class="text-xs text-slate-500">{{ $b->address }}</p>
        </div>
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div class="bg-slate-50 p-3 rounded-xl"><span class="text-slate-400 block text-[10px]">Machines</span><strong class="text-navy-900 text-sm">{{ $b->machines_count }}</strong></div>
            <div class="bg-slate-50 p-3 rounded-xl"><span class="text-slate-400 block text-[10px]">Employees</span><strong class="text-navy-900 text-sm">{{ $b->employees_count }}</strong></div>
        </div>
        <a href="{{ route('manager.branches.show', $b) }}" class="block text-center bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-2 rounded-xl text-xs">View Branch Profile &rarr;</a>
    </div>
    @endforeach
</div>
@endsection
