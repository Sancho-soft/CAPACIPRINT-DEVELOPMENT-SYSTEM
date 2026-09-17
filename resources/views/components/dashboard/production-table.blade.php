@props([
    'jobs' => [],
    'title' => 'Live Production Queue & Orders',
    'subtitle' => 'Real-time job scheduling, priority flags, and press floor execution',
    'viewAllUrl' => null,
    'viewAllLabel' => 'View All Jobs',
    'emptyMessage' => 'No production jobs currently active in the queue.',
])

<div id="press-line-queue" class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col scroll-mt-6">
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

    <div class="overflow-x-auto font-sans">
        <table class="w-full text-left text-xs">
            <thead class="text-slate-600 dark:text-slate-300 font-bold uppercase tracking-wider border-b border-cyber/60 text-[10px]" style="background: rgba(13,21,32,0.7);">
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
                            'urgent' => 'bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 shadow-2xs',
                            'rush'   => 'bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 shadow-2xs',
                            default  => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 shadow-2xs',
                        };

                        $statusStr = strtolower($job->status ?? 'pending');
                        $statusBadge = match($statusStr) {
                            'in_production', 'production' => 'bg-cyan-100 dark:bg-cyan-500/20 text-cyan-800 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-500/30 shadow-2xs',
                            'completed', 'claimed'        => 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30 shadow-2xs',
                            'ready_for_pickup'            => 'bg-teal-100 dark:bg-teal-500/20 text-teal-800 dark:text-teal-300 border border-teal-200 dark:border-teal-500/30 shadow-2xs',
                            'delayed'                     => 'bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 shadow-2xs',
                            'quality_checking'            => 'bg-purple-100 dark:bg-purple-500/20 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-500/30 shadow-2xs',
                            default                       => 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-800 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30 shadow-2xs',
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
                    <tr class="transition-colors" style="" onmouseenter="this.style.background='rgba(30,41,59,0.4)'" onmouseleave="this.style.background=''">
                        {{-- Job Number --}}
                        <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                            <span class="font-mono font-bold text-black dark:text-white text-xs block tracking-tight">{{ $jobNo }}</span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 block mt-0.5 font-medium">{{ $job->created_at ? $job->created_at->diffForHumans() : 'Active' }}</span>
                        </td>

                        {{-- Customer & Service --}}
                        <td class="px-4 sm:px-5 py-3.5 min-w-[150px]">
                            <span class="font-bold text-black dark:text-white block truncate max-w-[190px] text-xs leading-snug">{{ $customerName }}</span>
                            <span class="text-[11px] text-black dark:text-slate-200 font-semibold block truncate max-w-[190px] mt-0.5">{{ $service }}</span>
                        </td>

                        {{-- Material Specs --}}
                        <td class="px-4 sm:px-5 py-3.5 min-w-[150px]">
                            <span class="text-slate-700 dark:text-slate-300 font-medium block truncate max-w-[180px] text-xs">{{ $material }}</span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 block font-mono mt-0.5">
                                @if($quantity) {{ number_format($quantity) }} pcs @endif
                                @if($size) &middot; {{ $size }} @endif
                                @if($finishing) &middot; {{ $finishing }} @endif
                            </span>
                        </td>

                        {{-- Assigned Press & Branch --}}
                        <td class="px-4 sm:px-5 py-3.5 min-w-[150px]">
                            <span class="font-semibold text-black dark:text-white block leading-tight text-xs">{{ $branchName }}</span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 block font-mono mt-0.5">{{ $machineName }}</span>
                        </td>

                        {{-- Priority --}}
                        <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                            @if(in_array($priority, ['normal', 'standard', 'low']))
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 font-mono">
                                    {{ $priority }}
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $priorityBadge }} font-mono">
                                    {{ $priority }}
                                </span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusBadge }} font-mono">
                                {{ $statusLabel }}
                            </span>
                            @if($statusStr === 'delayed' && !empty($job->delay_reason))
                                <span class="text-[9px] text-rose-500 font-medium block mt-1 truncate max-w-[140px]" title="{{ $job->delay_reason }}">
                                    {{ $job->delay_reason }}
                                </span>
                            @endif
                        </td>

                        {{-- Action (Signature Cyan Eye Button) --}}
                        <td class="px-4 sm:px-5 py-3.5 text-right whitespace-nowrap">
                            @if($detailRoute)
                                <a href="{{ $detailRoute }}" 
                                   class="group h-8 w-8 rounded-xl bg-cyan-500/10 hover:bg-cyan-500 text-cyan-600 dark:text-cyan-400 hover:text-white dark:hover:text-slate-950 border border-cyan-500/25 hover:border-cyan-500 inline-flex items-center justify-center transition-all duration-200 shadow-xs"
                                   title="View Details">
                                    <i class="fa-solid fa-eye text-xs text-cyan-600 dark:text-cyan-400 group-hover:text-white dark:group-hover:text-slate-950 transition-colors"></i>
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

    @if(method_exists($jobs, 'hasPages') && $jobs->hasPages())
        <div class="px-5 py-3 border-t border-cyber/60 bg-cyber-sub/40">
            {{ $jobs->appends(request()->except('jobs_page'))->fragment('press-line-queue')->links() }}
        </div>
    @endif
</div>
