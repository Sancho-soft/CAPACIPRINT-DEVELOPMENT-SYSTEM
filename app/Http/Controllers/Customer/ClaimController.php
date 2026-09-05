<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ClaimReference;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    /**
     * List all claim references for the authenticated customer.
     */
    public function index(Request $request)
    {
        $claims = $request->user()
            ->claimReferences()
            ->with(['order.printRequest', 'order.payment'])
            ->latest()
            ->get();

        return view('customer.claiming.index', compact('claims'));
    }

    /**
     * Show QR claim view for a specific order (own only).
     */
    public function show(Request $request, $orderId)
    {
        $claim = $request->user()
            ->claimReferences()
            ->where('order_id', $orderId)
            ->with(['order.printRequest', 'order.payment'])
            ->firstOrFail();

        return view('customer.claiming.show', compact('claim'));
    }
}
