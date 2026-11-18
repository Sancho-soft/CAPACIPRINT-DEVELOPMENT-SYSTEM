<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * List all orders belonging to the authenticated customer.
     */
    public function index(Request $request)
    {
        $query = $request->user()
            ->orders()
            ->with(['printRequest', 'quotation']);

        $statusFilter = $request->input('status') ?? $request->input('filter');
        if ($statusFilter === 'active') {
            $query->whereNotIn('status', ['completed', 'claimed', 'cancelled']);
        } elseif ($statusFilter === 'completed') {
            $query->whereIn('status', ['completed', 'claimed']);
        }

        $orders = $query->latest()->paginate(7)->withQueryString();

        return view('customer.orders.index', compact('orders', 'statusFilter'));
    }

    /**
     * Show a single order's details (own only).
     */
    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        $order->load(['printRequest', 'quotation', 'payment', 'claimReference']);
        return view('customer.orders.show', compact('order'));
    }

    /**
     * Show the order tracking timeline.
     */
    public function tracking(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        $order->load(['printRequest', 'claimReference']);
        $steps = Order::statusSteps();

        return view('customer.orders.tracking', compact('order', 'steps'));
    }
}
