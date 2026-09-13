<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PricingRule;
use App\Models\Branch;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the Customer CRM Landing Page.
     */
    public function index()
    {
        if (auth()->check()) {
            return match(auth()->user()->role) {
                'system_admin', 'admin' => redirect()->route('admin.dashboard'),
                'owner', 'management'  => redirect()->route('management.dashboard'),
                'manager'              => redirect()->route('manager.dashboard'),
                'production_officer'   => redirect()->route('manager.production-planning.index'),
                'staff'                => redirect()->route('staff.dashboard'),
                'designer'             => redirect()->route('designer.dashboard'),
                'production'           => redirect()->route('production.dashboard'),
                'inventory'            => redirect()->route('inventory.dashboard'),
                default                => redirect()->route('customer.dashboard'),
            };
        }

        $pricingRules = PricingRule::where('is_active', true)->get();
        $branches     = Branch::where('status', 'active')->get();

        return view('welcome', compact('pricingRules', 'branches'));
    }

    /**
     * Live public order tracking lookup for customers.
     */
    public function trackOrder(Request $request)
    {
        $request->validate([
            'order_number' => ['required', 'string', 'max:50'],
        ]);

        $orderNumber = trim($request->input('order_number'));

        // Handle with or without 'ORD-' prefix
        $cleanNumber = strtoupper($orderNumber);
        if (!str_starts_with($cleanNumber, 'ORD-')) {
            $queryNumber = 'ORD-' . $cleanNumber;
        } else {
            $queryNumber = $cleanNumber;
        }

        $order = Order::with(['printRequest', 'claimReference'])
            ->where('order_number', $queryNumber)
            ->orWhere('order_number', $cleanNumber)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => "Order #{$orderNumber} was not found. Please verify your order or claim reference number.",
            ], 404);
        }

        $steps = Order::statusSteps();
        $currentIndex = array_search($order->status, $steps);
        if ($currentIndex === false) {
            $currentIndex = 0;
        }

        return response()->json([
            'success' => true,
            'order'   => [
                'order_number'         => $order->order_number,
                'service'              => $order->printRequest->service ?? 'Custom Print Service',
                'quantity'             => $order->printRequest->quantity ?? 1,
                'size'                 => $order->printRequest->size ?? 'Standard',
                'material'             => $order->printRequest->material ?? 'Standard Material',
                'finishing'            => $order->printRequest->finishing ?? 'None',
                'status'               => $order->status,
                'status_label'         => $order->status_label,
                'status_index'         => $currentIndex,
                'steps'                => $steps,
                'progress_percent'     => round((($currentIndex + 1) / count($steps)) * 100),
                'assigned_branch'      => $order->assigned_branch ?? 'Routing Hub',
                'estimated_completion' => $order->estimated_completion ? $order->estimated_completion->format('M d, Y') : 'In Evaluation',
                'created_at'           => $order->created_at->format('M d, Y'),
            ],
        ]);
    }
}
