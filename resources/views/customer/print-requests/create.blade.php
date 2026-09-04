@extends('layouts.customer')
@section('title', 'New Print Request')
@section('page-title', 'New Print Request')

@section('content')
<div class="max-w-4xl mx-auto" x-data="printWizard()">

    <div class="mb-6">
        <h2 class="text-2xl font-black text-cyber-main font-display">Submit Print Request</h2>
        <p class="text-sm text-cyber-muted mt-1">Fill in the details below and upload your artwork to get started.</p>
    </div>

    {{-- ── Step Indicators ─────────────────────────────── --}}
    <div class="mb-8 bg-cyber-card border border-cyber p-5 rounded-2xl shadow-sm">
        <div class="flex items-center justify-between relative">
            <template x-for="(label, i) in steps" :key="i">
                <div class="flex-1 flex flex-col items-center relative">
                    {{-- Connector line between steps --}}
                    <template x-if="i < steps.length - 1">
                        <div class="absolute left-1/2 top-4 w-full h-[3px] -translate-y-1/2 z-0">
                            <div class="w-full h-full transition-colors duration-300"
                                 :class="step > i + 1 ? 'bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.5)]' : 'bg-slate-300 dark:bg-slate-800'"></div>
                        </div>
                    </template>

                    {{-- Step Circle Indicator --}}
                    <div class="relative z-10 h-8 w-8 rounded-full border-2 flex items-center justify-center text-xs font-bold transition-all duration-300 shadow-sm"
                         :class="step === i+1 ? 'border-cyan-400 bg-cyan-500 text-slate-950 ring-4 ring-cyan-500/25 font-black scale-110'
                               : (step > i+1  ? 'border-cyan-500 bg-cyan-500 text-slate-950 font-bold'
                               : 'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-400 dark:text-slate-500')">
                        <template x-if="step > i+1"><i class="fa-solid fa-check text-[10px]"></i></template>
                        <template x-if="step <= i+1"><span x-text="i+1"></span></template>
                    </div>

                    {{-- Step Label --}}
                    <span class="text-[10px] font-bold uppercase tracking-wider mt-2.5 text-center transition-colors" 
                          :class="step === i+1 ? 'text-cyan-500 dark:text-cyan-400 font-black' : 'text-slate-500 dark:text-slate-400'" 
                          x-text="label"></span>
                </div>
            </template>
        </div>
    </div>

    {{-- ── Form Card ────────────────────────────────────── --}}
    <form method="POST" action="{{ route('customer.print-requests.store') }}" enctype="multipart/form-data" id="print-request-form">
        @csrf

        <div class="bg-cyber-card border border-cyber rounded-2xl shadow-xl p-6 sm:p-8 min-h-[400px]">

            {{-- STEP 1: SERVICE --}}
            <div x-show="step === 1" class="space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-cyber-main">Select Print Service</h3>
                        <p class="text-xs text-cyber-muted mt-0.5">Choose the service that matches your product type.</p>
                    </div>
                </div>

                {{-- Search & Category Filter Toolbar --}}
                <div class="space-y-3 bg-cyber-sub/30 border border-cyber p-4 rounded-2xl">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                        
                        {{-- Live Search Input --}}
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyber-muted">
                                <i class="fa-solid fa-magnifying-glass text-xs text-cyan-400"></i>
                            </div>
                            <input type="text" x-model="searchQuery" placeholder="Search service... (e.g. sticker, banner, t-shirt, receipt)"
                                   class="block w-full rounded-xl border border-cyber bg-cyber-sub pl-9 pr-9 py-2 text-xs font-medium text-cyber-main placeholder-cyber-muted/60 focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                            <button type="button" x-show="searchQuery" @click="searchQuery = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-cyber-muted hover:text-cyan-400 transition cursor-pointer">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>

                        {{-- Service Counter --}}
                        <div class="text-[11px] font-bold text-cyber-muted shrink-0 flex items-center gap-1.5 px-1 self-end sm:self-center">
                            <span>Showing:</span>
                            <span class="text-cyan-400 font-extrabold" x-text="filteredServicesCount"></span> / 32
                        </div>
                    </div>

                    {{-- Category Filter Pills --}}
                    <div class="flex flex-wrap items-center gap-1.5 pt-1 border-t border-cyber/50">
                        <button type="button" @click="activeCategory = 'all'"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer border"
                                :class="activeCategory === 'all' ? 'bg-cyan-500 text-slate-950 border-cyan-400 shadow-md font-extrabold' : 'bg-cyber-sub border-cyber text-cyber-muted hover:text-cyber-main hover:border-cyan-500/40'">
                            <i class="fa-solid fa-layer-group text-[10px]"></i> All (32)
                        </button>
                        <button type="button" @click="activeCategory = 'banners'"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer border"
                                :class="activeCategory === 'banners' ? 'bg-cyan-500 text-slate-950 border-cyan-400 shadow-md font-extrabold' : 'bg-cyber-sub border-cyber text-cyber-muted hover:text-cyber-main hover:border-cyan-500/40'">
                            <i class="fa-solid fa-scroll text-[10px]"></i> Banners & Signs (6)
                        </button>
                        <button type="button" @click="activeCategory = 'stickers'"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer border"
                                :class="activeCategory === 'stickers' ? 'bg-cyan-500 text-slate-950 border-cyan-400 shadow-md font-extrabold' : 'bg-cyber-sub border-cyber text-cyber-muted hover:text-cyber-main hover:border-cyan-500/40'">
                            <i class="fa-solid fa-tags text-[10px]"></i> Stickers & Labels (4)
                        </button>
                        <button type="button" @click="activeCategory = 'forms'"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer border"
                                :class="activeCategory === 'forms' ? 'bg-cyan-500 text-slate-950 border-cyan-400 shadow-md font-extrabold' : 'bg-cyber-sub border-cyber text-cyber-muted hover:text-cyber-main hover:border-cyan-500/40'">
                            <i class="fa-solid fa-file-invoice text-[10px]"></i> Forms & Receipts (7)
                        </button>
                        <button type="button" @click="activeCategory = 'marketing'"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer border"
                                :class="activeCategory === 'marketing' ? 'bg-cyan-500 text-slate-950 border-cyan-400 shadow-md font-extrabold' : 'bg-cyber-sub border-cyber text-cyber-muted hover:text-cyber-main hover:border-cyan-500/40'">
                            <i class="fa-solid fa-address-card text-[10px]"></i> Marketing & Cards (8)
                        </button>
                        <button type="button" @click="activeCategory = 'apparel'"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer border"
                                :class="activeCategory === 'apparel' ? 'bg-cyan-500 text-slate-950 border-cyan-400 shadow-md font-extrabold' : 'bg-cyber-sub border-cyber text-cyber-muted hover:text-cyber-main hover:border-cyan-500/40'">
                            <i class="fa-solid fa-shirt text-[10px]"></i> Apparel & Promo (7)
                        </button>
                    </div>
                </div>

                {{-- Service Cards Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                    $services = [
                        ['name'=>'Tarpaulin Printing',                 'icon'=>'fa-scroll',             'cat'=>'banners',   'desc'=>'High-resolution outdoor & indoor tarpaulin banners and event backdrops.'],
                        ['name'=>'Indoor Sticker Printing',            'icon'=>'fa-note-sticky',        'cat'=>'stickers',  'desc'=>'Custom indoor paper stickers, glossy labels, and indoor decals.'],
                        ['name'=>'Outdoor Sticker Printing',           'icon'=>'fa-water',              'cat'=>'stickers',  'desc'=>'Weatherproof vinyl stickers, car decals, and durable outdoor labels.'],
                        ['name'=>'Cut-Out Sticker Printing',           'icon'=>'fa-scissors',           'cat'=>'stickers',  'desc'=>'Precision die-cut and kiss-cut vinyl stickers and custom logo decals.'],
                        ['name'=>'Product Label Printing',             'icon'=>'fa-tags',               'cat'=>'stickers',  'desc'=>'Custom packaging product labels, bottle stickers, and brand seals.'],
                        ['name'=>'Risograph Printing',                  'icon'=>'fa-print',              'cat'=>'forms',     'desc'=>'High-volume duplicate printing for documents, forms, and flyers.'],
                        ['name'=>'Receipt Printing',                   'icon'=>'fa-receipt',            'cat'=>'forms',     'desc'=>'Official receipts, carbonless duplicate receipts, and sales slips.'],
                        ['name'=>'Invoice Printing',                   'icon'=>'fa-file-invoice-dollar','cat'=>'forms',     'desc'=>'Official commercial invoices, billing statements, and collection books.'],
                        ['name'=>'Prescription (Rx) Pad Printing',     'icon'=>'fa-file-medical',       'cat'=>'forms',     'desc'=>'Customized medical prescription pads for clinics and physicians.'],
                        ['name'=>'Form Printing',                      'icon'=>'fa-file-lines',         'cat'=>'forms',     'desc'=>'Corporate forms, application forms, checklists, and log sheets.'],
                        ['name'=>'Voucher Printing',                   'icon'=>'fa-ticket',             'cat'=>'forms',     'desc'=>'Gift vouchers, discount coupons, promo cards, and serialized tickets.'],
                        ['name'=>'Order Slip Printing',                'icon'=>'fa-clipboard-list',     'cat'=>'forms',     'desc'=>'Job order slips, kitchen order tickets, and delivery receipts.'],
                        ['name'=>'Laser Printing',                     'icon'=>'fa-copy',               'cat'=>'marketing', 'desc'=>'Crisp high-speed digital laser printing for documents & reports.'],
                        ['name'=>'Poster Printing',                    'icon'=>'fa-image',              'cat'=>'marketing', 'desc'=>'Vibrant promotional posters, event displays, and decorative prints.'],
                        ['name'=>'Flyer Printing',                     'icon'=>'fa-paper-plane',        'cat'=>'marketing', 'desc'=>'Marketing flyers, promotional handouts, and single-sheet leaflets.'],
                        ['name'=>'Calling Card Printing',              'icon'=>'fa-address-card',       'cat'=>'marketing', 'desc'=>'Professional business cards with matte, gloss, or velvet lamination.'],
                        ['name'=>'Brochure Printing',                  'icon'=>'fa-book-open-reader',   'cat'=>'marketing', 'desc'=>'Tri-fold, bi-fold, and multi-page marketing brochures & catalogs.'],
                        ['name'=>'Bookbinding',                         'icon'=>'fa-book',               'cat'=>'marketing', 'desc'=>'Hardbound, softbound, coil, wire-o, and saddle-stitch bookbinding.'],
                        ['name'=>'Lanyard Printing',                   'icon'=>'fa-id-card-clip',       'cat'=>'apparel',   'desc'=>'Custom full-color sublimated lanyards and neck straps for events.'],
                        ['name'=>'ID Sling Printing',                  'icon'=>'fa-ribbon',             'cat'=>'apparel',   'desc'=>'Custom printed ID slings, badge reels, and lanyard accessories.'],
                        ['name'=>'PVC ID Printing',                    'icon'=>'fa-id-card',            'cat'=>'apparel',   'desc'=>'Durable plastic PVC identification cards, student & employee IDs.'],
                        ['name'=>'Mug Printing',                       'icon'=>'fa-mug-hot',            'cat'=>'apparel',   'desc'=>'Customized ceramic mugs, magic mugs, and promotional drinkware.'],
                        ['name'=>'Folded Fan Printing',                'icon'=>'fa-fan',                'cat'=>'apparel',   'desc'=>'Custom plastic folded fans and promotional giveaway fans.'],
                        ['name'=>'Invitation Printing',                'icon'=>'fa-envelope-open-text', 'cat'=>'marketing', 'desc'=>'Wedding invitations, birthday cards, and formal event invites.'],
                        ['name'=>'Souvenir Program Printing',          'icon'=>'fa-newspaper',          'cat'=>'marketing', 'desc'=>'Event souvenir programs, souvenir booklets, and commemorative books.'],
                        ['name'=>'T-Shirt Printing – Silk Screen',     'icon'=>'fa-shirt',              'cat'=>'apparel',   'desc'=>'Traditional silk screen printing for bulk t-shirts, jerseys, & uniforms.'],
                        ['name'=>'T-Shirt Printing – Heat Press',      'icon'=>'fa-fire',               'cat'=>'apparel',   'desc'=>'Full-color vinyl & DTF heat press custom t-shirt printing.'],
                        ['name'=>'Sintra Board Printing',              'icon'=>'fa-border-all',         'cat'=>'banners',   'desc'=>'Rigid Sintra board standees, wall mounts, and photo panels.'],
                        ['name'=>'X-Stand Banner Printing',            'icon'=>'fa-expand',             'cat'=>'banners',   'desc'=>'Portable X-stand banners and promotional display stands.'],
                        ['name'=>'Pull-Up Banner Printing',            'icon'=>'fa-arrows-up-down',     'cat'=>'banners',   'desc'=>'Retractable pull-up roll-up banners with aluminum stand.'],
                        ['name'=>'Panaflex Signage',                   'icon'=>'fa-store',              'cat'=>'banners',   'desc'=>'Illuminated Panaflex signboards and store canopy signages.'],
                        ['name'=>'Acrylic Signage',                    'icon'=>'fa-gem',                'cat'=>'banners',   'desc'=>'Custom 3D acrylic signages, laser-cut acrylic logo signboards.'],
                    ];
                    @endphp
                    @foreach($services as $svc)
                    <label x-show="matchesCategoryAndSearch('{{ addslashes($svc['name']) }}', '{{ addslashes($svc['desc']) }}', '{{ $svc['cat'] }}')"
                           x-transition:enter="transition ease-out duration-150"
                           x-transition:enter-start="opacity-0 scale-95"
                           x-transition:enter-end="opacity-100 scale-100"
                           class="border rounded-2xl p-4 cursor-pointer transition flex flex-col justify-between hover:border-cyan-500/50 bg-cyber-sub/40 hover:bg-cyber-sub/80 relative group h-full"
                           :class="form.service === '{{ addslashes($svc['name']) }}' ? 'border-cyan-500 bg-cyan-500/10 ring-2 ring-cyan-500/30 shadow-lg scale-[1.01]' : 'border-cyber'">
                        <input type="radio" name="service" value="{{ $svc['name'] }}" x-model="form.service" class="sr-only" required>
                        
                        <div class="flex items-start gap-3.5">
                            <div class="h-10 w-10 bg-cyber-sub text-cyan-400 border border-cyber rounded-xl flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform group-hover:border-cyan-400/50">
                                <i class="fa-solid {{ $svc['icon'] }}"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-1.5">
                                    <h4 class="font-bold text-cyber-main text-sm leading-snug group-hover:text-cyan-400 transition-colors">{{ $svc['name'] }}</h4>
                                    <template x-if="form.service === '{{ addslashes($svc['name']) }}'">
                                        <span class="text-cyan-400 text-sm shrink-0 bg-cyan-400/10 p-1 rounded-full"><i class="fa-solid fa-circle-check"></i></span>
                                    </template>
                                </div>
                                <p class="text-xs text-cyber-muted mt-1 leading-relaxed">{{ $svc['desc'] }}</p>
                            </div>
                        </div>
                    </label>
                    @endforeach

                    {{-- Empty Search State --}}
                    <div x-show="filteredServicesCount === 0" class="col-span-full py-12 text-center bg-cyber-sub/20 border border-dashed border-cyber rounded-2xl space-y-3">
                        <div class="h-12 w-12 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center mx-auto text-xl">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <div>
                            <p class="font-bold text-cyber-main text-sm">No matching print services found</p>
                            <p class="text-xs text-cyber-muted mt-1">Try adjusting your search query or select a different category filter.</p>
                        </div>
                        <button type="button" @click="searchQuery = ''; activeCategory = 'all'"
                                class="px-4 py-1.5 rounded-xl text-xs font-bold bg-cyber-sub border border-cyber hover:border-cyan-400 text-cyan-400 transition cursor-pointer">
                            Reset All Filters
                        </button>
                    </div>
                </div>
            </div>

            {{-- STEP 2: SPECIFICATIONS --}}
            <div x-show="step === 2" class="space-y-5">
                <div>
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-cyber-main">Print Specifications</h3>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-400 border border-cyan-500/30" x-text="form.service || 'General Printing'"></span>
                    </div>
                    <p class="text-xs text-cyber-muted mt-0.5">Specify dimensions, stock materials, and finishing options tailored for <span class="text-cyan-400 font-bold" x-text="form.service"></span>.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Quantity --}}
                    <div>
                        <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Quantity <span class="text-red-400">*</span></label>
                        <input type="number" name="quantity" x-model.number="form.quantity" min="1" required
                               class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                    </div>

                    {{-- Size / Dimensions --}}
                    <div>
                        <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Size / Dimensions <span class="text-red-400">*</span></label>
                        
                        {{-- Tarpaulin Printing Dynamic UI --}}
                        <template x-if="form.service === 'Tarpaulin Printing'">
                            <div class="space-y-3 bg-cyber-sub/30 border border-cyber p-3.5 rounded-2xl">
                                
                                {{-- Mode Toggle Tabs --}}
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="tarpMode = 'dimensions'; updateTarpSize()"
                                            class="flex-1 py-1.5 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer border"
                                            :class="tarpMode === 'dimensions' ? 'bg-cyan-500 text-slate-950 border-cyan-400 shadow-md font-extrabold' : 'bg-cyber-sub border-cyber text-cyber-muted hover:text-cyber-main'">
                                        <i class="fa-solid fa-calculator text-xs"></i> Dimension Entry
                                    </button>
                                    <button type="button" @click="tarpMode = 'custom'; updateTarpSize()"
                                            class="flex-1 py-1.5 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer border"
                                            :class="tarpMode === 'custom' ? 'bg-cyan-500 text-slate-950 border-cyan-400 shadow-md font-extrabold' : 'bg-cyber-sub border-cyber text-cyber-muted hover:text-cyber-main'">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i> Custom Text
                                    </button>
                                </div>

                                {{-- Dimensions Mode View --}}
                                <div x-show="tarpMode === 'dimensions'" class="space-y-3">
                                    {{-- Unit Selector Pills --}}
                                    <div class="flex items-center gap-1 bg-cyber-sub p-1 rounded-xl border border-cyber">
                                        <button type="button" @click="tarpUnit = 'FT'; updateTarpSize()"
                                                class="flex-1 py-1 rounded-lg text-[10px] font-bold uppercase transition cursor-pointer"
                                                :class="tarpUnit === 'FT' ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/40 font-black' : 'text-cyber-muted hover:text-cyber-main'">
                                            FEET (FT)
                                        </button>
                                        <button type="button" @click="tarpUnit = 'IN'; updateTarpSize()"
                                                class="flex-1 py-1 rounded-lg text-[10px] font-bold uppercase transition cursor-pointer"
                                                :class="tarpUnit === 'IN' ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/40 font-black' : 'text-cyber-muted hover:text-cyber-main'">
                                            INCHES (IN)
                                        </button>
                                        <button type="button" @click="tarpUnit = 'M'; updateTarpSize()"
                                                class="flex-1 py-1 rounded-lg text-[10px] font-bold uppercase transition cursor-pointer"
                                                :class="tarpUnit === 'M' ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/40 font-black' : 'text-cyber-muted hover:text-cyber-main'">
                                            METERS (M)
                                        </button>
                                    </div>

                                    {{-- Width & Height Input Row --}}
                                    <div class="flex items-center gap-2">
                                        <div class="relative flex-1">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-cyan-400">
                                                <i class="fa-solid fa-ruler-horizontal text-xs"></i>
                                            </div>
                                            <input type="number" step="0.1" min="0.1" x-model.number="tarpWidth" @input="updateTarpSize()"
                                                   class="block w-full rounded-xl border border-cyber bg-cyber-sub pl-8 pr-8 py-2 text-sm font-bold text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                                            <span class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-[10px] font-bold text-cyber-muted uppercase pointer-events-none" x-text="tarpUnit"></span>
                                        </div>

                                        <span class="text-cyber-muted font-bold text-base px-0.5">×</span>

                                        <div class="relative flex-1">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-cyan-400">
                                                <i class="fa-solid fa-ruler-vertical text-xs"></i>
                                            </div>
                                            <input type="number" step="0.1" min="0.1" x-model.number="tarpHeight" @input="updateTarpSize()"
                                                   class="block w-full rounded-xl border border-cyber bg-cyber-sub pl-8 pr-8 py-2 text-sm font-bold text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                                            <span class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-[10px] font-bold text-cyber-muted uppercase pointer-events-none" x-text="tarpUnit"></span>
                                        </div>
                                    </div>

                                    {{-- Calculated Area Badge --}}
                                    <div class="p-2.5 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold flex items-center justify-between">
                                        <span class="flex items-center gap-1.5 text-slate-300">
                                            <i class="fa-solid fa-vector-square text-cyan-400 text-xs"></i> Calculated Area:
                                        </span>
                                        <span class="font-black text-xs text-cyan-400 tracking-wider" x-text="calculatedAreaText"></span>
                                    </div>
                                </div>

                                {{-- Custom Text Mode View --}}
                                <div x-show="tarpMode === 'custom'">
                                    <input type="text" x-model="customTarpSize" @input="updateTarpSize()" placeholder="e.g. 2 x 3 ft, 4 x 6 ft, 3 x 5 ft"
                                           class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                                    <p class="text-[11px] text-cyber-muted mt-1">Specify custom size or special dimensions.</p>
                                </div>

                                <input type="hidden" name="size" x-model="form.size">
                            </div>
                        </template>

                        {{-- Standard / Dynamic Service Size Select Component --}}
                        <template x-if="form.service !== 'Tarpaulin Printing'">
                            <div class="space-y-2.5 bg-cyber-sub/30 border border-cyber p-3 rounded-2xl">
                                
                                {{-- Quick Mode Toggle Pills --}}
                                <div class="flex items-center gap-1.5 p-1 bg-cyber-sub rounded-xl border border-cyber">
                                    <button type="button" @click="sizeMode = 'preset'; updateCustomSizeValue()"
                                            class="flex-1 py-1.5 px-2.5 rounded-lg text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer"
                                            :class="sizeMode === 'preset' ? 'bg-cyan-500 text-slate-950 shadow font-extrabold' : 'text-cyber-muted hover:text-cyber-main'">
                                        <i class="fa-solid fa-list-check text-[10px]"></i> Standard Presets
                                    </button>
                                    <button type="button" @click="sizeMode = 'custom'; updateCustomSizeValue()"
                                            class="flex-1 py-1.5 px-2.5 rounded-lg text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer"
                                            :class="sizeMode === 'custom' ? 'bg-cyan-500 text-slate-950 shadow font-extrabold' : 'text-cyber-muted hover:text-cyber-main'">
                                        <i class="fa-solid fa-pen-to-square text-[10px]"></i> Manual Custom Entry
                                    </button>
                                </div>

                                {{-- Preset Mode View --}}
                                <div x-show="sizeMode === 'preset'">
                                    <select x-model="selectedSizeOption" @change="updateCustomSizeValue()" required
                                            class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm font-semibold text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                                        <template x-for="opt in sizeOptions" :key="opt">
                                            <option :value="opt" x-text="opt" :selected="selectedSizeOption === opt"></option>
                                        </template>
                                    </select>
                                </div>

                                {{-- Manual Custom Entry View --}}
                                <div x-show="sizeMode === 'custom'" class="space-y-1">
                                    <input type="text" x-model="customSizeText" @input="updateCustomSizeValue()"
                                           :placeholder="customSizePlaceholder"
                                           class="block w-full rounded-xl border border-cyan-400/60 bg-cyber-sub/90 px-3.5 py-2 text-xs font-bold text-cyber-main placeholder-cyber-muted/60 focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                                    <p class="text-[11px] text-cyan-400/90 font-medium">Type your exact dimensions or custom measurements above.</p>
                                </div>

                                <input type="hidden" name="size" x-model="form.size">
                            </div>
                        </template>
                    </div>

                    {{-- Material --}}
                    <div>
                        <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Material <span class="text-red-400">*</span></label>
                        
                        {{-- Tarp Materials --}}
                        <template x-if="form.service === 'Tarpaulin Printing'">
                            <select name="material" x-model="form.material" required
                                    class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm font-semibold text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                                <option value="10oz Standard Outdoor Tarpaulin">10oz Standard Outdoor Tarpaulin</option>
                                <option value="13oz Heavy Duty Glossy Tarpaulin">13oz Heavy Duty Glossy Tarpaulin</option>
                                <option value="15oz Blockout Premium Tarpaulin">15oz Blockout Premium Tarpaulin</option>
                                <option value="Perforated Mesh Tarpaulin">Perforated Mesh Tarpaulin</option>
                            </select>
                        </template>

                        {{-- Dynamic Service Materials --}}
                        <template x-if="form.service !== 'Tarpaulin Printing'">
                            <select name="material" x-model="form.material" required
                                    class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm font-semibold text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                                <template x-for="opt in materialOptions" :key="opt">
                                    <option :value="opt" x-text="opt" :selected="form.material === opt"></option>
                                </template>
                            </select>
                        </template>
                    </div>

                    {{-- Finishing --}}
                    <div>
                        <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Finishing <span class="text-red-400">*</span></label>
                        
                        {{-- Tarpaulin Printing Finishing --}}
                        <template x-if="form.service === 'Tarpaulin Printing'">
                            <select name="finishing" x-model="form.finishing" required
                                    class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm font-semibold text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                                <option value="Allowance with Eyelets">Allowance with Eyelets</option>
                                <option value="Allowance Only">Allowance Only</option>
                                <option value="With Eyelets Only">With Eyelets Only</option>
                                <option value="Folded Hemming with Eyelets">Folded Hemming with Eyelets</option>
                                <option value="None (Cut to Edge)">None (Cut to Edge)</option>
                            </select>
                        </template>

                        {{-- Dynamic Service Finishing --}}
                        <template x-if="form.service !== 'Tarpaulin Printing'">
                            <select name="finishing" x-model="form.finishing" required
                                    class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm font-semibold text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                                <template x-for="opt in finishingOptions" :key="opt">
                                    <option :value="opt" x-text="opt" :selected="form.finishing === opt"></option>
                                </template>
                            </select>
                        </template>
                    </div>
                </div>
            </div>

            {{-- STEP 3: ARTWORK UPLOAD --}}
            <div x-show="step === 3" class="space-y-5">
                <div>
                    <h3 class="text-lg font-bold text-cyber-main">Upload Artwork File</h3>
                    <p class="text-xs text-cyber-muted mt-0.5">Attach vector files or high-resolution print-ready documents.</p>
                </div>
                <div class="border-2 border-dashed border-cyber hover:border-cyan-400/60 rounded-2xl p-10 text-center transition cursor-pointer bg-cyber-sub/30"
                     @click="$refs.fileInput.click()" @dragover.prevent @drop.prevent="handleDrop($event)">
                    <input type="file" name="design_file" x-ref="fileInput" class="hidden"
                           accept=".pdf,.eps,.tiff,.tif,.jpg,.jpeg,.png,.ai" @change="handleFile($event)">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-cyan-400 mb-3 block"></i>
                    <p class="text-sm font-bold text-cyber-main">Drag &amp; drop or <span class="text-cyan-400 underline">browse</span></p>
                    <p class="text-xs text-cyber-muted mt-1">PDF, EPS, TIFF, JPG, PNG, AI — max 50 MB</p>
                </div>

                <template x-if="form.fileName">
                    <div class="p-4 bg-cyber-sub border border-cyber rounded-xl flex items-center gap-3">
                        <i class="fa-solid fa-file text-cyan-400 text-xl shrink-0"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-cyber-main truncate" x-text="form.fileName"></p>
                            <p class="text-xs text-cyber-muted" x-text="form.fileSize"></p>
                        </div>
                        <button type="button" @click="form.fileName=''; form.fileSize=''; $refs.fileInput.value=''"
                                class="text-cyber-muted hover:text-red-400 p-1.5 transition">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                </template>

                <p class="text-xs text-cyber-muted">File upload is optional. You may provide a cloud link in additional instructions if the file is too large.</p>
            </div>

            {{-- STEP 4: SCHEDULE --}}
            <div x-show="step === 4" class="space-y-5">
                <div>
                    <h3 class="text-lg font-bold text-cyber-main">Schedule &amp; Collection</h3>
                    <p class="text-xs text-cyber-muted mt-0.5">Choose your delivery requirements and branch preferences.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Deadline <span class="text-red-400">*</span></label>
                        <input type="date" name="deadline" x-model="form.deadline" required
                               :min="minDate"
                               class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                        <p class="text-[11px] text-cyber-muted mt-1">Minimum 2-day production window required.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Preferred Branch <span class="text-cyber-muted font-normal">(optional)</span></label>
                        <select name="preferred_branch" x-model="form.preferred_branch"
                                class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                            <option value="">System will recommend best branch</option>
                            <option value="Branch 1 – Main">Branch 1 – Main</option>
                            <option value="Branch 2 – North">Branch 2 – North</option>
                            <option value="Branch 3 – South">Branch 3 – South</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-2">Collection Mode <span class="text-red-400">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="border rounded-2xl p-4 text-center cursor-pointer transition bg-cyber-sub/40"
                               :class="form.collection_mode === 'pickup' ? 'border-cyan-500 bg-cyan-500/10 ring-1 ring-cyan-500/40' : 'border-cyber hover:border-cyan-500/30'">
                            <input type="radio" name="collection_mode" value="pickup" x-model="form.collection_mode" class="sr-only">
                            <i class="fa-solid fa-store text-cyan-400 text-xl mb-1 block"></i>
                            <span class="text-xs font-bold text-cyber-main">Branch Pickup</span>
                        </label>
                        <label class="border rounded-2xl p-4 text-center cursor-pointer transition bg-cyber-sub/40"
                               :class="form.collection_mode === 'shipping' ? 'border-cyan-500 bg-cyan-500/10 ring-1 ring-cyan-500/40' : 'border-cyber hover:border-cyan-500/30'">
                            <input type="radio" name="collection_mode" value="shipping" x-model="form.collection_mode" class="sr-only">
                            <i class="fa-solid fa-truck text-cyan-400 text-xl mb-1 block"></i>
                            <span class="text-xs font-bold text-cyber-main">Courier Shipping</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Additional Instructions</label>
                    <textarea name="additional_instructions" x-model="form.notes" rows="3" placeholder="Colour profile, bleed requirements, special handling notes..."
                              class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition resize-none"></textarea>
                </div>
            </div>

            {{-- STEP 5: REVIEW --}}
            <div x-show="step === 5" class="space-y-5">
                <div>
                    <h3 class="text-lg font-bold text-cyber-main">Review Your Request</h3>
                    <p class="text-xs text-cyber-muted mt-0.5">Please confirm all specifications before dispatching your request.</p>
                </div>
                <div class="border border-cyber rounded-2xl overflow-hidden shadow-sm">
                    <table class="min-w-full divide-y divide-cyber text-sm">
                        <tbody class="divide-y divide-cyber">
                            <tr class="bg-cyber-card">
                                <td class="px-5 py-3 font-bold text-cyber-muted bg-cyber-sub/40 w-1/3">Print Service</td>
                                <td class="px-5 py-3 font-bold text-cyber-main" x-text="form.service || '—'"></td>
                            </tr>
                            <tr class="bg-cyber-card">
                                <td class="px-5 py-3 font-bold text-cyber-muted bg-cyber-sub/40">Qty &amp; Size</td>
                                <td class="px-5 py-3 text-cyber-main font-medium" x-text="form.quantity + ' copies · ' + form.size"></td>
                            </tr>
                            <tr class="bg-cyber-card">
                                <td class="px-5 py-3 font-bold text-cyber-muted bg-cyber-sub/40">Material</td>
                                <td class="px-5 py-3 text-cyber-main font-medium" x-text="form.material"></td>
                            </tr>
                            <tr class="bg-cyber-card">
                                <td class="px-5 py-3 font-bold text-cyber-muted bg-cyber-sub/40">Finishing</td>
                                <td class="px-5 py-3 text-cyber-main font-medium" x-text="form.finishing"></td>
                            </tr>
                            <tr class="bg-cyber-card">
                                <td class="px-5 py-3 font-bold text-cyber-muted bg-cyber-sub/40">Artwork File</td>
                                <td class="px-5 py-3 text-cyber-main font-medium" x-text="form.fileName || 'No file selected'"></td>
                            </tr>
                            <tr class="bg-cyber-card">
                                <td class="px-5 py-3 font-bold text-cyber-muted bg-cyber-sub/40">Deadline</td>
                                <td class="px-5 py-3 text-cyber-main font-medium" x-text="form.deadline"></td>
                            </tr>
                            <tr class="bg-cyber-card">
                                <td class="px-5 py-3 font-bold text-cyber-muted bg-cyber-sub/40">Collection</td>
                                <td class="px-5 py-3 text-cyber-main font-medium" x-text="form.collection_mode === 'pickup' ? 'Branch Pickup' : 'Courier Shipping'"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-cyan-500/10 border border-cyan-500/20 rounded-2xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-cyan-400 text-lg shrink-0"></i>
                    <p class="text-xs text-slate-300">
                        A formal quotation will be generated after automated capacity &amp; routing analysis and sent to your account dashboard.
                    </p>
                </div>
            </div>
        </div>

        {{-- ── Navigation Buttons ─────────────────────────── --}}
        <div class="flex items-center justify-between mt-6">
            <button type="button" @click="prevStep" x-show="step > 1"
                    class="border border-cyber bg-cyber-card hover:bg-cyber-sub text-cyber-main px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Back
            </button>
            <div x-show="step === 1"></div>

            <template x-if="step < 5">
                <button type="button" @click="nextStep"
                        class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider transition shadow-[0_0_15px_rgba(6,182,212,0.3)] flex items-center gap-2 cursor-pointer">
                    Continue <i class="fa-solid fa-arrow-right"></i>
                </button>
            </template>
            <template x-if="step === 5">
                <button type="submit"
                        class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-black px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider transition shadow-lg flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-paper-plane"></i> Submit Print Request
                </button>
            </template>
        </div>
    </form>
