<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\PricingRule;
use Illuminate\Http\Request;

class PricingRuleController extends Controller
{
    /**
     * Display all pricing calculation rules matrix.
     */
    public function index()
    {
        $rules = PricingRule::latest()->paginate(10);
        return view('manager.pricing-rules.index', compact('rules'));
    }

    /**
     * Store a new pricing calculation rule.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'service'        => ['required', 'string', 'max:100'],
            'size'           => ['nullable', 'string', 'max:50'],
            'base_rate'      => ['required', 'numeric', 'min:0'],
            'material_rate'  => ['required', 'numeric', 'min:0'],
            'finishing_rate' => ['required', 'numeric', 'min:0'],
        ]);

        PricingRule::create(array_merge($data, ['is_active' => true]));

        return back()->with('success', 'Pricing calculation rule created successfully.');
    }

    /**
     * Update an existing pricing calculation rule.
     */
    public function update(Request $request, PricingRule $pricingRule)
    {
        $data = $request->validate([
            'base_rate'      => ['required', 'numeric', 'min:0'],
            'material_rate'  => ['required', 'numeric', 'min:0'],
            'finishing_rate' => ['required', 'numeric', 'min:0'],
            'is_active'      => ['required', 'boolean'],
        ]);

        $pricingRule->update($data);

        return back()->with('success', 'Pricing calculation rule updated successfully.');
    }
}
