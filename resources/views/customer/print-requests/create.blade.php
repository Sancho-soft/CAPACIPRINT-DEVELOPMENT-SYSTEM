@extends('layouts.customer')
@section('title', 'New Print Request')
@section('page-title', 'New Print Request')

@section('content')
<div class="max-w-3xl mx-auto" x-data="printWizard()">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-navy-900 font-display">Submit Print Request</h2>
        <p class="text-sm text-slate-500 mt-1">Fill in the details below and upload your artwork to get started.</p>
    </div>

    {{-- ── Step Indicators ─────────────────────────────── --}}
    <div class="flex items-center justify-between mb-8">
        <template x-for="(label, i) in steps" :key="i">
            <div class="flex-1 flex flex-col items-center relative">
                {{-- connector line --}}
                <template x-if="i > 0">
                    <div class="absolute left-0 top-4 w-full h-0.5 -translate-y-1/2"
                         :class="step > i ? 'bg-brand-400' : 'bg-slate-200'"></div>
                </template>
                <div class="relative z-10 h-8 w-8 rounded-full border-2 flex items-center justify-center text-xs font-bold transition-all"
                     :class="step === i+1 ? 'border-brand-500 bg-brand-500 text-white ring-4 ring-brand-200'
                           : (step > i+1  ? 'border-brand-400 bg-brand-400 text-white'
                           : 'border-slate-200 bg-white text-slate-400')">
                    <template x-if="step > i+1"><i class="fa-solid fa-check text-[10px]"></i></template>
                    <template x-if="step <= i+1"><span x-text="i+1"></span></template>
                </div>
                <span class="hidden sm:block text-[10px] font-semibold text-slate-500 mt-1.5 text-center" x-text="label"></span>
            </div>
        </template>
    </div>

    {{-- ── Form Card ────────────────────────────────────── --}}
    <form method="POST" action="{{ route('customer.print-requests.store') }}" enctype="multipart/form-data" id="print-request-form">
        @csrf

        <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-6 sm:p-8 min-h-[380px]">

            {{-- STEP 1: SERVICE --}}
            <div x-show="step === 1" class="space-y-5">
                <h3 class="text-lg font-bold text-navy-900">Select Print Service</h3>
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
                    <label class="border rounded-xl p-4 cursor-pointer transition flex items-start gap-4 hover:border-brand-400"
                           :class="form.service === '{{ $svc['name'] }}' ? 'border-brand-500 bg-brand-50/30 ring-1 ring-brand-400 shadow' : 'border-slate-200'">
                        <input type="radio" name="service" value="{{ $svc['name'] }}" x-model="form.service" class="sr-only" required>
                        <div class="h-10 w-10 bg-slate-100 text-navy-700 rounded-lg flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid {{ $svc['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="font-bold text-navy-900 text-sm">{{ $svc['name'] }}</p>
                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $svc['desc'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- STEP 2: SPECIFICATIONS --}}
            <div x-show="step === 2" class="space-y-5">
                <h3 class="text-lg font-bold text-navy-900">Print Specifications</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" x-model.number="form.quantity" min="1" required
                               class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Size / Dimensions <span class="text-red-500">*</span></label>
                        <select name="size" x-model="form.size" required
                                class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition">
                            <option value="A4">A4 (210 × 297 mm)</option>
                            <option value="A5">A5 (148 × 210 mm)</option>
                            <option value="Letter">Letter (8.5 × 11 in)</option>
                            <option value="Legal">Legal (8.5 × 14 in)</option>
                            <option value="Poster Size">Poster (18 × 24 in)</option>
                            <option value="Custom">Custom / Specify in notes</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Material <span class="text-red-500">*</span></label>
                        <select name="material" x-model="form.material" required
                                class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition">
                            <option value="Matte 100gsm">Matte 100gsm (Lightweight)</option>
                            <option value="Glossy 250gsm">Glossy 250gsm (Premium Cover)</option>
                            <option value="Cardstock 300gsm">Cardstock 300gsm (Heavyweight)</option>
                            <option value="Vinyl Sheet">Vinyl Sheet (Weatherproof)</option>
                            <option value="Canvas">Canvas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Finishing</label>
                        <select name="finishing" x-model="form.finishing"
                                class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition">
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
                <h3 class="text-lg font-bold text-navy-900">Upload Artwork File</h3>
                <div class="border-2 border-dashed border-slate-200 hover:border-brand-400 rounded-xl p-10 text-center transition cursor-pointer bg-slate-50/50"
                     @click="$refs.fileInput.click()" @dragover.prevent @drop.prevent="handleDrop($event)">
                    <input type="file" name="design_file" x-ref="fileInput" class="hidden"
                           accept=".pdf,.eps,.tiff,.tif,.jpg,.jpeg,.png,.ai" @change="handleFile($event)">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400 mb-3 block"></i>
                    <p class="text-sm font-bold text-navy-900">Drag &amp; drop or <span class="text-brand-500">browse</span></p>
                    <p class="text-xs text-slate-400 mt-1">PDF, EPS, TIFF, JPG, PNG, AI — max 50 MB</p>
                </div>

                <template x-if="form.fileName">
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg flex items-center gap-3">
                        <i class="fa-solid fa-file text-brand-500 text-xl shrink-0"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-navy-900 truncate" x-text="form.fileName"></p>
                            <p class="text-xs text-slate-400" x-text="form.fileSize"></p>
                        </div>
                        <button type="button" @click="form.fileName=''; form.fileSize=''; $refs.fileInput.value=''"
                                class="text-slate-400 hover:text-red-500 p-1 transition">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                </template>

                <p class="text-xs text-slate-400">File upload is optional. You may provide a link in additional instructions if the file is too large.</p>
            </div>

            {{-- STEP 4: SCHEDULE --}}
            <div x-show="step === 4" class="space-y-5">
                <h3 class="text-lg font-bold text-navy-900">Schedule &amp; Collection</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Deadline <span class="text-red-500">*</span></label>
                        <input type="date" name="deadline" x-model="form.deadline" required
                               :min="minDate"
                               class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition">
                        <p class="text-[11px] text-slate-400 mt-1">Minimum 2-day production window required.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Preferred Branch <span class="text-slate-400 font-normal">(optional)</span></label>
                        <select name="preferred_branch" x-model="form.preferred_branch"
                                class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition">
                            <option value="">System will recommend best branch</option>
                            <option value="Branch 1 – Main">Branch 1 – Main</option>
                            <option value="Branch 2 – North">Branch 2 – North</option>
                            <option value="Branch 3 – South">Branch 3 – South</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-2">Collection Mode <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="border rounded-xl p-4 text-center cursor-pointer transition"
                               :class="form.collection_mode === 'pickup' ? 'border-brand-500 bg-brand-50/30 ring-1 ring-brand-400' : 'border-slate-200 hover:border-slate-300'">
                            <input type="radio" name="collection_mode" value="pickup" x-model="form.collection_mode" class="sr-only">
                            <i class="fa-solid fa-store text-navy-700 text-xl mb-1 block"></i>
                            <span class="text-xs font-bold text-navy-900">Branch Pickup</span>
                        </label>
                        <label class="border rounded-xl p-4 text-center cursor-pointer transition"
                               :class="form.collection_mode === 'shipping' ? 'border-brand-500 bg-brand-50/30 ring-1 ring-brand-400' : 'border-slate-200 hover:border-slate-300'">
                            <input type="radio" name="collection_mode" value="shipping" x-model="form.collection_mode" class="sr-only">
                            <i class="fa-solid fa-truck text-navy-700 text-xl mb-1 block"></i>
                            <span class="text-xs font-bold text-navy-900">Courier Shipping</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Additional Instructions</label>
                    <textarea name="additional_instructions" x-model="form.notes" rows="3" placeholder="Colour profile, bleed requirements, special handling notes..."
                              class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition resize-none"></textarea>
                </div>
            </div>

            {{-- STEP 5: REVIEW --}}
            <div x-show="step === 5" class="space-y-5">
                <h3 class="text-lg font-bold text-navy-900">Review Your Request</h3>
                <div class="border border-slate-150 rounded-xl overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <tbody class="bg-white divide-y divide-slate-100">
                            <tr>
                                <td class="px-5 py-3 font-semibold text-slate-500 bg-slate-50 w-1/3">Print Service</td>
                                <td class="px-5 py-3 font-bold text-navy-900" x-text="form.service || '—'"></td>
                            </tr>
                            <tr>
                                <td class="px-5 py-3 font-semibold text-slate-500 bg-slate-50">Qty &amp; Size</td>
                                <td class="px-5 py-3 text-slate-800" x-text="form.quantity + ' copies · ' + form.size"></td>
                            </tr>
                            <tr>
                                <td class="px-5 py-3 font-semibold text-slate-500 bg-slate-50">Material</td>
                                <td class="px-5 py-3 text-slate-800" x-text="form.material"></td>
                            </tr>
                            <tr>
                                <td class="px-5 py-3 font-semibold text-slate-500 bg-slate-50">Finishing</td>
                                <td class="px-5 py-3 text-slate-800" x-text="form.finishing"></td>
                            </tr>
                            <tr>
                                <td class="px-5 py-3 font-semibold text-slate-500 bg-slate-50">Artwork File</td>
                                <td class="px-5 py-3 text-slate-800" x-text="form.fileName || 'No file selected'"></td>
                            </tr>
                            <tr>
                                <td class="px-5 py-3 font-semibold text-slate-500 bg-slate-50">Deadline</td>
                                <td class="px-5 py-3 text-slate-800" x-text="form.deadline"></td>
                            </tr>
                            <tr>
                                <td class="px-5 py-3 font-semibold text-slate-500 bg-slate-50">Collection</td>
                                <td class="px-5 py-3 text-slate-800" x-text="form.collection_mode === 'pickup' ? 'Branch Pickup' : 'Courier Shipping'"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-brand-50 border border-brand-100 rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-brand-500 text-lg shrink-0"></i>
                    <p class="text-sm text-navy-800">
                        A formal quotation will be generated after routing analysis and sent to your account.
                    </p>
                </div>
            </div>
        </div>

        {{-- ── Navigation Buttons ─────────────────────────── --}}
        <div class="flex items-center justify-between mt-6">
            <button type="button" @click="prevStep" x-show="step > 1"
                    class="border border-slate-200 hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back
            </button>
            <div x-show="step === 1"></div>

            <template x-if="step < 5">
                <button type="button" @click="nextStep"
                        class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-6 py-2.5 rounded-lg text-sm transition shadow flex items-center gap-2">
                    Continue <i class="fa-solid fa-arrow-right"></i>
                </button>
            </template>
            <template x-if="step === 5">
                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-2.5 rounded-lg text-sm transition shadow flex items-center gap-2">
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
