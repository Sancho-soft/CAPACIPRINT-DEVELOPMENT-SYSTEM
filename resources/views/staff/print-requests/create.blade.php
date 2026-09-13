@extends('layouts.internal')
@section('title', 'New Print Request Intake — Customer Service')
@section('page-title', 'Customer Service Print Intake')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto" x-data="{ customerType: 'existing' }">

    {{-- Header Banner --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#0E3386]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="h-14 w-14 rounded-2xl bg-[#0E3386]/15 border border-[#0E3386]/30 text-[#0E3386] dark:text-sky-400 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">New Customer Print Request</h2>
                    <p class="text-xs text-cyber-muted mt-1">Record client technical specifications, dimensions, materials, and file assets at the service desk.</p>
                </div>
            </div>

            <a href="{{ route('staff.print-requests.index') }}" 
               class="px-4 py-2.5 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted hover:text-cyber-main font-bold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i> Back to Requests
            </a>
        </div>
    </div>

    {{-- Error Banner --}}
    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-600 dark:text-red-400 text-xs space-y-1">
            <div class="font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-sm"></i> Please correct the following errors:
            </div>
            <ul class="list-disc list-inside pl-4 text-[11px] space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Intake Form --}}
    <form method="POST" action="{{ route('staff.print-requests.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Section 1: Customer Information --}}
        <div class="bg-cyber-card border border-cyber rounded-3xl p-6 shadow-xl space-y-5">
            <div class="flex items-center justify-between border-b border-cyber pb-3">
                <h3 class="font-bold text-cyber-main text-sm uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-user-tag text-[#0E3386] dark:text-sky-400"></i> Customer Profile
                </h3>

                {{-- Toggle Customer Type --}}
                <div class="flex items-center p-1 bg-cyber-sub rounded-xl border border-cyber text-xs">
                    <button type="button" @click="customerType = 'existing'"
                            class="px-3 py-1 rounded-lg font-bold transition cursor-pointer"
                            :class="customerType === 'existing' ? 'bg-[#0E3386] text-white shadow-xs' : 'text-cyber-muted hover:text-cyber-main'">
                        Registered Customer
                    </button>
                    <button type="button" @click="customerType = 'walk_in'"
                            class="px-3 py-1 rounded-lg font-bold transition cursor-pointer"
                            :class="customerType === 'walk_in' ? 'bg-[#0E3386] text-white shadow-xs' : 'text-cyber-muted hover:text-cyber-main'">
                        Walk-in / New Client
                    </button>
                </div>
            </div>

            {{-- Existing Customer Select --}}
            <div x-show="customerType === 'existing'" class="space-y-2">
                <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider">Select Registered Customer</label>
                <select name="user_id" class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main focus:border-[#0E3386] focus:outline-none">
                    <option value="">-- Choose Existing Client --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ old('user_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->email }} &middot; {{ $c->phone ?? 'No phone' }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Walk-in Customer Fields --}}
            <div x-show="customerType === 'walk_in'" class="grid grid-cols-1 sm:grid-cols-3 gap-4" x-cloak>
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Client Full Name *</label>
                    <input type="text" name="walk_in_name" value="{{ old('walk_in_name') }}" placeholder="e.g. Maria Santos"
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main placeholder-cyber-muted/60 focus:border-[#0E3386] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Phone / Mobile</label>
                    <input type="text" name="walk_in_phone" value="{{ old('walk_in_phone') }}" placeholder="+63 917 123 4567"
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main placeholder-cyber-muted/60 focus:border-[#0E3386] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Email Address</label>
                    <input type="email" name="walk_in_email" value="{{ old('walk_in_email') }}" placeholder="client@example.com"
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main placeholder-cyber-muted/60 focus:border-[#0E3386] focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Section 2: Technical Specifications --}}
        <div class="bg-cyber-card border border-cyber rounded-3xl p-6 shadow-xl space-y-5">
            <div class="border-b border-cyber pb-3">
                <h3 class="font-bold text-cyber-main text-sm uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-[#0E3386] dark:text-sky-400"></i> Print Specifications &amp; Sizing
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Service Type --}}
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Service Category *</label>
                    <select name="service" required class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main focus:border-[#0E3386] focus:outline-none">
                        <option value="">-- Choose Print Service --</option>
                        @foreach($services as $s)
                            <option value="{{ $s['name'] }}" {{ old('service') === $s['name'] ? 'selected' : '' }}>
                                {{ $s['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Quantity --}}
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Quantity (Units / Copies) *</label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity', 100) }}" required
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs font-mono text-cyber-main focus:border-[#0E3386] focus:outline-none">
                </div>

                {{-- Size --}}
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Dimensions / Size *</label>
                    <input type="text" name="size" value="{{ old('size', 'Standard') }}" required placeholder="e.g. A4 (8.27 x 11.69 in), 3x4 ft, 2x3.5 in"
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main focus:border-[#0E3386] focus:outline-none">
                </div>

                {{-- Material --}}
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Substrate / Material *</label>
                    <input type="text" name="material" value="{{ old('material', 'Standard Coated Stock') }}" required placeholder="e.g. 13oz Tarpaulin, Glossy Vinyl Sticker, C2S 220gsm"
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main focus:border-[#0E3386] focus:outline-none">
                </div>

                {{-- Finishing --}}
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Finishing &amp; Binding *</label>
                    <input type="text" name="finishing" value="{{ old('finishing', 'None / Standard Cut') }}" required placeholder="e.g. Eyelets on 4 corners, Matte Lamination, Saddle Stitch"
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main focus:border-[#0E3386] focus:outline-none">
                </div>

                {{-- Required Completion Deadline --}}
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Required Target Deadline *</label>
                    <input type="date" name="deadline" value="{{ old('deadline', now()->addDays(3)->format('Y-m-d')) }}" required
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main focus:border-[#0E3386] focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Section 3: Routing, Fulfillment & File Assets --}}
        <div class="bg-cyber-card border border-cyber rounded-3xl p-6 shadow-xl space-y-5">
            <div class="border-b border-cyber pb-3">
                <h3 class="font-bold text-cyber-main text-sm uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-truck-ramp-box text-[#0E3386] dark:text-sky-400"></i> Routing, Delivery &amp; Artwork
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Collection Mode --}}
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Fulfillment Mode *</label>
                    <select name="collection_mode" required class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main focus:border-[#0E3386] focus:outline-none">
                        <option value="pickup" {{ old('collection_mode') === 'pickup' ? 'selected' : '' }}>Store Pick-up (On-site Counter)</option>
                        <option value="shipping" {{ old('collection_mode') === 'shipping' ? 'selected' : '' }}>Courier Delivery / Shipping</option>
                    </select>
                </div>

                {{-- Preferred Branch --}}
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Fulfillment Facility / Branch</label>
                    <select name="preferred_branch" class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main focus:border-[#0E3386] focus:outline-none">
                        <option value="">-- Intelligent Multi-Branch Routing (Automatic) --</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->name }}" {{ old('preferred_branch') === $b->name ? 'selected' : '' }}>
                                {{ $b->name }} ({{ $b->location }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Artwork Upload --}}
            <div>
                <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Attach Customer Design / Artwork File</label>
                <input type="file" name="design_file" 
                       class="w-full text-xs text-cyber-muted file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0E3386] file:text-white hover:file:bg-[#1442a8] border border-cyber rounded-xl bg-cyber-sub p-1">
                <span class="text-[10px] text-cyber-muted block mt-1">Accepted formats: PDF, AI, EPS, PSD, TIFF, PNG, JPG, ZIP (Max 50MB)</span>
            </div>

            {{-- Instructions --}}
            <div>
                <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Special Customer Instructions / Notes</label>
                <textarea name="additional_instructions" rows="3" placeholder="Enter special color matching notes, packaging details, or deadline urgency..." 
                          class="w-full rounded-xl border border-cyber bg-cyber-sub p-3 text-xs text-cyber-main placeholder-cyber-muted/60 focus:border-[#0E3386] focus:outline-none">{{ old('additional_instructions') }}</textarea>
            </div>
        </div>

        {{-- Submission Toolbar --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('staff.print-requests.index') }}" 
               class="px-5 py-3 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted hover:text-cyber-main font-bold text-xs transition">
                Cancel
            </a>
            <button type="submit" 
                    class="px-7 py-3 rounded-xl bg-[#0E3386] hover:bg-[#1442a8] text-white font-bold text-xs shadow-lg shadow-[#0E3386]/25 transition flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-check text-xs"></i> Register Print Request
            </button>
        </div>
    </form>

</div>
@endsection
