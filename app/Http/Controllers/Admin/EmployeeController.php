<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['branch', 'user']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        if ($request->filled('status')) {
            $query->where('availability_status', $request->input('status'));
        }

        $employees = $query->latest()->paginate(10)->withQueryString();
        $branches = Branch::all();

        return view('admin.employees.index', compact('employees', 'branches'));
    }

    /**
     * Show form to add new employee.
     */
    public function create()
    {
        $branches = Branch::all();
        $users = User::whereIn('role', ['manager', 'staff', 'designer', 'planner', 'production', 'inventory', 'admin'])
                     ->doesntHave('employee')
                     ->get();

        return view('admin.employees.create', compact('branches', 'users'));
    }

    /**
     * Store newly created employee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'position'            => 'required|string|max:255',
            'branch_id'           => 'required|exists:branches,id',
            'user_id'             => 'nullable|exists:users,id',
            'availability_status' => 'required|in:available,on_leave,off_duty',
        ]);

        Employee::create($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Employee registered and assigned successfully.');
    }

    /**
     * Display specified employee.
     */
    public function show(Employee $employee)
    {
        $employee->load(['branch', 'user']);
        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Show form to edit employee.
     */
    public function edit(Employee $employee)
    {
        $branches = Branch::all();
        $users = User::whereIn('role', ['manager', 'staff', 'designer', 'planner', 'production', 'inventory', 'admin'])
                     ->where(function($q) use ($employee) {
                         $q->doesntHave('employee')->orWhere('id', $employee->user_id);
                     })
                     ->get();

        return view('admin.employees.edit', compact('employee', 'branches', 'users'));
    }

    /**
     * Update specified employee.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'position'            => 'required|string|max:255',
            'branch_id'           => 'required|exists:branches,id',
            'user_id'             => 'nullable|exists:users,id',
            'availability_status' => 'required|in:available,on_leave,off_duty',
        ]);

        $employee->update($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Employee details and assignment updated.');
    }

    /**
     * Remove employee.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employee record removed successfully.');
    }
}
