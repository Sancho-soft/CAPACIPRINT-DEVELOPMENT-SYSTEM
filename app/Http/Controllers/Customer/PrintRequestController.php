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
            ['id' => 1,  'name' => 'Tarpaulin Printing',                 'icon' => 'fa-solid fa-scroll',             'description' => 'High-resolution outdoor & indoor tarpaulin banners and event backdrops.'],
            ['id' => 2,  'name' => 'Sticker (Product Label)',             'icon' => 'fa-solid fa-tag',                'description' => 'Indoor & outdoor stickers, product decals, and precision cut-outs.'],
            ['id' => 3,  'name' => 'Risograph Printing',                  'icon' => 'fa-solid fa-file-invoice',       'description' => 'Receipts, invoices, Rx pads, official forms, vouchers, and order slips.'],
            ['id' => 4,  'name' => 'Laser Printing / Poster / Flyers',    'icon' => 'fa-solid fa-print',              'description' => 'High-speed digital laser prints, promotional flyers, and posters.'],
            ['id' => 5,  'name' => 'Calling Card / Brochure',             'icon' => 'fa-solid fa-address-card',       'description' => 'Premium business cards, multi-fold brochures, and corporate stationery.'],
            ['id' => 6,  'name' => 'Bookbinding',                         'icon' => 'fa-solid fa-book-open',          'description' => 'Hardbound, softbound, coil, wire-o, and saddle-stitch bookbinding.'],
            ['id' => 7,  'name' => 'Lanyard / I.D. Sling / PVC I.D',     'icon' => 'fa-solid fa-id-badge',           'description' => 'Custom printed lanyards, ID slings, and durable PVC identification cards.'],
            ['id' => 8,  'name' => 'Mug / Tumbler / Eco Bag / Fan',       'icon' => 'fa-solid fa-mug-hot',            'description' => 'Customized mugs, tumblers, eco bags, folded fans, and promotional items.'],
            ['id' => 9,  'name' => 'Invitation / Souvenir Program',       'icon' => 'fa-solid fa-envelope-open-text', 'description' => 'Event invitations, wedding cards, souvenir programs, and greeting cards.'],
            ['id' => 10, 'name' => 'T-Shirt Printing',                    'icon' => 'fa-solid fa-shirt',              'description' => 'Silkscreen and heat press custom t-shirt printing for uniforms and events.'],
            ['id' => 11, 'name' => 'Sintra Board',                        'icon' => 'fa-solid fa-border-all',         'description' => 'Rigid Sintra board standees, wall mounts, and photo panels.'],
            ['id' => 12, 'name' => 'Banners (X-Stand / Pull Up)',        'icon' => 'fa-solid fa-expand',             'description' => 'Portable X-stand banners, retractable pull-up banners, and display stands.'],
            ['id' => 13, 'name' => 'Signage (Panaflex / Acrylic)',        'icon' => 'fa-solid fa-store',              'description' => 'Panaflex illuminated signages, acrylic cutouts, and 3D shopfront signboards.'],
        ];
    }
}
