<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Models\PrintRequest;
use App\Models\DesignProof;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Pending Artwork Reviews queue (requests that have customer artwork or proof history)
        $pendingProofs = PrintRequest::with(['user', 'latestProof'])
            ->where(function ($q) {
                $q->whereNotNull('design_file_path')
                  ->orWhereHas('designProofs');
            })
            ->latest()
            ->take(10)
            ->get();

        // 2. Accurate KPI metrics
        $needsProofCount = PrintRequest::where(function ($q) {
                $q->whereNotNull('design_file_path')
                  ->orWhereHas('designProofs');
            })
            ->doesntHave('designProofs')
            ->count();

        $approvedProofs = DesignProof::where('status', 'approved')->count();
        $revisionRequests = DesignProof::where('status', 'revision_requested')->count();

        // 3. Operational Attention Center Items (Urgent revisions & unproofed submissions)
        $attentionItems = [];

        // Critical: Customer revision requests requiring immediate redesign
        $revisions = DesignProof::with(['printRequest.user'])
            ->where('status', 'revision_requested')
            ->latest('updated_at')
            ->take(5)
            ->get();

        foreach ($revisions as $rev) {
            $clientName = $rev->printRequest?->user?->name ?? 'Customer';
            $service = $rev->printRequest?->service ?? 'Print Job';
            $feedback = $rev->customer_feedback ? '"' . Str::limit($rev->customer_feedback, 120) . '"' : 'Customer requested layout modifications.';
            
            $attentionItems[] = [
                'title' => "Customer Revision Requested on #REQ-" . str_pad($rev->print_request_id, 5, '0', STR_PAD_LEFT) . " ({$service})",
                'description' => "Client {$clientName}: {$feedback}",
                'badge' => 'Revision Needed',
                'severity' => 'critical',
                'icon' => 'fa-solid fa-arrows-rotate',
                'action_url' => route('designer.show', $rev->print_request_id),
                'action_label' => 'Open Canvas',
                'meta' => 'Requested ' . $rev->updated_at->diffForHumans(),
            ];
        }

        // Warning: New jobs waiting for initial proof v1
        $unproofedJobs = PrintRequest::with('user')
            ->whereNotNull('design_file_path')
            ->doesntHave('designProofs')
            ->whereIn('status', ['submitted', 'verified'])
            ->latest()
            ->take(3)
            ->get();

        foreach ($unproofedJobs as $job) {
            $clientName = $job->user?->name ?? 'Customer';
            $attentionItems[] = [
                'title' => "Initial Proof Required: #REQ-" . str_pad($job->id, 5, '0', STR_PAD_LEFT) . " &middot; {$job->service}",
                'description' => "Customer artwork uploaded. Needs bleed verification, CMYK check, and v1 proof creation.",
                'badge' => 'Needs Proof',
                'severity' => 'info',
                'icon' => 'fa-solid fa-file-circle-plus',
                'action_url' => route('designer.show', $job->id),
                'action_label' => 'Inspect Artwork',
                'meta' => 'Submitted ' . $job->created_at->diffForHumans(),
            ];
        }

        return view('designer.dashboard', compact(
            'pendingProofs',
            'needsProofCount',
            'approvedProofs',
            'revisionRequests',
            'attentionItems'
        ));
    }
}
