<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PrintRequest;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrintRequestController extends Controller
{
    /**
     * Display listing of customer print requests.
     */
    public function index(Request $request)
    {
        $query = PrintRequest::with('user')->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', fn($sq) => $sq->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%"))
                  ->orWhere('service', 'like', "%$search%");
            });
        }

        $printRequests = $query->paginate(7)->withQueryString();

        return view('staff.print-requests.index', compact('printRequests'));
    }

    /**
     * Show form for Customer Service to record a new print request (walk-in or on behalf of client).
     */
    public function create()
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $branches  = Branch::where('status', 'active')->get();
        $services  = $this->getAvailableServices();

        return view('staff.print-requests.create', compact('customers', 'branches', 'services'));
    }

    /**
     * Store a newly created print request initiated by customer service staff.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'                 => ['nullable', 'exists:users,id'],
            'walk_in_name'            => ['nullable', 'string', 'max:150'],
            'walk_in_phone'           => ['nullable', 'string', 'max:50'],
            'walk_in_email'           => ['nullable', 'email', 'max:150'],
            'service'                 => ['required', 'string', 'max:100'],
            'quantity'                => ['required', 'integer', 'min:1'],
            'size'                    => ['required', 'string', 'max:50'],
            'material'                => ['required', 'string', 'max:100'],
            'finishing'               => ['required', 'string', 'max:100'],
            'deadline'                => ['required', 'date', 'after_or_equal:today'],
            'collection_mode'         => ['required', 'in:pickup,shipping'],
            'preferred_branch'        => ['nullable', 'string', 'max:100'],
            'additional_instructions' => ['nullable', 'string', 'max:2000'],
            'design_file'             => ['nullable', 'file', 'max:51200', 'mimes:pdf,eps,tiff,tif,jpg,jpeg,png,ai,zip'],
        ]);

        // Resolve or create customer account
        $userId = $data['user_id'] ?? null;
        if (!$userId) {
            $name = $data['walk_in_name'] ?: 'Walk-in Customer';
            $email = $data['walk_in_email'] ?: 'walkin_' . time() . '_' . rand(100, 999) . '@capaciprint.local';
            
            $customer = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'     => $name,
                    'password' => bcrypt('password'),
                    'role'     => 'customer',
                    'phone'    => $data['walk_in_phone'] ?? null,
                ]
            );
            $userId = $customer->id;
        }

        // Handle file upload
        $filePath = null;
        $fileName = null;
        $fileSize = null;

        if ($request->hasFile('design_file')) {
            $file     = $request->file('design_file');
            $filePath = $file->store('design-files/' . $userId, 'local');
            $fileName = $file->getClientOriginalName();
            $fileSize = $this->formatBytes($file->getSize());
        }

        $printRequest = PrintRequest::create([
            'user_id'                 => $userId,
            'service'                 => $data['service'],
            'quantity'                => $data['quantity'],
            'size'                    => $data['size'],
            'material'                => $data['material'],
            'finishing'               => $data['finishing'],
            'deadline'                => $data['deadline'],
            'collection_mode'         => $data['collection_mode'],
            'preferred_branch'        => $data['preferred_branch'] ?? null,
            'additional_instructions' => $data['additional_instructions'] ?? null,
            'design_file_path'        => $filePath,
            'design_file_name'        => $fileName,
            'design_file_size'        => $fileSize,
            'status'                  => PrintRequest::STATUS_SUBMITTED,
        ]);

        return redirect()->route('staff.print-requests.show', $printRequest)
            ->with('success', "New Print Request #REQ-{$printRequest->id} registered successfully by Customer Service.");
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

    // ── Private Helpers ──────────────────────────────────────────
    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        return round($bytes / 1024, 1) . ' KB';
    }

    private function getAvailableServices(): array
    {
        return [
            ['id' => 1,  'name' => 'Tarpaulin Printing',             'icon' => 'fa-solid fa-scroll'],
            ['id' => 2,  'name' => 'Indoor Sticker Printing',        'icon' => 'fa-solid fa-note-sticky'],
            ['id' => 3,  'name' => 'Outdoor Sticker Printing',       'icon' => 'fa-solid fa-water'],
            ['id' => 4,  'name' => 'Cut-Out Sticker Printing',       'icon' => 'fa-solid fa-scissors'],
            ['id' => 5,  'name' => 'Product Label Printing',         'icon' => 'fa-solid fa-tags'],
            ['id' => 6,  'name' => 'Risograph Printing',              'icon' => 'fa-solid fa-print'],
            ['id' => 7,  'name' => 'Receipt Printing',               'icon' => 'fa-solid fa-receipt'],
            ['id' => 8,  'name' => 'Invoice Printing',               'icon' => 'fa-solid fa-file-invoice-dollar'],
            ['id' => 9,  'name' => 'Prescription (Rx) Pad Printing', 'icon' => 'fa-solid fa-file-medical'],
            ['id' => 10, 'name' => 'Form Printing',                  'icon' => 'fa-solid fa-file-lines'],
            ['id' => 11, 'name' => 'Voucher Printing',               'icon' => 'fa-solid fa-ticket'],
            ['id' => 12, 'name' => 'Order Slip Printing',            'icon' => 'fa-solid fa-clipboard-list'],
            ['id' => 13, 'name' => 'Laser Printing',                 'icon' => 'fa-solid fa-copy'],
            ['id' => 14, 'name' => 'Poster Printing',                'icon' => 'fa-solid fa-image'],
            ['id' => 15, 'name' => 'Flyer Printing',                 'icon' => 'fa-solid fa-paper-plane'],
            ['id' => 16, 'name' => 'Calling Card Printing',          'icon' => 'fa-solid fa-address-card'],
            ['id' => 17, 'name' => 'Brochure Printing',              'icon' => 'fa-solid fa-book-open-reader'],
            ['id' => 18, 'name' => 'Bookbinding',                     'icon' => 'fa-solid fa-book'],
            ['id' => 19, 'name' => 'Lanyard Printing',               'icon' => 'fa-solid fa-id-card-clip'],
            ['id' => 20, 'name' => 'ID Sling Printing',              'icon' => 'fa-solid fa-ribbon'],
            ['id' => 21, 'name' => 'PVC ID Printing',                'icon' => 'fa-solid fa-id-card'],
            ['id' => 22, 'name' => 'Mug Printing',                   'icon' => 'fa-solid fa-mug-hot'],
            ['id' => 23, 'name' => 'Folded Fan Printing',            'icon' => 'fa-solid fa-fan'],
            ['id' => 24, 'name' => 'Invitation Printing',            'icon' => 'fa-solid fa-envelope-open-text'],
            ['id' => 25, 'name' => 'Souvenir Program Printing',      'icon' => 'fa-solid fa-newspaper'],
            ['id' => 26, 'name' => 'T-Shirt Printing – Silk Screen', 'icon' => 'fa-solid fa-shirt'],
            ['id' => 27, 'name' => 'T-Shirt Printing – Heat Press',  'icon' => 'fa-solid fa-fire'],
            ['id' => 28, 'name' => 'Sintra Board Printing',          'icon' => 'fa-solid fa-border-all'],
            ['id' => 29, 'name' => 'X-Stand Banner Printing',        'icon' => 'fa-solid fa-expand'],
            ['id' => 30, 'name' => 'Pull-Up Banner Printing',        'icon' => 'fa-solid fa-arrows-up-down'],
            ['id' => 31, 'name' => 'Panaflex Signage',               'icon' => 'fa-solid fa-store'],
        ];
    }
}
