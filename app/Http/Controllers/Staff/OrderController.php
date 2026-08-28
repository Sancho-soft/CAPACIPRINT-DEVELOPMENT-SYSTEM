<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PrintRequest;
use App\Models\Payment;
use App\Models\ProductionJob;
use App\Models\InternalNotification;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'printRequest', 'quotation', 'payment'])->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($payment = $request->get('payment_status')) {
            $query->where('payment_status', $payment);
        }

        $orders = $query->paginate(15);
        return view('staff.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'printRequest', 'quotation', 'payment', 'productionJob.branch', 'claimReference']);
        return view('staff.orders.show', compact('order'));
    }

    /**
     * Staff confirms customer payment and changes payment_status to 'confirmed'.
     */
    public function confirmPayment(Request $request, Order $order)
    {
        if ($order->payment_status !== 'submitted') {
            return back()->with('error', 'Payment is not in a submitted state.');
        }

        DB::transaction(function () use ($order) {
            $order->update([
                'payment_status' => 'confirmed',
                'status'         => 'branch_recommended',
            ]);

            if ($order->payment) {
                $order->payment->update(['status' => 'confirmed']);
            }

            // Notify customer
            \App\Models\CustomerNotification::create([
                'user_id'  => $order->user_id,
                'order_id' => $order->id,
                'title'    => 'Payment Confirmed',
                'body'     => "Your payment for Order #{$order->order_number} has been confirmed. We are now processing your order.",
                'type'     => 'payment',
            ]);
        });

        return back()->with('success', 'Payment confirmed. Order moved to production planning.');
    }

    /**
     * Staff creates a production job entry after branch recommendation is confirmed.
     */
    public function createProductionJob(Request $request, Order $order)
    {
        $data = $request->validate([
            'branch_id' => ['required', 'exists:branches,id'],
            'priority'  => ['required', 'in:normal,rush,urgent'],
        ]);

        abort_if($order->productionJob()->exists(), 422, 'A production job already exists for this order.');

        $job = ProductionJob::create([
            'job_number' => 'JOB-' . strtoupper(substr(md5($order->id . now()), 0, 8)),
            'order_id'   => $order->id,
            'branch_id'  => $data['branch_id'],
            'priority'   => $data['priority'],
            'status'     => 'assigned',
        ]);

        $order->update(['status' => 'production', 'assigned_branch' => Branch::find($data['branch_id'])->name]);

        return back()->with('success', "Production job #{$job->job_number} created.");
    }

    /**
     * Display the Claim & QR Code Scanner page for Branch Staff.
     */
    public function claimScanner()
    {
        $recentClaims = \App\Models\ClaimReference::with('order.user')->latest()->take(10)->get();
        return view('staff.claims.scanner', compact('recentClaims'));
    }

    /**
     * Process QR / Claim code verification and complete order pickup.
     */
    public function claimVerify(Request $request)
    {
        $request->validate([
            'claim_code' => ['required', 'string'],
        ]);

        $code = trim($request->claim_code);

        // Find claim reference by claim_code or order_number (case-insensitive)
        $claim = \App\Models\ClaimReference::where('claim_code', 'LIKE', $code)
            ->orWhereHas('order', fn($q) => $q->where('order_number', 'LIKE', $code))
            ->first();

        if (!$claim) {
            // Fallback: check if Order exists directly by order_number
            $order = Order::where('order_number', 'LIKE', $code)->first();
            if ($order) {
                $claim = \App\Models\ClaimReference::firstOrCreate(
                    ['order_id' => $order->id],
                    [
                        'user_id'         => $order->user_id,
                        'claim_code'      => 'CLM-' . strtoupper(\Illuminate\Support\Str::random(8)),
                        'pickup_branch'   => $order->assigned_branch ?? 'Main Branch',
                        'completion_date' => today(),
                    ]
                );
            }
        }

        if (!$claim) {
            return back()->with('error', "Invalid QR / Claim code: '{$code}'. No matching order found.");
        }

        if ($claim->status === 'claimed') {
            return back()->with('error', "Order #{$claim->order->order_number} has already been claimed on " . $claim->claimed_at?->format('M d, Y h:i A') . ".");
        }

        DB::transaction(function () use ($claim) {
            $claim->update([
                'status'     => 'claimed',
                'claimed_at' => now(),
            ]);

            $claim->order->update([
                'status' => 'claimed',
            ]);

            // Notify Customer
            \App\Models\CustomerNotification::create([
                'user_id'  => $claim->order->user_id,
                'order_id' => $claim->order->id,
                'title'    => 'Order Claimed Successfully',
                'body'     => "Order #{$claim->order->order_number} was claimed at the branch. Thank you for choosing Morning Star Printing Press!",
                'type'     => 'order',
            ]);
        });

        return back()->with('success', "Success! Order #{$claim->order->order_number} verified and marked as CLAIMED.");
    }
}
