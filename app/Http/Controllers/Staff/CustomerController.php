<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount(['orders', 'printRequests', 'quotations']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        $customers = $query->latest()->paginate(15);

        return view('staff.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        abort_if($user->role !== 'customer', 404);

        $user->loadCount(['orders', 'printRequests', 'quotations']);

        $recentOrders = $user->orders()
            ->with(['printRequest', 'quotation'])
            ->latest()
            ->take(10)
            ->get();

        $recentRequests = $user->printRequests()->latest()->take(5)->get();

        return view('staff.customers.show', compact('user', 'recentOrders', 'recentRequests'));
    }
}
