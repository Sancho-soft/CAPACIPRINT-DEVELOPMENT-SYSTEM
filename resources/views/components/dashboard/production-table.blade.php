@props([
    'jobs' => [],
    'title' => 'Live Production Queue & Orders',
    'subtitle' => 'Real-time job scheduling, priority flags, and press floor execution',
    'viewAllUrl' => null,
    'viewAllLabel' => 'View All Jobs',
    'emptyMessage' => 'No production jobs currently active in the queue.',
])

<div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
    <div class="px-5 sm:px-6 py-4 border-b border-cyber/80 flex items-center justify-between bg-cyber-sub/70">
        <div>
            <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">{{ $title }}</h3>
            <p class="text-[11px] text-cyber-muted mt-0.5">{{ $subtitle }}</p>
        </div>
        @if($viewAllUrl)
            <a href="{{ $viewAllUrl }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1 shrink-0">
                {{ $viewAllLabel }} <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-cyber-base/80 text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                <tr>
                    <th class="px-4 sm:px-5 py-3.5">Job / Order</th>
                    <th class="px-4 sm:px-5 py-3.5">Customer & Service</th>
                    <th class="px-4 sm:px-5 py-3.5">Material & Specs</th>
                    <th class="px-4 sm:px-5 py-3.5">Assigned Press</th>
                    <th class="px-4 sm:px-5 py-3.5">Priority</th>
                    <th class="px-4 sm:px-5 py-3.5">Status</th>
                    <th class="px-4 sm:px-5 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cyber/60 text-cyber-main">
                @forelse($jobs as $job)
                    @php
                        // Normalize whether $job is a ProductionJob or Order
                        $isOrder = !isset($job->job_number) && isset($job->order_number);
                        $jobNo = $isOrder ? ('ORD-' . $job->order_number) : ($job->job_number ?? 'JOB-#' . $job->id);
                        $customerName = $job->order->user->name ?? ($job->user->name ?? 'Direct Customer');
                        $service = $job->order->printRequest->service ?? ($job->printRequest->service ?? 'Print Order');
                        $quantity = $job->order->printRequest->quantity ?? ($job->printRequest->quantity ?? null);
                        $size = $job->order->printRequest->size ?? ($job->printRequest->size ?? null);
                        $material = $job->order->printRequest->material ?? ($job->printRequest->material ?? 'Standard Stock');
                        $finishing = $job->order->printRequest->finishing ?? ($job->printRequest->finishing ?? null);
                        $branchName = $job->branch->name ?? ($job->assigned_branch ?? 'Branch Hub');
                        $machineName = $job->machine->name ?? 'Press Unit';
                        
                        $priority = strtolower($job->priority ?? 'normal');
                        $priorityBadge = match($priority) {
                            'urgent' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                            'rush'   => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                            default  => 'bg-slate-500/15 text-slate-400 border-slate-500/30',
                        };

                        $statusStr = strtolower($job->status ?? 'pending');
                        $statusBadge = match($statusStr) {
                            'in_production', 'production' => 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30',
                            'completed', 'claimed'        => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                            'ready_for_pickup'            => 'bg-teal-500/15 text-teal-400 border-teal-500/30',
                            'delayed'                     => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                            'quality_checking'            => 'bg-purple-500/15 text-purple-400 border-purple-500/30',
                            default                       => 'bg-indigo-500/15 text-indigo-400 border-indigo-500/30',
                        };

                        $statusLabel = match($statusStr) {
                            'in_production', 'production' => 'On Press',
                            'quality_checking'            => 'Quality Check',
                            'ready_for_pickup'            => 'Ready Pickup',
                            default                       => ucfirst(str_replace('_', ' ', $statusStr)),
                        };

                        $detailRoute = null;
                        if (!$isOrder && Route::has('manager.production-planning.show')) {
                            $detailRoute = route('manager.production-planning.show', $job->id);
                        } elseif ($isOrder && Route::has('staff.orders.show')) {
                            $detailRoute = route('staff.orders.show', $job->id);
                        } elseif (Route::has('production.jobs.show')) {
                            $detailRoute = route('production.jobs.show', $job->id);
                        }
                    @endphp
                    <tr class="hover:bg-cyber-hover/50 transition">
                        {{-- Job Number --}}
                        <td class="px-4 sm:px-5 py-3.5">
                            <span class="font-mono font-bold text-cyber-main text-xs block">{{ $jobNo }}</span>
                            <span class="text-[10px] text-cyber-sub block mt-0.5">{{ $job->created_at ? $job->created_at->diffForHumans() : 'Active' }}</span>
                        </td>

                        {{-- Customer & Service --}}
                        <td class="px-4 sm:px-5 py-3.5 min-w-[150px]">
                            <span class="font-bold text-cyber-main block truncate max-w-[180px]">{{ $customerName }}</span>
                            <span class="text-[11px] text-cyan-400 font-medium block truncate max-w-[180px]">{{ $service }}</span>
                        </td>

                        {{-- Material Specs --}}
                        <td class="px-4 sm:px-5 py-3.5 min-w-[150px]">
                            <span class="text-cyber-muted block truncate max-w-[180px]">{{ $material }}</span>
                            <span class="text-[10px] text-cyber-sub block font-mono">
                                @if($quantity) {{ number_format($quantity) }} pcs @endif
                                @if($size) &middot; {{ $size }} @endif
                                @if($finishing) &middot; {{ $finishing }} @endif
                            </span>
                        </td>

                        {{-- Assigned Press & Branch --}}
                        <td class="px-4 sm:px-5 py-3.5 min-w-[140px]">
                            <span class="font-medium text-cyber-main block truncate max-w-[160px]">{{ $branchName }}</span>
                            <span class="text-[10px] text-cyber-muted block truncate max-w-[160px] font-mono">{{ $machineName }}</span>
                        </td>

                        {{-- Priority --}}
                        <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider border {{ $priorityBadge }} font-mono">
                                @if($priority === 'urgent')
                                    <i class="fa-solid fa-bolt mr-0.5"></i>
                                @endif
                                {{ $priority }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider border {{ $statusBadge }} font-mono">
                                {{ $statusLabel }}
                            </span>
                            @if($statusStr === 'delayed' && !empty($job->delay_reason))
                                <span class="text-[9px] text-rose-400 block mt-1 truncate max-w-[120px]" title="{{ $job->delay_reason }}">
                                    {{ $job->delay_reason }}
                                </span>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="px-4 sm:px-5 py-3.5 text-right whitespace-nowrap">
                            @if($detailRoute)
                                <a href="{{ $detailRoute }}" class="px-2.5 py-1 rounded-lg bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition inline-flex items-center gap-1 shadow-sm">
                                    <span>Details</span>
                                    <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                </a>
                            @else
                                <span class="text-cyber-sub text-xs">&mdash;</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-cyber-muted text-xs">
                            <i class="fa-solid fa-box-archive text-cyber-sub text-3xl mb-2 block"></i>
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
