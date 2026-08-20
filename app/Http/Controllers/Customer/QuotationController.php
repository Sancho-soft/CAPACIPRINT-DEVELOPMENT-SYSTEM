<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * List all quotations for the authenticated customer.
     */
    public function index(Request $request)
    {
        $quotations = $request->user()
            ->quotations()
            ->with('printRequest')
            ->latest()
            ->paginate(10);

        return view('customer.quotations.index', compact('quotations'));
    }

    /**
     * Show a single quotation (own only).
     */
    public function show(Request $request, Quotation $quotation)
    {
        if ($quotation->user_id !== $request->user()->id) {
            abort(403);
        }

        $quotation->load('printRequest');
        return view('customer.quotations.show', compact('quotation'));
    }

    /**
     * Confirm a quotation (customer accepts the price).
     */
    public function confirm(Request $request, Quotation $quotation)
    {
        if ($quotation->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($quotation->status !== 'pending') {
            return back()->with('error', 'This quotation can no longer be confirmed.');
        }

        $quotation->update(['status' => 'confirmed']);

        // Update the print request status to payment
        $quotation->printRequest->update(['status' => 'payment']);

        return redirect()->route('customer.quotations.show', $quotation)
            ->with('success', 'Quotation confirmed! Proceed to payment.');
    }

    /**
     * Decline a quotation (customer rejects the price).
     */
    public function decline(Request $request, Quotation $quotation)
    {
        if ($quotation->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($quotation->status !== 'pending') {
            return back()->with('error', 'This quotation can no longer be declined.');
        }

        $quotation->update(['status' => 'declined']);

        return redirect()->route('customer.quotations.index')
            ->with('info', 'Quotation declined.');
    }

    /**
     * Download quotation as PDF.
     * (PDF generation can be added later with a package like DomPDF)
     */
    public function download(Request $request, Quotation $quotation)
    {
        if ($quotation->user_id !== $request->user()->id) {
            abort(403);
        }

        // Placeholder: return quotation view for printing
        $quotation->load('printRequest');
        return view('customer.quotations.download', compact('quotation'));
    }
}
