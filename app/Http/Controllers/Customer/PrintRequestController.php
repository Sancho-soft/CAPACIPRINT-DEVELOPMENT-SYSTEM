<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PrintRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrintRequestController extends Controller
{
    /**
     * Show all print requests for the authenticated customer.
     */
    public function index(Request $request)
    {
        $printRequests = $request->user()
            ->printRequests()
            ->latest()
            ->paginate(10);

        return view('customer.print-requests.index', compact('printRequests'));
    }

    /**
     * Show the new print request form (5-step wizard).
     */
    public function create()
    {
        $services = $this->getAvailableServices();
        return view('customer.print-requests.create', compact('services'));
    }

    /**
     * Store a new print request submitted by the customer.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'service'                  => ['required', 'string', 'max:100'],
            'quantity'                 => ['required', 'integer', 'min:1'],
            'size'                     => ['required', 'string', 'max:50'],
            'material'                 => ['required', 'string', 'max:100'],
            'finishing'                => ['required', 'string', 'max:100'],
            'deadline'                 => ['required', 'date', 'after:today'],
            'collection_mode'          => ['required', 'in:pickup,shipping'],
            'preferred_branch'         => ['nullable', 'string', 'max:100'],
            'additional_instructions'  => ['nullable', 'string', 'max:2000'],
            'design_file'              => ['nullable', 'file', 'max:51200', 'mimes:pdf,eps,tiff,tif,jpg,jpeg,png,ai'],
        ]);

        // Handle file upload
        $filePath = null;
        $fileName = null;
        $fileSize = null;

        if ($request->hasFile('design_file')) {
            $file     = $request->file('design_file');
            $filePath = $file->store('design-files/' . $request->user()->id, 'local');
            $fileName = $file->getClientOriginalName();
            $fileSize = $this->formatBytes($file->getSize());
        }

        $printRequest = $request->user()->printRequests()->create([
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

        return redirect()->route('customer.print-requests.show', $printRequest)
            ->with('success', 'Your print request has been submitted successfully!');
    }

    /**
     * Show a single print request (own only).
     */
    public function show(Request $request, PrintRequest $printRequest)
    {
        // Ownership check
        if ($printRequest->user_id !== $request->user()->id) {
            abort(403);
        }

        $printRequest->load(['quotation', 'order.payment', 'order.claimReference']);
        return view('customer.print-requests.show', compact('printRequest'));
    }

    /**
     * Cancel a print request (only if still submitted).
     */
    public function cancel(Request $request, PrintRequest $printRequest)
    {
        if ($printRequest->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($printRequest->status !== PrintRequest::STATUS_SUBMITTED) {
            return back()->with('error', 'You cannot cancel a request that is already in progress.');
        }

        $printRequest->update(['status' => 'cancelled']);

        return redirect()->route('customer.print-requests.index')
            ->with('success', 'Print request cancelled.');
    }

    // ── Helpers ────────────────────────────────────────────────

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
            ['id' => 1,  'name' => 'Tarpaulin Printing',                 'icon' => 'fa-solid fa-scroll',              'description' => 'High-resolution outdoor & indoor tarpaulin banners and event backdrops.'],
            ['id' => 2,  'name' => 'Indoor Sticker Printing',            'icon' => 'fa-solid fa-note-sticky',         'description' => 'Custom indoor paper stickers, glossy labels, and indoor decals.'],
            ['id' => 3,  'name' => 'Outdoor Sticker Printing',           'icon' => 'fa-solid fa-water',               'description' => 'Weatherproof vinyl stickers, car decals, and durable outdoor labels.'],
            ['id' => 4,  'name' => 'Cut-Out Sticker Printing',           'icon' => 'fa-solid fa-scissors',            'description' => 'Precision die-cut and kiss-cut vinyl stickers and custom logo decals.'],
            ['id' => 5,  'name' => 'Product Label Printing',             'icon' => 'fa-solid fa-tags',                'description' => 'Custom packaging product labels, bottle stickers, and brand seals.'],
            ['id' => 6,  'name' => 'Risograph Printing',                  'icon' => 'fa-solid fa-print',               'description' => 'High-volume duplicate printing for documents, forms, and flyers.'],
            ['id' => 7,  'name' => 'Receipt Printing',                   'icon' => 'fa-solid fa-receipt',             'description' => 'Official receipts, carbonless duplicate receipts, and sales slips.'],
            ['id' => 8,  'name' => 'Invoice Printing',                   'icon' => 'fa-solid fa-file-invoice-dollar', 'description' => 'Official commercial invoices, billing statements, and collection books.'],
            ['id' => 9,  'name' => 'Prescription (Rx) Pad Printing',     'icon' => 'fa-solid fa-file-medical',        'description' => 'Customized medical prescription pads for clinics and physicians.'],
            ['id' => 10, 'name' => 'Form Printing',                      'icon' => 'fa-solid fa-file-lines',          'description' => 'Corporate forms, application forms, checklists, and log sheets.'],
            ['id' => 11, 'name' => 'Voucher Printing',                   'icon' => 'fa-solid fa-ticket',              'description' => 'Gift vouchers, discount coupons, promo cards, and serialized tickets.'],
            ['id' => 12, 'name' => 'Order Slip Printing',                'icon' => 'fa-solid fa-clipboard-list',      'description' => 'Job order slips, kitchen order tickets, and delivery receipts.'],
            ['id' => 13, 'name' => 'Laser Printing',                     'icon' => 'fa-solid fa-copy',                'description' => 'Crisp high-speed digital laser printing for documents & reports.'],
            ['id' => 14, 'name' => 'Poster Printing',                    'icon' => 'fa-solid fa-image',               'description' => 'Vibrant promotional posters, event displays, and decorative prints.'],
            ['id' => 15, 'name' => 'Flyer Printing',                     'icon' => 'fa-solid fa-paper-plane',         'description' => 'Marketing flyers, promotional handouts, and single-sheet leaflets.'],
            ['id' => 16, 'name' => 'Calling Card Printing',              'icon' => 'fa-solid fa-address-card',        'description' => 'Professional business cards with matte, gloss, or velvet lamination.'],
            ['id' => 17, 'name' => 'Brochure Printing',                  'icon' => 'fa-solid fa-book-open-reader',    'description' => 'Tri-fold, bi-fold, and multi-page marketing brochures & catalogs.'],
            ['id' => 18, 'name' => 'Bookbinding',                         'icon' => 'fa-solid fa-book',                'description' => 'Hardbound, softbound, coil, wire-o, and saddle-stitch bookbinding.'],
            ['id' => 19, 'name' => 'Lanyard Printing',                   'icon' => 'fa-solid fa-id-card-clip',        'description' => 'Custom full-color sublimated lanyards and neck straps for events.'],
            ['id' => 20, 'name' => 'ID Sling Printing',                  'icon' => 'fa-solid fa-ribbon',              'description' => 'Custom printed ID slings, badge reels, and lanyard accessories.'],
            ['id' => 21, 'name' => 'PVC ID Printing',                    'icon' => 'fa-solid fa-id-card',             'description' => 'Durable plastic PVC identification cards, student & employee IDs.'],
            ['id' => 22, 'name' => 'Mug Printing',                       'icon' => 'fa-solid fa-mug-hot',             'description' => 'Customized ceramic mugs, magic mugs, and promotional drinkware.'],
            ['id' => 23, 'name' => 'Folded Fan Printing',                'icon' => 'fa-solid fa-fan',                 'description' => 'Custom plastic folded fans and promotional giveaway fans.'],
            ['id' => 24, 'name' => 'Invitation Printing',                'icon' => 'fa-solid fa-envelope-open-text',  'description' => 'Wedding invitations, birthday cards, and formal event invites.'],
            ['id' => 25, 'name' => 'Souvenir Program Printing',          'icon' => 'fa-solid fa-newspaper',           'description' => 'Event souvenir programs, souvenir booklets, and commemorative books.'],
            ['id' => 26, 'name' => 'T-Shirt Printing – Silk Screen',     'icon' => 'fa-solid fa-shirt',               'description' => 'Traditional silk screen printing for bulk t-shirts, jerseys, & uniforms.'],
            ['id' => 27, 'name' => 'T-Shirt Printing – Heat Press',      'icon' => 'fa-solid fa-fire',                'description' => 'Full-color vinyl & DTF heat press custom t-shirt printing.'],
            ['id' => 28, 'name' => 'Sintra Board Printing',              'icon' => 'fa-solid fa-border-all',          'description' => 'Rigid Sintra board standees, wall mounts, and photo panels.'],
            ['id' => 29, 'name' => 'X-Stand Banner Printing',            'icon' => 'fa-solid fa-expand',              'description' => 'Portable X-stand banners and promotional display stands.'],
            ['id' => 30, 'name' => 'Pull-Up Banner Printing',            'icon' => 'fa-solid fa-arrows-up-down',      'description' => 'Retractable pull-up roll-up banners with aluminum stand.'],
            ['id' => 31, 'name' => 'Panaflex Signage',                   'icon' => 'fa-solid fa-store',               'description' => 'Illuminated Panaflex signboards and store canopy signages.'],
            ['id' => 32, 'name' => 'Acrylic Signage',                    'icon' => 'fa-solid fa-gem',                 'description' => 'Custom 3D acrylic signages, laser-cut acrylic logo signboards.'],
        ];
    }
}
