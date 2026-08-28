<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PrintRequest;
use App\Models\Quotation;
use App\Models\InternalNotification;

class DashboardController extends Controller
{
    public function index()
    {
        $newRequestsCount    = PrintRequest::where('status', 'submitted')->count();
        $pendingQuotesCount  = Quotation::where('status', 'pending')->count();
        $confirmedOrdersCount= Order::where('status', 'payment')->count();
        $activeOrdersCount   = Order::whereNotIn('status', ['completed','claimed','cancelled'])->count();

        $recentRequests = PrintRequest::with('user')
            ->latest()
            ->take(8)
            ->get();

        $pendingPaymentOrders = Order::with(['printRequest', 'user', 'payment'])
            ->where('payment_status', 'submitted')
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = Order::with(['printRequest', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $recentQuotations = Quotation::with(['printRequest', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $unread = InternalNotification::where('user_id', auth()->id())
            ->where('is_read', false)->count();

        return view('staff.dashboard', compact(
            'newRequestsCount',
            'pendingQuotesCount',
            'confirmedOrdersCount',
            'activeOrdersCount',
            'recentRequests',
            'pendingPaymentOrders',
            'recentOrders',
            'recentQuotations',
            'unread'
        ));
    }
}
