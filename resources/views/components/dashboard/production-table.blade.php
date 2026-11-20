@props([
    'jobs' => [],
    'title' => 'Live Production Queue & Orders',
    'subtitle' => 'Real-time job scheduling, priority flags, and press floor execution',
    'viewAllUrl' => null,
    'viewAllLabel' => 'View All Jobs',
    'emptyMessage' => 'No production jobs currently active in the queue.',
])

<div id="press-line-queue" class="bg-cyber-card border border-cyber rounded-2xl overflow-hidden flex flex-col scroll-mt-6 shadow-sm">
    <div class="px-5 sm:px-6 py-4 border-b border-cyber-sub flex items-center justify-between bg-cyber-sub">
        <div>
            <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">{{ $title }}</h3>
            @if(!empty($subtitle))
                <p class="text-xs text-cyber-muted mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if($viewAllUrl)
            <a href="{{ $viewAllUrl }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1 shrink-0">
                {{ $viewAllLabel }} <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        @endif
    </div>

    <div class="overflow-x-auto font-sans">
        <table class="w-full text-left text-xs tabular-nums">
            <thead class="text-cyber-muted font-sans font-bold uppercase tracking-wide border-b border-cyber text-xs bg-cyber-sub select-none">
                <tr>
                    <th class="px-4 sm:px-5 py-3">Job / Order</th>
                    <th class="px-4 sm:px-5 py-3">Customer</th>
                    <th class="px-4 sm:px-5 py-3">Service</th>
                    <th class="px-4 sm:px-5 py-3">Material &amp; Specs</th>
                    <th class="px-4 sm:px-5 py-3">Assigned Press</th>
                    <th class="px-4 sm:px-5 py-3">Priority</th>
                    <th class="px-4 sm:px-5 py-3">Status</th>
                    <th class="px-4 sm:px-5 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cyber-sub text-cyber-main">
                @forelse($jobs as $job)
                    @php
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
                        $priorityColor = match($priority) {
                            'urgent' => 'text-rose-600 dark:text-rose-400',
                            'rush'   => 'text-amber-600 dark:text-amber-400',
                            default  => 'text-cyber-muted',
                        };

                        $statusStr = strtolower($job->status ?? 'pending');
                        $statusColor = match($statusStr) {
                            'in_production', 'production' => 'text-blue-600 dark:text-blue-400',
                            'completed', 'claimed'        => 'text-emerald-600 dark:text-emerald-400',
                            'ready_for_pickup'            => 'text-teal-600 dark:text-teal-400',
                            'delayed'                     => 'text-rose-600 dark:text-rose-400',
                            'quality_checking'            => 'text-purple-600 dark:text-purple-400',
                            default                       => 'text-indigo-600 dark:text-indigo-400',
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
                    <tr class="hover:bg-black/[0.02] dark:hover:bg-white/[0.025] transition-colors">
                        {{-- Job Number --}}
                        <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                            <span class="font-sans tabular-nums font-semibold text-cyber-main text-xs block tracking-tight">{{ $jobNo }}</span>
                            <span class="text-[11px] font-sans text-cyber-muted block mt-0.5">{{ $job->created_at ? $job->created_at->diffForHumans() : 'Active' }}</span>
                        </td>

                        {{-- Customer --}}
                        <td class="px-4 sm:px-5 py-3.5 min-w-[130px]">
                            <span class="font-semibold text-cyber-main block truncate max-w-[180px] text-xs leading-snug">{{ $customerName }}</span>
                        </td>

                        {{-- Service --}}
                        <td class="px-4 sm:px-5 py-3.5 min-w-[130px]">
                            <span class="text-xs text-cyber-main font-medium block truncate max-w-[180px]">{{ $service }}</span>
                        </td>

                        {{-- Material Specs --}}
                        <td class="px-4 sm:px-5 py-3.5 min-w-[150px]">
                            <span class="text-cyber-main font-medium block truncate max-w-[180px] text-xs">{{ $material }}</span>
                            <span class="text-[11px] font-sans text-cyber-muted block mt-0.5 tabular-nums">
                                @if($quantity) {{ number_format($quantity) }} pcs @endif
                                @if($size) &middot; {{ $size }} @endif
                                @if($finishing) &middot; {{ $finishing }} @endif
                            </span>
                        </td>

                        {{-- Assigned Press & Branch --}}
                        <td class="px-4 sm:px-5 py-3.5 min-w-[150px]">
                            <span class="font-semibold text-cyber-main block leading-tight text-xs">{{ $branchName }}</span>
                            <span class="text-[11px] font-sans text-cyber-muted block mt-0.5">{{ $machineName }}</span>
                        </td>

                        {{-- Priority --}}
                        <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                            <span class="text-xs font-sans font-semibold capitalize {{ $priorityColor }}">
                                {{ $priority }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                            <span class="text-xs font-sans font-semibold {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                            @if($statusStr === 'delayed' && !empty($job->delay_reason))
                                <span class="text-[11px] text-rose-600 dark:text-rose-400 font-medium block mt-1 truncate max-w-[140px]" title="{{ $job->delay_reason }}">
                                    {{ $job->delay_reason }}
                                </span>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="px-4 sm:px-5 py-3.5 text-right whitespace-nowrap">
                            @if($detailRoute)
                                <a href="{{ $detailRoute }}" 
                                   aria-label="View Details for {{ $jobNo }}"
                                   class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1.5 rounded-lg hover:bg-blue-600/10 dark:hover:bg-blue-500/15 inline-flex items-center justify-center"
                                   title="View Details">
                                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                </a>
                            @else
                                <span class="text-cyber-sub text-xs">&mdash;</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center text-cyber-muted text-xs">
                            <i class="fa-solid fa-box-archive text-cyber-sub text-2xl mb-2 block"></i>
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($jobs, 'hasPages') && $jobs->hasPages())
        <div class="px-5 py-3 border-t border-cyber-sub bg-cyber-sub">
            {{ $jobs->withQueryString()->fragment('press-line-queue')->links() }}
        </div>
    @endif
</div>
