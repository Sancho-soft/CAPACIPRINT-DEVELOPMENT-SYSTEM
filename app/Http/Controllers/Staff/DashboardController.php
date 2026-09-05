<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PrintRequest;
use App\Models\Quotation;
use App\Models\ProductionJob;
use App\Models\InternalNotification;

class DashboardController extends Controller
{
    public function index()
    {
        $newRequestsCount    = PrintRequest::where('status', 'submitted')->count();
        $pendingQuotesCount  = Quotation::where('status', 'pending')->count();
        $confirmedOrdersCount= Order::where('status', 'payment')->count();
        $activeOrdersCount   = Order::whereNotIn('status', ['completed','claimed','cancelled'])->count();
        $readyForPickupCount = Order::where('status', 'ready_for_pickup')->count();

        // Customer Service Intake & Fulfillment Pipeline
        $pipeline = [
            [
                'key'   => 'intake',
                'label' => 'New Request Intake',
                'count' => $newRequestsCount,
                'icon'  => 'fa-solid fa-file-circle-plus',
                'color' => 'cyan',
            ],
            [
                'key'   => 'quotation',
                'label' => 'Pending Estimation',
                'count' => $pendingQuotesCount,
                'icon'  => 'fa-solid fa-file-invoice-dollar',
                'color' => 'indigo',
            ],
            [
                'key'   => 'payment',
                'label' => 'Payment Verification',
                'count' => Order::where('payment_status', 'submitted')->count(),
                'icon'  => 'fa-solid fa-credit-card',
                'color' => 'amber',
            ],
            [
                'key'    => 'production',
                'label'  => 'In Production',
                'count'  => ProductionJob::whereIn('status', ['preparing', 'in_production'])->count(),
                'icon'   => 'fa-solid fa-industry',
                'color'  => 'teal',
                'active' => true,
            ],
            [
                'key'   => 'ready',
                'label' => 'Ready for Pickup',
                'count' => $readyForPickupCount,
                'icon'  => 'fa-solid fa-box-open',
                'color' => 'emerald',
            ],
            [
                'key'   => 'claimed',
                'label' => 'Claimed Handover',
                'count' => Order::where('status', 'claimed')->count(),
                'icon'  => 'fa-solid fa-handshake',
                'color' => 'blue',
            ],
        ];

        // Orders awaiting payment verification
        $pendingPaymentOrders = Order::with(['printRequest', 'user', 'payment'])
            ->where('payment_status', 'submitted')
            ->latest()
            ->take(5)
            ->get();

        // Attention items for Front Desk / CS
        $attentionItems = [];

        // 1. Pending payment slips
        foreach ($pendingPaymentOrders as $pOrd) {
            $attentionItems[] = [
                'title'        => "Payment Verification Required: {$pOrd->order_number}",
                'description'  => "Customer: " . ($pOrd->user->name ?? 'Client') . " submitted payment proof for ₱" . number_format($pOrd->payment->amount ?? 0, 2) . " (Ref: " . ($pOrd->payment->payment_reference ?? 'N/A') . ")",
                'severity'     => 'critical',
                'icon'         => 'fa-solid fa-credit-card',
                'badge'        => 'PAYMENT PENDING',
                'meta'         => "Submitted " . ($pOrd->updated_at ? $pOrd->updated_at->diffForHumans() : 'Recently'),
                'action_url'   => route('staff.orders.show', $pOrd->id),
                'action_label' => 'Verify Slip',
            ];
        }

        // 2. Unverified print requests
        $unverifiedRequests = PrintRequest::where('status', 'submitted')->with('user')->take(3)->get();
        foreach ($unverifiedRequests as $uReq) {
            $attentionItems[] = [
                'title'        => "Unverified Print Request: #{$uReq->id} ({$uReq->service})",
                'description'  => "Customer: " . ($uReq->user->name ?? 'Client') . " &middot; Specs: {$uReq->quantity} pcs, {$uReq->size} on " . ($uReq->material ?? 'Standard Media'),
                'severity'     => 'warning',
                'icon'         => 'fa-solid fa-file-signature',
                'badge'        => 'AWAITING SPECS',
                'meta'         => "Submitted " . ($uReq->created_at ? $uReq->created_at->diffForHumans() : 'Recently'),
                'action_url'   => route('staff.print-requests.show', $uReq->id),
                'action_label' => 'Verify Specs',
            ];
        }

        // Recent customer print requests
        $recentRequests = PrintRequest::with('user')
            ->latest()
            ->take(6)
            ->get();

        // Recent quotations
        $recentQuotations = Quotation::with(['printRequest', 'user'])
            ->latest()
            ->take(6)
            ->get();

        // Recent customer orders
        $recentOrders = Order::with(['printRequest', 'user'])
            ->latest()
            ->take(8)
            ->get();

        $unread = InternalNotification::where('user_id', auth()->id())
            ->where('is_read', false)->count();

        return view('staff.dashboard', compact(
            'newRequestsCount',
            'pendingQuotesCount',
            'confirmedOrdersCount',
            'activeOrdersCount',
            'readyForPickupCount',
            'pipeline',
            'attentionItems',
            'recentRequests',
            'pendingPaymentOrders',
            'recentOrders',
            'recentQuotations',
            'unread'
        ));
    }
}
