<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'printRequest', 'quotation'])->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20);
        return view('management.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'printRequest', 'quotation', 'payment', 'productionJob.branch', 'claimReference']);
        return view('management.orders.show', compact('order'));
    }
}
