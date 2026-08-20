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
        $orders = $request->user()
            ->orders()
            ->with(['printRequest', 'quotation'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
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
