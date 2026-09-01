<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomerNotification;
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

    public function update(Request $request, User $user)
    {
        abort_if($user->role !== 'customer', 403);

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $user->update($data);

        return redirect()->route('staff.customers.index')
            ->with('success', 'Customer profile updated successfully.');
    }

    public function notify(Request $request, User $user)
    {
        abort_if($user->role !== 'customer', 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string', 'max:1000'],
            'type'  => ['nullable', 'string', 'max:50'],
        ]);

        CustomerNotification::create([
            'user_id'  => $user->id,
            'order_id' => null,
            'title'    => $data['title'],
            'body'     => $data['body'],
            'type'     => $data['type'] ?? 'info',
        ]);

        return redirect()->route('staff.customers.index')
            ->with('success', 'Direct notification sent to customer.');
    }
}