</div>

@section('scripts')
<script>
function printWizard() {
    const tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate() + 2);
    return {
        step: 1,
        steps: ['Service', 'Specifications', 'Artwork', 'Schedule', 'Review'],
        minDate: tomorrow.toISOString().split('T')[0],

        searchQuery: '',
        activeCategory: 'all',

        tarpMode: 'dimensions',
        tarpUnit: 'FT',
        tarpWidth: 2.3,
        tarpHeight: 3,
        customTarpSize: '',

        sizeMode: 'preset',
        selectedSizeOption: '',
        customSizeText: '',

        servicesMasterList: [
            { name: 'Tarpaulin Printing', cat: 'banners', desc: 'High-resolution outdoor & indoor tarpaulin banners and event backdrops.' },
            { name: 'Indoor Sticker Printing', cat: 'stickers', desc: 'Custom indoor paper stickers, glossy labels, and indoor decals.' },
            { name: 'Outdoor Sticker Printing', cat: 'stickers', desc: 'Weatherproof vinyl stickers, car decals, and durable outdoor labels.' },
            { name: 'Cut-Out Sticker Printing', cat: 'stickers', desc: 'Precision die-cut and kiss-cut vinyl stickers and custom logo decals.' },
            { name: 'Product Label Printing', cat: 'stickers', desc: 'Custom packaging product labels, bottle stickers, and brand seals.' },
            { name: 'Risograph Printing', cat: 'forms', desc: 'High-volume duplicate printing for documents, forms, and flyers.' },
            { name: 'Receipt Printing', cat: 'forms', desc: 'Official receipts, carbonless duplicate receipts, and sales slips.' },
            { name: 'Invoice Printing', cat: 'forms', desc: 'Official commercial invoices, billing statements, and collection books.' },
            { name: 'Prescription (Rx) Pad Printing', cat: 'forms', desc: 'Customized medical prescription pads for clinics and physicians.' },
            { name: 'Form Printing', cat: 'forms', desc: 'Corporate forms, application forms, checklists, and log sheets.' },
            { name: 'Voucher Printing', cat: 'forms', desc: 'Gift vouchers, discount coupons, promo cards, and serialized tickets.' },
            { name: 'Order Slip Printing', cat: 'forms', desc: 'Job order slips, kitchen order tickets, and delivery receipts.' },
            { name: 'Laser Printing', cat: 'marketing', desc: 'Crisp high-speed digital laser printing for documents & reports.' },
            { name: 'Poster Printing', cat: 'marketing', desc: 'Vibrant promotional posters, event displays, and decorative prints.' },
            { name: 'Flyer Printing', cat: 'marketing', desc: 'Marketing flyers, promotional handouts, and single-sheet leaflets.' },
            { name: 'Calling Card Printing', cat: 'marketing', desc: 'Professional business cards with matte, gloss, or velvet lamination.' },
            { name: 'Brochure Printing', cat: 'marketing', desc: 'Tri-fold, bi-fold, and multi-page marketing brochures & catalogs.' },
            { name: 'Bookbinding', cat: 'marketing', desc: 'Hardbound, softbound, coil, wire-o, and saddle-stitch bookbinding.' },
            { name: 'Lanyard Printing', cat: 'apparel', desc: 'Custom full-color sublimated lanyards and neck straps for events.' },
            { name: 'ID Sling Printing', cat: 'apparel', desc: 'Custom printed ID slings, badge reels, and lanyard accessories.' },
            { name: 'PVC ID Printing', cat: 'apparel', desc: 'Durable plastic PVC identification cards, student & employee IDs.' },
            { name: 'Mug Printing', cat: 'apparel', desc: 'Customized ceramic mugs, magic mugs, and promotional drinkware.' },
            { name: 'Folded Fan Printing', cat: 'apparel', desc: 'Custom plastic folded fans and promotional giveaway fans.' },
            { name: 'Invitation Printing', cat: 'marketing', desc: 'Wedding invitations, birthday cards, and formal event invites.' },
            { name: 'Souvenir Program Printing', cat: 'marketing', desc: 'Event souvenir programs, souvenir booklets, and commemorative books.' },
            { name: 'T-Shirt Printing – Silk Screen', cat: 'apparel', desc: 'Traditional silk screen printing for bulk t-shirts, jerseys, & uniforms.' },
            { name: 'T-Shirt Printing – Heat Press', cat: 'apparel', desc: 'Full-color vinyl & DTF heat press custom t-shirt printing.' },
            { name: 'Sintra Board Printing', cat: 'banners', desc: 'Rigid Sintra board standees, wall mounts, and photo panels.' },
            { name: 'X-Stand Banner Printing', cat: 'banners', desc: 'Portable X-stand banners and promotional display stands.' },
            { name: 'Pull-Up Banner Printing', cat: 'banners', desc: 'Retractable pull-up roll-up banners with aluminum stand.' },
            { name: 'Panaflex Signage', cat: 'banners', desc: 'Illuminated Panaflex signboards and store canopy signages.' },
            { name: 'Acrylic Signage', cat: 'banners', desc: 'Custom 3D acrylic signages, laser-cut acrylic logo signboards.' }
        ],

        form: {
            service: '', quantity: 1, size: '', material: '',
            finishing: '', deadline: '', preferred_branch: '',
            collection_mode: 'pickup', notes: '', fileName: '', fileSize: '',
        },
        init() {
            this.$watch('form.service', (svc) => {
                this.updateServiceDefaults(svc);
            });
        },
        matchesCategoryAndSearch(name, desc, category) {
            const catMatch = this.activeCategory === 'all' || this.activeCategory === category;
            if (!catMatch) return false;
            if (!this.searchQuery || this.searchQuery.trim() === '') return true;
            const q = this.searchQuery.toLowerCase().trim();
            return name.toLowerCase().includes(q) || desc.toLowerCase().includes(q);
        },
        get filteredServicesCount() {
            const q = this.searchQuery.toLowerCase().trim();
            return this.servicesMasterList.filter(s => {
                const catMatch = this.activeCategory === 'all' || this.activeCategory === s.cat;
                if (!catMatch) return false;
                if (!q) return true;
                return s.name.toLowerCase().includes(q) || s.desc.toLowerCase().includes(q);
            }).length;
        },
        get serviceCategory() {
            const s = (this.form.service || '').toLowerCase();
            if (s.includes('tarpaulin')) return 'tarp';
            if (s.includes('sticker') || s.includes('label')) return 'stickers';
            if (s.includes('mug')) return 'mugs';
            if (s.includes('fan')) return 'fans';
            if (s.includes('lanyard') || s.includes('sling')) return 'lanyard';
            if (s.includes('pvc id')) return 'pvc_id';
            if (s.includes('t-shirt')) return 'tshirt';
            if (s.includes('prescription')) return 'rx_pad';
            if (s.includes('receipt') || s.includes('invoice') || s.includes('form') || s.includes('voucher') || s.includes('order slip') || s.includes('risograph')) return 'forms';
            if (s.includes('sintra') || s.includes('x-stand') || s.includes('pull-up') || s.includes('panaflex') || s.includes('acrylic')) return 'displays';
            if (s.includes('calling card')) return 'calling_cards';
            if (s.includes('brochure')) return 'brochures';
            if (s.includes('bookbinding')) return 'bookbinding';
            if (s.includes('flyer') || s.includes('poster') || s.includes('laser')) return 'flyers_posters';
            if (s.includes('invitation') || s.includes('souvenir')) return 'invitations';
            return 'general';
        },
        get sizeOptions() {
            const cat = this.serviceCategory;
            if (cat === 'mugs') {
                return [
                    'Standard 11oz Ceramic Mug',
                    'Large 15oz Ceramic Mug',
                    '11oz Magic / Heat Sensitive Mug',
                    '12oz Stainless Steel Travel Tumbler',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'fans') {
                return [
                    'Standard 7-inch Plastic Folded Fan',
                    'Large 8-inch Plastic Folded Fan',
                    'Heart-Shape Hand Fan',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'stickers') {
                return [
                    'A4 Sheet (Kiss-Cut Labels)',
                    'A3 Sheet (Multi-Up Kiss-Cut)',
                    'Custom Die-Cut Single (2 × 2 in)',
                    'Custom Die-Cut Single (3 × 3 in)',
                    'Custom Roll Label',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'pvc_id') {
                return [
                    'Standard CR80 (85.6 × 53.9 mm) - PVC Card',
                    'Custom Oversized Badge (3.5 × 5 in)',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'lanyard') {
                return [
                    '3/4 inch (19mm) Width × 36 inch',
                    '1 inch (25mm) Width × 36 inch',
                    '1/2 inch (13mm) Width × 36 inch',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'tshirt') {
                return [
                    'Standard Adult (S, M, L, XL, 2XL)',
                    'Kids Sizes (Size 10, 12, 14, 16)',
                    'Oversized Unisex Fit',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'rx_pad') {
                return [
                    'Rx Pad Standard (5 × 7 in)',
                    'A5 Rx Pad (5.8 × 8.3 in)',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'forms') {
                if (this.form.service === 'Receipt Printing' || this.form.service === 'Invoice Printing') {
                    return [
                        'Half Letter / A5 (5.5 × 8.5 in)',
                        'Standard A4 (8.27 × 11.69 in)',
                        'Duplicate Booklet (4.25 × 7 in)',
                        'Custom / Specify exact dimensions'
                    ];
                }
                return [
                    'Standard A4 (8.27 × 11.69 in)',
                    'Full Letter (8.5 × 11 in)',
                    'Legal (8.5 × 14 in)',
                    'Half Letter / A5 (5.5 × 8.5 in)',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'brochures') {
                return [
                    'A4 Tri-Fold (Letter Size Folded)',
                    'A4 Bi-Fold (Half Sheet)',
                    'Z-Fold Brochure (A4)',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'bookbinding') {
                return [
                    'Standard A4 (8.27 × 11.69 in)',
                    'A5 Booklet (5.8 × 8.3 in)',
                    'Full Letter (8.5 × 11 in)',
                    'Legal (8.5 × 14 in)',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'invitations') {
                return [
                    'Standard 5 × 7 in Invitation Card',
                    'A5 Folded Card (5.8 × 8.3 in)',
                    'A4 Souvenir Booklet',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'flyers_posters') {
                return [
                    'A4 (210 × 297 mm)',
                    'A3 (297 × 420 mm)',
                    'A5 (148 × 210 mm)',
                    'Poster 18 × 24 in',
                    'Poster 24 × 36 in',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'displays') {
                if (this.form.service === 'Pull-Up Banner Printing') {
                    return ['Roll-Up / Pull-Up Banner (2.75 × 6.5 ft / 33 × 78 in)', 'Custom / Specify exact dimensions'];
                } else if (this.form.service === 'X-Stand Banner Printing') {
                    return ['X-Banner Standee (2 × 5 ft)', 'Custom / Specify exact dimensions'];
                } else if (this.form.service === 'Acrylic Signage' || this.form.service === 'Panaflex Signage') {
                    return [
                        'Custom Store Signage Dimensions',
                        '2 × 3 ft Illuminated Signage',
                        '3 × 6 ft Overhead Store Canopy',
                        'Custom / Specify exact dimensions'
                    ];
                }
                return [
                    'A2 Sintra Panel (16.5 × 23.4 in)',
                    'A1 Sintra Panel (23.4 × 33.1 in)',
                    'A3 Sintra Panel (11.7 × 16.5 in)',
                    'Custom Sintra Cutout',
                    'Custom / Specify exact dimensions'
                ];
            } else if (cat === 'calling_cards') {
                return [
                    'Standard Business Card (3.5 × 2 in)',
                    'Square Business Card (2.5 × 2.5 in)',
                    'Custom / Specify exact dimensions'
                ];
            }
            return [
                'A4 (210 × 297 mm)',
                'A3 (297 × 420 mm)',
                'A5 (148 × 210 mm)',
                'Letter (8.5 × 11 in)',
                'Legal (8.5 × 14 in)',
                'Poster (18 × 24 in)',
                'Custom / Specify exact dimensions'
            ];
        },
        get materialOptions() {
            const cat = this.serviceCategory;
            if (cat === 'mugs') {
                return [
                    'White Ceramic Sublimation Coating',
                    'Black Magic Heat-Sensitive Ceramic',
                    'Stainless Steel Metallic',
                    'Frosted Glass Mug'
                ];
            } else if (cat === 'fans') {
                return [
                    'Flexible Polypropylene (PP) Plastic 0.5mm',
                    'Heavy Cardstock 300gsm Hand Fan'
                ];
            } else if (cat === 'stickers') {
                return [
                    'Glossy Vinyl Waterproof Sticker',
                    'Matte Vinyl Waterproof Sticker',
                    'Transparent Clear Vinyl Sticker',
                    'Kraft Paper Eco Sticker',
                    'Reflective Outdoor Vinyl'
                ];
            } else if (cat === 'pvc_id') {
                return [
                    '0.76mm Premium Rigid PVC Polycarbonate',
                    'Frosted Matte PVC Board',
                    'Standard PVC Card'
                ];
            } else if (cat === 'lanyard') {
                return [
                    'High-Density Sublimation Polyester Ribbon',
                    'Smooth Satin Ribbon',
                    'Heavy-Duty Nylon Webbing'
                ];
            } else if (cat === 'tshirt') {
                return [
                    '100% Premium Combed Cotton (200gsm)',
                    'Cotton-Polyester Blend (60/40)',
                    'Dri-Fit Polyester Performance Mesh',
                    'Heavyweight Cotton (240gsm)'
                ];
            } else if (cat === 'rx_pad') {
                return [
                    'Book Paper 80gsm (Rx Pads)',
                    'Bond Paper 70gsm',
                    'Premium Matte Paper 90gsm'
                ];
            } else if (cat === 'forms') {
                if (this.form.service === 'Receipt Printing' || this.form.service === 'Invoice Printing') {
                    return [
                        'NCR Carbonless Duplicate Paper (2-Ply)',
                        'NCR Carbonless Triplicate Paper (3-Ply)',
                        'Standard Bond Paper 70gsm'
                    ];
                }
                return [
                    'Bond Paper 70gsm (Standard Forms)',
                    'NCR Carbonless Duplicate Paper (2-Ply)',
                    'Board Coupon Stock 180gsm (Vouchers)'
                ];
            } else if (cat === 'brochures') {
                return [
                    'Glossy Coated Paper 150gsm',
                    'Matte Coated Paper 170gsm',
                    'Premium Cardstock 220gsm'
                ];
            } else if (cat === 'bookbinding') {
                return [
                    'Hardbound Leatherette Cover + 80gsm Inside',
                    'Softbound Glossy Cover 250gsm + 70gsm Inside',
                    'Clear PVC Front Cover + Black Leatherette Backing'
                ];
            } else if (cat === 'invitations') {
                return [
                    'Specialty Textured Linen Board 250gsm',
                    'Pearlized Metallic Cardstock 300gsm',
                    'Matte Premium Cardstock 300gsm'
                ];
            } else if (cat === 'flyers_posters') {
                return [
                    'Glossy Paper 150gsm (Flyers)',
                    'Matte Paper 120gsm',
                    'Glossy Poster Stock 220gsm',
                    'Bond Paper 80gsm'
                ];
            } else if (cat === 'displays') {
                if (this.form.service === 'Pull-Up Banner Printing') {
                    return ['PET Synthetic Roll-Up Banner Film', '10oz Outdoor Tarpaulin'];
                } else if (this.form.service === 'Acrylic Signage') {
                    return ['4mm Cast Clear Acrylic Sheet', '5mm Solid Black Acrylic', '3mm Frosted Acrylic'];
                } else if (this.form.service === 'Panaflex Signage') {
                    return ['Panaflex Translucent Signboard Substrate', 'Backlit Heavy Duty Flex Film'];
                }
                return [
                    '3mm Rigid PVC Sintra Board',
                    '5mm Heavy-Duty PVC Sintra Board',
                    'Standard Foam Board 5mm'
                ];
            } else if (cat === 'calling_cards') {
                return [
                    'Matte Cardstock 300gsm (Heavyweight)',
                    'Glossy Cardstock 350gsm',
                    'Textured Specialty Linen Paper 250gsm'
                ];
            }
            return [
                'Matte 100gsm (Lightweight)',
                'Glossy 250gsm (Premium Cover)',
                'Cardstock 300gsm (Heavyweight)',
                'Vinyl Sheet (Weatherproof)',
                'Canvas'
            ];
        },
        get finishingOptions() {
            const cat = this.serviceCategory;
            if (cat === 'mugs') {
                return [
                    'Individual White Gift Box',
                    'Custom Printed Gift Box + Ribbon',
                    'Bubble Wrapped Safety Packaging',
                    'Bulk Tray Packed'
                ];
            } else if (cat === 'fans') {
                return [
                    'With Plastic Handle Grip',
                    'Foldable Wire Frame Pouch',
                    'Glossy Waterproof Coating'
                ];
            } else if (cat === 'stickers') {
                return [
                    'Kiss-Cut (Sheet Form)',
                    'Die-Cut (Individual Cutout)',
                    'Glossy Lamination Overlay',
                    'Matte Lamination Overlay',
                    'Unlaminated Cut-Out'
                ];
            } else if (cat === 'pvc_id') {
                return [
                    'Punch Hole (Slot Punch)',
                    'Round Corners (Standard ID)',
                    'With Transparent Badge Holder Sleeve'
                ];
            } else if (cat === 'lanyard') {
                return [
                    'Metal Side-Release Hook + Safety Breakaway Clip',
                    'Swivel Metal Lobster Hook',
                    'Badge Reel Hook + Plastic Sleeve Holder'
                ];
            } else if (cat === 'tshirt') {
                return [
                    'Full-Color DTF / Heat Transfer',
                    'Traditional Silkscreen Print (Front + Back)',
                    'Single Placement Logo Print',
                    'Polybagged & Folded per Piece'
                ];
            } else if (cat === 'rx_pad') {
                return [
                    'Padded Top with Cardboard Backing (100 sheets/pad)',
                    'Padded Top with Cardboard Backing (50 sheets/pad)'
                ];
            } else if (cat === 'forms') {
                return [
                    'Padded Top with Cardboard Backing (50 sheets/pad)',
                    'Padded Top (100 sheets/pad)',
                    'Stapled Booklet with Perforation & Numbering',
                    'Numbered Serialized Cut'
                ];
            } else if (cat === 'brochures') {
                return [
                    'Tri-Fold Machine Crease',
                    'Bi-Fold Machine Crease',
                    'Gloss Lamination Both Sides',
                    'Matte Lamination Both Sides'
                ];
            } else if (cat === 'bookbinding') {
                return [
                    'Hardbound / Bookbinding with Gold Foil Title',
                    'Plastic Ring / Spiral Coil Binding',
                    'Wire-O Metal Binding',
                    'Thermal Tape Binding',
                    'Saddle Stitch Binding'
                ];
            } else if (cat === 'invitations') {
                return [
                    'Includes Matching Envelope & Seal',
                    'Saddle Stitched Booklet (Souvenir)',
                    'Ribbon Accent + Die-Cut Cover',
                    'Gold / Silver Foil Stamping'
                ];
            } else if (cat === 'flyers_posters') {
                return [
                    'None (Standard Cut to Size)',
                    'Gloss Lamination Overlay',
                    'Matte Lamination Overlay'
                ];
            } else if (cat === 'displays') {
                if (this.form.service === 'Pull-Up Banner Printing') {
                    return ['Includes Aluminum Pull-Up Stand + Carrying Bag'];
                } else if (this.form.service === 'X-Stand Banner Printing') {
                    return ['Includes Collapsible X-Stand Frame'];
                } else if (this.form.service === 'Acrylic Signage') {
                    return ['Standoff Stainless Bolts (Wall Mounting)', 'Flame Polished Edges'];
                }
                return [
                    'Direct Matte Lamination + Double-Sided Tape Backing',
                    'Cut-to-Shape Standee Backer',
                    'Standoff Wall Mounts'
                ];
            } else if (cat === 'calling_cards') {
                return [
                    'Matte Lamination Both Sides',
                    'Gloss Lamination Both Sides',
                    'Round Corners (4-Sides)',
                    'Foiled Stamping Accent'
                ];
            }
            return [
                'None (Standard Cut)',
                'Gloss Lamination',
                'Matte Lamination',
                'Saddle Stitch Binding',
                'Perfect Binding',
                'Die Cut'
            ];
        },
        get isCustomSizeSelected() {
            if (this.sizeMode === 'custom') return true;
            if (!this.selectedSizeOption) return false;
            return this.selectedSizeOption.toLowerCase().includes('custom');
        },
        get customSizePlaceholder() {
            const cat = this.serviceCategory;
            if (cat === 'stickers') return 'e.g., 2 × 2 in, 3 × 3 in, 5 × 5 cm, 50mm circle';
            if (cat === 'lanyard') return 'e.g., 3/4 in × 36 in, 1 in × 36 in';
            if (cat === 'tshirt') return 'e.g., 5 Pcs Medium, 10 Pcs Large, 5 Pcs XL';
            if (cat === 'forms') return 'e.g., 5.5 × 8.5 in, 8.5 × 11 in, 50 sheets/pad';
            if (cat === 'displays') return 'e.g., 3 × 8 ft, 4 × 10 ft, 24 × 60 in';
            if (cat === 'calling_cards') return 'e.g., 3.5 × 2 in, 90 × 54 mm';
            return 'e.g., 2 × 3 ft, 40 × 60 cm, 8.5 × 11 in';
        },
        updateCustomSizeValue() {
            if (this.form.service === 'Tarpaulin Printing') return;
            if (this.sizeMode === 'custom' || (this.selectedSizeOption && this.selectedSizeOption.toLowerCase().includes('custom'))) {
                this.sizeMode = 'custom';
                if (this.customSizeText && this.customSizeText.trim() !== '') {
                    this.form.size = `Custom (${this.customSizeText.trim()})`;
                } else {
                    this.form.size = this.selectedSizeOption || 'Custom Size';
                }
            } else {
                this.form.size = this.selectedSizeOption;
            }
        },
        updateServiceDefaults(svc) {
            if (!svc) return;
            if (svc === 'Tarpaulin Printing') {
                this.form.material = '10oz Standard Outdoor Tarpaulin';
                this.form.finishing = 'Allowance with Eyelets';
                this.updateTarpSize();
            } else {
                this.selectedSizeOption = this.sizeOptions[0];
                this.customSizeText = '';
                if (this.selectedSizeOption && this.selectedSizeOption.toLowerCase().includes('custom')) {
                    this.sizeMode = 'custom';
                } else {
                    this.sizeMode = 'preset';
                }
                this.updateCustomSizeValue();
                this.form.material = this.materialOptions[0];
                this.form.finishing = this.finishingOptions[0];
            }
        },
        updateTarpSize() {
            if (this.form.service !== 'Tarpaulin Printing') return;
            if (this.tarpMode === 'dimensions') {
                const w = parseFloat(this.tarpWidth) || 0;
                const h = parseFloat(this.tarpHeight) || 0;
                this.form.size = `${w} × ${h} ${this.tarpUnit} (${this.calculatedAreaText})`;
            } else {
                this.form.size = this.customTarpSize || 'Custom Size';
            }
        },
        get calculatedAreaValue() {
            const w = parseFloat(this.tarpWidth) || 0;
            const h = parseFloat(this.tarpHeight) || 0;
            if (this.tarpUnit === 'FT') {
                return (w * h).toFixed(2);
            } else if (this.tarpUnit === 'IN') {
                return ((w * h) / 144).toFixed(2);
            } else if (this.tarpUnit === 'M') {
                return (w * h).toFixed(2);
            }
            return '0.00';
        },
        get calculatedAreaText() {
            const unitLabel = this.tarpUnit === 'M' ? 'sq m' : 'sq ft';
            return `${this.calculatedAreaValue} ${unitLabel}`;
        },
        nextStep() {
            if (this.step === 1 && !this.form.service) { alert('Please select a print service.'); return; }
            if (this.step === 2) {
                if (!this.form.quantity || this.form.quantity < 1) { alert('Please enter a valid quantity.'); return; }
                if (this.form.service === 'Tarpaulin Printing') this.updateTarpSize();
                if (!this.form.size || this.form.size.trim() === '') { alert('Please specify the size / dimensions.'); return; }
            }
            if (this.step === 4 && !this.form.deadline) { alert('Please enter a deadline.'); return; }
            if (this.step < 5) this.step++;
        },
        prevStep() { if (this.step > 1) this.step--; },
        handleFile(e) {
            const f = e.target.files[0];
            if (f) { this.form.fileName = f.name; this.form.fileSize = this.fmtBytes(f.size); }
        },
        handleDrop(e) {
            const f = e.dataTransfer.files[0];
            if (f) {
                const dt = new DataTransfer(); dt.items.add(f);
                this.$refs.fileInput.files = dt.files;
                this.form.fileName = f.name; this.form.fileSize = this.fmtBytes(f.size);
            }
        },
        fmtBytes(b) { return b >= 1048576 ? (b/1048576).toFixed(1)+' MB' : (b/1024).toFixed(0)+' KB'; }
    }
}
</script>
@endsection
@endsection
