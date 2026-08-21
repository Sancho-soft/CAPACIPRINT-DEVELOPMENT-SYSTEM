<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display listing of all branches with metrics.
     */
    public function index(Request $request)
    {
        $query = Branch::withCount(['machines', 'employees', 'productionJobs']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('manager_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $branches = $query->latest()->paginate(10)->withQueryString();

        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Show form to create a new branch.
     */
    public function create()
    {
        return view('admin.branches.create');
    }

    /**
     * Store newly created branch.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255|unique:branches',
            'location'       => 'required|string|max:255',
            'address'        => 'nullable|string|max:500',
            'phone'          => 'nullable|string|max:50',
            'manager_name'   => 'nullable|string|max:255',
            'status'         => 'required|in:active,inactive,maintenance',
            'max_daily_jobs' => 'required|integer|min:1|max:1000',
        ]);

        Branch::create($validated);

        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully.');
    }

    /**
     * Display specified branch details.
     */
    public function show(Branch $branch)
    {
        $branch->load(['machines', 'employees.user', 'inventory.material', 'productionJobs' => function($q) {
            $q->latest()->limit(5);
        }]);

        return view('admin.branches.show', compact('branch'));
    }

    /**
     * Show form to edit branch.
     */
    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Update specified branch.
     */
    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255|unique:branches,name,' . $branch->id,
            'location'       => 'required|string|max:255',
            'address'        => 'nullable|string|max:500',
            'phone'          => 'nullable|string|max:50',
            'manager_name'   => 'nullable|string|max:255',
            'status'         => 'required|in:active,inactive,maintenance',
            'max_daily_jobs' => 'required|integer|min:1|max:1000',
        ]);

        $branch->update($validated);

        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    /**
     * Delete branch.
     */
    public function destroy(Branch $branch)
    {
        if ($branch->productionJobs()->whereNotIn('status', ['completed', 'cancelled'])->exists()) {
            return redirect()->route('admin.branches.index')->with('error', 'Cannot delete branch with active production jobs.');
        }

        $branch->delete();

        return redirect()->route('admin.branches.index')->with('success', 'Branch removed successfully.');
    }
}
