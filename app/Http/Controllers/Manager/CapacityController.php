<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\PrintRequest;
use App\Models\Branch;
use App\Models\CapacityEvaluation;
use App\Models\BranchRecommendation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CapacityController extends Controller
{
    public function index()
    {
        // Show all print requests awaiting capacity evaluation
        $pendingRequests = PrintRequest::with('user')
            ->whereIn('status', ['quotation', 'payment', 'branch_recommended'])
            ->whereDoesntHave('branchRecommendation', fn($q) => $q->where('status', 'confirmed'))
            ->latest()
            ->paginate(10);

        $branches = Branch::where('status', 'active')
            ->withCount([
                'productionJobs as active_count' => fn($q) => $q->whereNotIn('status', ['completed']),
                'machines as available_machines' => fn($q) => $q->where('status', 'available'),
            ])->get();

        return view('manager.capacity.index', compact('pendingRequests', 'branches'));
    }

    public function evaluate(PrintRequest $printRequest)
    {
        $printRequest->load('user');
        $branches = Branch::where('status', 'active')
            ->with(['machines', 'employees', 'inventory.material'])
            ->get();

        // Run evaluation algorithm for each branch
        $evaluations = $this->runCapacityAlgorithm($printRequest, $branches);

        return view('manager.capacity.evaluate', compact('printRequest', 'branches', 'evaluations'));
    }

    public function runEvaluation(Request $request, PrintRequest $printRequest)
    {
        $branches = Branch::where('status', 'active')
            ->with(['machines', 'employees'])
            ->get();

        $evaluations = $this->runCapacityAlgorithm($printRequest, $branches);

        // Persist evaluations
        foreach ($evaluations as $eval) {
            CapacityEvaluation::updateOrCreate(
                ['print_request_id' => $printRequest->id, 'branch_id' => $eval['branch']->id],
                [
                    'evaluated_by'        => auth()->id(),
                    'machine_score'       => $eval['machine_score'],
                    'material_score'      => $eval['material_score'],
                    'employee_score'      => $eval['employee_score'],
                    'workload_score'      => $eval['workload_score'],
                    'deadline_score'      => $eval['deadline_score'],
                    'total_score'         => $eval['total_score'],
                    'capacity_status'     => $eval['capacity_status'],
                    'available_machines'  => $eval['available_machines'],
                    'current_workload_pct'=> $eval['workload_pct'],
                    'estimated_completion'=> $eval['estimated_completion'],
                    'deadline_feasible'   => $eval['deadline_feasible'],
                ]
            );
        }

        // Auto-generate recommendation from best score
        $best = collect($evaluations)->sortByDesc('total_score')->first();

        if ($best && $best['total_score'] > 40) {
            BranchRecommendation::updateOrCreate(
                ['print_request_id' => $printRequest->id],
                [
                    'recommended_branch_id' => $best['branch']->id,
                    'created_by'            => auth()->id(),
                    'recommendation_score'  => $best['total_score'],
                    'reason'                => "Branch {$best['branch']->name} scored highest ({$best['total_score']}/100) with {$best['available_machines']} available machines and {$best['workload_pct']}% workload utilization.",
                    'status'                => 'pending',
                ]
            );
        }

        return redirect()->route('manager.recommendations.index')
            ->with('success', 'Evaluation complete. Branch recommendation generated.');
    }

    /**
     * Core capacity scoring algorithm.
     */
    private function runCapacityAlgorithm(PrintRequest $printRequest, $branches): array
    {
        $results = [];
        $deadline = $printRequest->deadline ?? Carbon::now()->addDays(7);

        foreach ($branches as $branch) {
            // 1. Machine availability (weight 30)
            $totalMachines     = max($branch->machines->count(), 1);
            $availableMachines = $branch->machines->where('status', 'available')->count();
            $machineScore      = min(round(($availableMachines / $totalMachines) * 30), 30);

            // 2. Employee availability (weight 20)
            $totalEmp     = max($branch->employees->count(), 1);
            $availableEmp = $branch->employees->where('availability_status', 'available')->count();
            $empScore     = min(round(($availableEmp / $totalEmp) * 20), 20);

            // 3. Workload score (weight 20)
            $activeJobs  = $branch->productionJobs()->whereNotIn('status', ['completed'])->count();
            $maxJobs     = max($branch->max_daily_jobs, 1);
            $workloadPct = min(round(($activeJobs / $maxJobs) * 100, 1), 100);
            $workloadScore = max(round((1 - ($activeJobs / $maxJobs)) * 20), 0);

            // 4. Material availability (weight 20) — simplified check
            $materialScore = 15; // Default assume materials available; inventory staff maintains this

            // 5. Deadline feasibility (weight 10)
            $estimatedCompletion = Carbon::now()->addDays(2 + intval($printRequest->quantity / 500));
            $deadlineFeasible    = $estimatedCompletion->lte($deadline);
            $deadlineScore       = $deadlineFeasible ? 10 : 0;

            $totalScore = $machineScore + $empScore + $workloadScore + $materialScore + $deadlineScore;

            // Determine status
            $capacityStatus = match (true) {
                $totalScore >= 70 => 'qualified',
                $totalScore >= 50 => 'near_capacity',
                $totalScore >= 30 => 'not_qualified',
                $workloadPct >= 90 => 'over_capacity',
                default           => 'unavailable',
            };

            $results[] = [
                'branch'              => $branch,
                'machine_score'       => $machineScore,
                'material_score'      => $materialScore,
                'employee_score'      => $empScore,
                'workload_score'      => $workloadScore,
                'deadline_score'      => $deadlineScore,
                'total_score'         => $totalScore,
                'capacity_status'     => $capacityStatus,
                'available_machines'  => $availableMachines,
                'workload_pct'        => $workloadPct,
                'estimated_completion'=> $estimatedCompletion->toDateString(),
                'deadline_feasible'   => $deadlineFeasible,
            ];
        }

        return collect($results)->sortByDesc('total_score')->values()->toArray();
    }
}
