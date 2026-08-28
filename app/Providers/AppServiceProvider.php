<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Branch;
use App\Models\ProductionJob;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $totalCapacity = Branch::where('status', 'active')->sum('max_daily_jobs');
            $activeJobs = ProductionJob::whereNotIn('status', ['completed', 'cancelled'])->count();
            
            $systemLoad = 0;
            if ($totalCapacity > 0) {
                $systemLoad = min(round(($activeJobs / $totalCapacity) * 100, 1), 100);
            }

            // Real-time branch load breakdown
            $branchLoads = Branch::where('status', 'active')->get()->map(function ($b) {
                return [
                    'name' => $b->name,
                    'load' => $b->workload_percent,
                    'active' => $b->active_jobs_count,
                    'capacity' => $b->max_daily_jobs,
                ];
            });

            $view->with([
                'globalSystemLoad' => $systemLoad,
                'globalActiveJobs' => $activeJobs,
                'globalTotalCapacity' => $totalCapacity,
                'globalBranchLoads' => $branchLoads,
            ]);
        });
    }
}
