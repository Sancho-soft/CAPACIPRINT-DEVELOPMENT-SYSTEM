<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Models\PrintRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingProofs = PrintRequest::whereIn('status', ['submitted', 'verified'])->latest()->take(10)->get();
        $approvedProofs = PrintRequest::where('status', 'quoted')->count();
        $revisionRequests = PrintRequest::where('status', 'declined')->count();

        return view('designer.dashboard', compact('pendingProofs', 'approvedProofs', 'revisionRequests'));
    }
}
