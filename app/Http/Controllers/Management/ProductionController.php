<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\ProductionJob;

class ProductionController extends Controller
{
    public function index()
    {
        $jobs = ProductionJob::with(['order.user', 'order.printRequest', 'branch', 'assignedTo'])
            ->latest()->paginate(20);

        return view('management.production.index', compact('jobs'));
    }
}
