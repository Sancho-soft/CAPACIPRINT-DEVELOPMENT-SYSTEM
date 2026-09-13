<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PrintRequest;
use App\Models\Quotation;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PricingRule;
use App\Models\InternalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = Quotation::with(['user', 'printRequest']);

        // Search by quotation number, customer name, or service
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('printRequest', function ($pq) use ($search) {
                      $pq->where('service', 'like', "%{$search}%")
                         ->orWhere('material', 'like', "%{$search}%");
                  });
            });
        }

        // Date Assigned / Created filtering
        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['created_at', 'total_price', 'valid_until', 'quotation_number'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $quotations = $query->paginate(10)->withQueryString();

        return view('staff.quotations.index', compact('quotations'));
    }

    public function create(Request $request)
    {
        // Allow pre-selecting a print request
        $printRequest = null;
        if ($id = $request->get('print_request_id')) {
            $printRequest = PrintRequest::with('user')->findOrFail($id);
        }

        // Load all unquoted print requests for dropdown
        $openRequests = PrintRequest::with('user')
            ->whereIn('status', ['submitted', 'quotation'])
            ->whereDoesntHave('quotation', fn($q) => $q->whereIn('status', ['pending', 'confirmed']))
            ->latest()
            ->get();

        $pricingRule = null;
        if ($printRequest) {
            $pricingRule = PricingRule::findBestMatch($printRequest->service, $printRequest->size);
        }

        return view('staff.quotations.create', compact('printRequest', 'openRequests', 'pricingRule'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'print_request_id' => ['required', 'exists:print_requests,id'],
            'base_cost'        => ['required', 'numeric', 'min:0'],
            'material_cost'    => ['required', 'numeric', 'min:0'],
            'finishing_cost'   => ['required', 'numeric', 'min:0'],
            'valid_until'      => ['required', 'date', 'after:today'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);

        $pr    = PrintRequest::findOrFail($data['print_request_id']);
        $total = $data['base_cost'] + $data['material_cost'] + $data['finishing_cost'];

        DB::transaction(function () use ($data, $pr, $total) {
            $quotation = Quotation::create([
                'quotation_number' => 'QT-' . strtoupper(Str::random(8)),
                'print_request_id' => $pr->id,
                'user_id'          => $pr->user_id,
                'base_cost'        => $data['base_cost'],
                'material_cost'    => $data['material_cost'],
                'finishing_cost'   => $data['finishing_cost'],
                'total_price'      => $total,
                'valid_until'      => $data['valid_until'],
                'notes'            => $data['notes'] ?? null,
                'status'           => 'pending',
            ]);

            $pr->update(['status' => 'quotation']);

            // Notify customer
            \App\Models\CustomerNotification::create([
                'user_id'  => $pr->user_id,
                'order_id' => null,
                'title'    => 'Quotation Ready',
                'body'     => "Your quotation #{$quotation->quotation_number} for {$pr->service} is ready for your review.",
                'type'     => 'quotation',
            ]);
        });

        return redirect()->route('staff.quotations.index')
            ->with('success', 'Quotation created and customer notified.');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['user', 'printRequest', 'order']);
        return view('staff.quotations.show', compact('quotation'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        abort_if($quotation->status !== 'pending', 403, 'Only pending quotations can be updated.');

        $data = $request->validate([
            'base_cost'     => ['required', 'numeric', 'min:0'],
            'material_cost' => ['required', 'numeric', 'min:0'],
            'finishing_cost'=> ['required', 'numeric', 'min:0'],
            'valid_until'   => ['required', 'date', 'after:today'],
            'notes'         => ['nullable', 'string', 'max:1000'],
        ]);

        $total = $data['base_cost'] + $data['material_cost'] + $data['finishing_cost'];

        $quotation->update([
            'base_cost'     => $data['base_cost'],
            'material_cost' => $data['material_cost'],
            'finishing_cost'=> $data['finishing_cost'],
            'total_price'   => $total,
            'valid_until'   => $data['valid_until'],
            'notes'         => $data['notes'] ?? null,
        ]);

        return redirect()->route('staff.quotations.show', $quotation)
            ->with('success', 'Quotation updated.');
    }

    public function send(Request $request, Quotation $quotation)
    {
        // Resend notification to customer
        \App\Models\CustomerNotification::create([
            'user_id'  => $quotation->user_id,
            'order_id' => null,
            'title'    => 'Quotation Update',
            'body'     => "Please review your updated quotation #{$quotation->quotation_number}.",
            'type'     => 'quotation',
        ]);

        return back()->with('success', 'Customer has been notified.');
    }

    public function download(Quotation $quotation)
    {
        $quotation->load(['user', 'printRequest']);
        return view('staff.quotations.download', compact('quotation'));
    }

    /**
     * Pricing Rules Matrix Index
     */
    public function pricingRulesIndex()
    {
        $rules = PricingRule::latest()->paginate(7);
        return view('staff.pricing-rules.index', compact('rules'));
    }

    /**
     * Store new Pricing Rule
     */
    public function pricingRulesStore(Request $request)
    {
        $data = $request->validate([
            'service'        => ['required', 'string'],
            'size'           => ['nullable', 'string'],
            'base_rate'      => ['required', 'numeric', 'min:0'],
            'material_rate'  => ['required', 'numeric', 'min:0'],
            'finishing_rate' => ['required', 'numeric', 'min:0'],
        ]);

        PricingRule::create(array_merge($data, ['is_active' => true]));

        return back()->with('success', 'Pricing Rule created successfully.');
    }

    /**
     * Update existing Pricing Rule
     */
    public function pricingRulesUpdate(Request $request, PricingRule $pricingRule)
    {
        $data = $request->validate([
            'base_rate'      => ['required', 'numeric', 'min:0'],
            'material_rate'  => ['required', 'numeric', 'min:0'],
            'finishing_rate' => ['required', 'numeric', 'min:0'],
            'is_active'      => ['required', 'boolean'],
        ]);

        $pricingRule->update($data);

        return back()->with('success', 'Pricing Rule updated successfully.');
    }
}
