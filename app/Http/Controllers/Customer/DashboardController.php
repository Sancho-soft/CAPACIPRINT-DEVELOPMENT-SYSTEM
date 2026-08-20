<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Eager-load latest orders and notifications
        $orders = $user->orders()
            ->with(['printRequest', 'quotation'])
            ->latest()
            ->take(5)
            ->get();

        $activeOrdersCount    = $user->orders()->whereNotIn('status', ['completed', 'claimed'])->count();
        $pendingQuotesCount   = $user->quotations()->where('status', 'pending')->count();
        $completedOrdersCount = $user->orders()->whereIn('status', ['completed', 'claimed'])->count();

        $latestOrder = $user->orders()
            ->with(['printRequest', 'claimReference'])
            ->whereNotIn('status', ['claimed'])
            ->latest()
            ->first();

        $unreadCount = $user->notifications()->where('is_read', false)->count();

        return view('customer.dashboard', compact(
            'orders',
            'activeOrdersCount',
            'pendingQuotesCount',
            'completedOrdersCount',
            'latestOrder',
            'unreadCount'
        ));
    }
}
