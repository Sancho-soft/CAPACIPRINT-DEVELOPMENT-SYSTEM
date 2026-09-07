<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Models\PrintRequest;
use App\Models\DesignProof;
use App\Models\CustomerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    /**
     * Display all assigned design requests and proofs.
     */
    public function index(Request $request)
    {
        $baseQuery = fn() => PrintRequest::where(function ($q) {
            $q->whereNotNull('design_file_path')
              ->orWhereHas('designProofs');
        });

        // Tab counts
        $counts = [
            'all'                => $baseQuery()->count(),
            'needs_proof'        => $baseQuery()->doesntHave('designProofs')->count(),
            'revision_requested' => $baseQuery()->whereHas('latestProof', fn($q) => $q->where('status', 'revision_requested'))->count(),
            'approved'           => $baseQuery()->whereHas('latestProof', fn($q) => $q->where('status', 'approved'))->count(),
        ];

        $query = PrintRequest::with(['user', 'designProofs.designer', 'latestProof'])
            ->where(function ($q) {
                $q->whereNotNull('design_file_path')
                  ->orWhereHas('designProofs');
            });

        // Status Filter
        if ($status = $request->get('status')) {
            if ($status === 'needs_proof') {
                $query->doesntHave('designProofs');
            } else {
                $query->whereHas('latestProof', fn($q) => $q->where('status', $status));
            }
        }

        // Search Filter
        if ($search = trim($request->get('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('service', 'like', "%{$search}%")
                  ->orWhere('material', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        $printRequests = $query->latest()->paginate(12)->withQueryString();

        return view('designer.index', compact('printRequests', 'counts'));
    }

    /**
     * Show design workspace for a specific print request.
     */
    public function show(PrintRequest $printRequest)
    {
        $printRequest->load(['user', 'designProofs.designer', 'quotation', 'order']);
        return view('designer.show', compact('printRequest'));
    }

    /**
     * Upload a new design proof / layout version.
     */
    public function storeProof(Request $request, PrintRequest $printRequest)
    {
        $request->validate([
            'proof_file'     => 'required|file|max:51200|mimes:pdf,png,jpg,jpeg,webp',
            'designer_notes' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('proof_file');
        $filePath = $file->store('design-proofs/' . $printRequest->id, 'public');
        $fileName = $file->getClientOriginalName();
        $fileSize = round($file->getSize() / 1024, 1) . ' KB';

        $nextVersion = ($printRequest->designProofs()->max('version') ?? 0) + 1;

        $proof = DesignProof::create([
            'print_request_id' => $printRequest->id,
            'designer_id'      => auth()->id(),
            'version'          => $nextVersion,
            'proof_file_path'  => $filePath,
            'proof_file_name'  => $fileName,
            'proof_file_size'  => $fileSize,
            'designer_notes'   => $request->input('designer_notes'),
            'status'           => 'pending_review',
        ]);

        // Notify the customer that a layout proof is ready for approval
        CustomerNotification::create([
            'user_id'  => $printRequest->user_id,
            'order_id' => $printRequest->order?->id,
            'title'    => 'Design Proof Ready for Review (v' . $nextVersion . ')',
            'body'     => "Our design team has uploaded a new layout proof for '{$printRequest->service}'. Please review and approve it.",
            'type'     => 'design_proof',
        ]);

        return back()->with('success', "Design Proof v{$nextVersion} uploaded and sent to customer for approval.");
    }

    /**
     * Upload final production-ready artwork file.
     */
    public function uploadProductionFile(Request $request, DesignProof $designProof)
    {
        $request->validate([
            'production_file' => 'required|file|max:102400|mimes:pdf,ai,eps,tiff,tif,zip',
        ]);

        $file = $request->file('production_file');
        $filePath = $file->store('production-files/' . $designProof->print_request_id, 'public');
        $fileName = $file->getClientOriginalName();

        $designProof->update([
            'production_file_path' => $filePath,
            'production_file_name' => $fileName,
        ]);

        return back()->with('success', 'Final production-ready file uploaded successfully.');
    }

    /**
     * Customer Proof Review: Approve or Request Revision.
     */
    public function customerReview(Request $request, DesignProof $designProof)
    {
        // Check ownership
        if (auth()->id() !== $designProof->printRequest->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'action'            => 'required|in:approve,revise',
            'customer_feedback' => 'nullable|string|max:1000',
        ]);

        if ($request->input('action') === 'approve') {
            $designProof->update([
                'status'            => 'approved',
                'customer_feedback' => $request->input('customer_feedback'),
                'approved_at'       => now(),
            ]);
            $msg = 'Design proof approved successfully!';
        } else {
            $request->validate(['customer_feedback' => 'required|string|min:5']);
            $designProof->update([
                'status'            => 'revision_requested',
                'customer_feedback' => $request->input('customer_feedback'),
            ]);
            $msg = 'Revision request submitted to the design team.';
        }

        return back()->with('success', $msg);
    }
}
