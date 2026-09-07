@props([
    'jobs' => [],
    'title' => 'Live Production Queue & Orders',
    'subtitle' => 'Real-time job scheduling, priority flags, and press floor execution',
    'viewAllUrl' => null,
    'viewAllLabel' => 'View All Jobs',
    'emptyMessage' => 'No production jobs currently active in the queue.',
])

<div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
    <div class="px-5 sm:px-6 py-4 border-b border-cyber/60 flex items-center justify-between">
        <div>
            <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">{{ $title }}</h3>
            @if(!empty($subtitle))
                <p class="text-[11px] text-cyber-muted mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if($viewAllUrl)
            <a href="{{ $viewAllUrl }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 dark:text-cyan-400 dark:hover:text-cyan-300 flex items-center gap-1 shrink-0">
                {{ $viewAllLabel }} <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber/60 text-[10px]">
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
                        $jobNo = $isOrder ? (str_starts_with((string)$job->order_number, 'ORD-') ? $job->order_number : ('ORD-' . $job->order_number)) : ($job->job_number ?? 'JOB-#' . $job->id);
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
                            'urgent' => 'bg-rose-500/15 text-rose-700 dark:text-rose-400',
                            'rush'   => 'bg-amber-500/15 text-amber-800 dark:text-amber-400',
                            default  => 'bg-slate-500/15 text-slate-700 dark:text-slate-400',
                        };

                        $statusStr = strtolower($job->status ?? 'pending');
                        $statusBadge = match($statusStr) {
                            'in_production', 'production' => 'bg-cyan-500/15 text-cyan-700 dark:text-cyan-400',
                            'completed', 'claimed'        => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400',
                            'ready_for_pickup'            => 'bg-teal-500/15 text-teal-700 dark:text-teal-400',
                            'delayed'                     => 'bg-rose-500/15 text-rose-700 dark:text-rose-400',
                            'quality_checking'            => 'bg-purple-500/15 text-purple-700 dark:text-purple-400',
                            default                       => 'bg-indigo-500/15 text-indigo-700 dark:text-indigo-400',
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
                            <span class="text-[11px] text-sky-600 dark:text-cyan-400 font-semibold block truncate max-w-[180px]">{{ $service }}</span>
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
                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $priorityBadge }} font-mono">
                                @if($priority === 'urgent')
                                    <i class="fa-solid fa-bolt mr-0.5"></i>
                                @endif
                                {{ $priority }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $statusBadge }} font-mono">
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
                                <a href="{{ $detailRoute }}" 
                                   class="h-8 w-8 rounded-xl bg-white hover:bg-slate-50 dark:bg-slate-800 dark:hover:bg-slate-700/80 border border-slate-300 dark:border-slate-700 hover:border-sky-400 dark:hover:border-cyan-500/40 inline-flex items-center justify-center transition-all shadow-xs group"
                                   title="View Details">
                                    <i class="fa-solid fa-eye text-sm text-slate-800 dark:text-slate-100 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors"></i>
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
