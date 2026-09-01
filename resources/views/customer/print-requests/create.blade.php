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
                <div>
                    <h3 class="text-lg font-bold text-cyber-main">Select Print Service</h3>
                    <p class="text-xs text-cyber-muted mt-0.5">Choose the service that matches your product type.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                    $services = [
                        ['name'=>'Digital Printing',   'icon'=>'fa-print',           'desc'=>'High-quality digital prints for flyers, brochures, and marketing materials.'],
                        ['name'=>'Offset Printing',    'icon'=>'fa-layer-group',      'desc'=>'Cost-effective bulk printing with consistent colour accuracy.'],
                        ['name'=>'Large Format',       'icon'=>'fa-expand',           'desc'=>'Banners, posters, and signage up to 3m wide.'],
                        ['name'=>'Booklet / Binding',  'icon'=>'fa-book',             'desc'=>'Saddle-stitch or perfect binding for booklets and catalogs.'],
                        ['name'=>'Business Cards',     'icon'=>'fa-id-card',          'desc'=>'Premium business cards with optional special finishes.'],
                        ['name'=>'Sticker / Labels',   'icon'=>'fa-tag',              'desc'=>'Custom cut stickers and labels in various shapes and materials.'],
                    ];
                    @endphp
                    @foreach($services as $svc)
                    <label class="border rounded-2xl p-4 cursor-pointer transition flex items-start gap-4 hover:border-cyan-500/40 bg-cyber-sub/40 hover:bg-cyber-sub/80"
                           :class="form.service === '{{ $svc['name'] }}' ? 'border-cyan-500 bg-cyan-500/10 ring-1 ring-cyan-500/40 shadow-lg' : 'border-cyber'">
                        <input type="radio" name="service" value="{{ $svc['name'] }}" x-model="form.service" class="sr-only" required>
                        <div class="h-10 w-10 bg-cyber-sub text-cyan-400 border border-cyber rounded-xl flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid {{ $svc['icon'] }}"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-cyber-main text-sm">{{ $svc['name'] }}</p>
                            <p class="text-xs text-cyber-muted mt-0.5 leading-relaxed">{{ $svc['desc'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- STEP 2: SPECIFICATIONS --}}
            <div x-show="step === 2" class="space-y-5">
                <div>
                    <h3 class="text-lg font-bold text-cyber-main">Print Specifications</h3>
                    <p class="text-xs text-cyber-muted mt-0.5">Specify dimensions, stock materials, and finishing options.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Quantity <span class="text-red-400">*</span></label>
                        <input type="number" name="quantity" x-model.number="form.quantity" min="1" required
                               class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Size / Dimensions <span class="text-red-400">*</span></label>
                        <select name="size" x-model="form.size" required
                                class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                            <option value="A4">A4 (210 × 297 mm)</option>
                            <option value="A5">A5 (148 × 210 mm)</option>
                            <option value="Letter">Letter (8.5 × 11 in)</option>
                            <option value="Legal">Legal (8.5 × 14 in)</option>
                            <option value="Poster Size">Poster (18 × 24 in)</option>
                            <option value="Custom">Custom / Specify in notes</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Material <span class="text-red-400">*</span></label>
                        <select name="material" x-model="form.material" required
                                class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                            <option value="Matte 100gsm">Matte 100gsm (Lightweight)</option>
                            <option value="Glossy 250gsm">Glossy 250gsm (Premium Cover)</option>
                            <option value="Cardstock 300gsm">Cardstock 300gsm (Heavyweight)</option>
                            <option value="Vinyl Sheet">Vinyl Sheet (Weatherproof)</option>
                            <option value="Canvas">Canvas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Finishing</label>
                        <select name="finishing" x-model="form.finishing"
                                class="block w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-sm text-cyber-main focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                            <option value="None">None (Standard Cut)</option>
                            <option value="Gloss Lamination">Gloss Lamination</option>
                            <option value="Matte Lamination">Matte Lamination</option>
                            <option value="Saddle Stitch Binding">Saddle Stitch Binding</option>
                            <option value="Perfect Binding">Perfect Binding</option>
                            <option value="Die Cut">Die Cut</option>
                        </select>
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
        form: {
            service: '', quantity: 1, size: 'A4', material: 'Matte 100gsm',
            finishing: 'None', deadline: '', preferred_branch: '',
            collection_mode: 'pickup', notes: '', fileName: '', fileSize: '',
        },
        nextStep() {
            if (this.step === 1 && !this.form.service) { alert('Please select a print service.'); return; }
            if (this.step === 2 && (!this.form.quantity || this.form.quantity < 1)) { alert('Please enter a valid quantity.'); return; }
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
