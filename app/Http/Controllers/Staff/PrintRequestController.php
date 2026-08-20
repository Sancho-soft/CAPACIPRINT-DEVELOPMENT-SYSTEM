<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PrintRequest;
use Illuminate\Http\Request;

class PrintRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = PrintRequest::with('user')->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->get('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%"))
                  ->orWhere('service', 'like', "%$search%");
        }

        $printRequests = $query->paginate(15);

        return view('staff.print-requests.index', compact('printRequests'));
    }

    public function show(PrintRequest $printRequest)
    {
        $printRequest->load(['user', 'quotation', 'order.payment']);
        return view('staff.print-requests.show', compact('printRequest'));
    }

    public function verify(Request $request, PrintRequest $printRequest)
    {
        $data = $request->validate([
            'verified_notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Mark as reviewed (update status only if still submitted)
        if ($printRequest->status === 'submitted') {
            $printRequest->update(['status' => 'quotation']);
        }

        return redirect()->route('staff.print-requests.show', $printRequest)
            ->with('success', 'Print request verified and moved to quotation stage.');
    }
}
