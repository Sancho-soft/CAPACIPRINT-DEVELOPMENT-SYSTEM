@extends('layouts.customer')
@section('title', 'Help & Support')
@section('page-title', 'Help & Support')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-navy-900 font-display">Help &amp; Support</h2>
        <p class="text-sm text-slate-500 mt-1">Find answers to common questions about the CAPACIPRINT customer portal.</p>
    </div>

    @php
    $faqs = [
        ['q' => 'How do I submit a print request?', 'a' => 'Click "New Print Request" in the sidebar or on your dashboard. Follow the 5-step wizard: select your service, configure specifications, upload your design file, set a deadline, and review before submitting.'],
        ['q' => 'What file formats are accepted?', 'a' => 'We accept PDF, EPS, TIFF, JPG, PNG, and AI files up to 50MB. For best print quality, submit high-resolution files (300 DPI or higher) with proper bleed and colour profiles.'],
        ['q' => 'How long does it take to get a quotation?', 'a' => 'Quotations are typically generated within 1–2 business days after your request is reviewed. You will receive a notification once your quotation is ready.'],
        ['q' => 'How do I confirm a quotation?', 'a' => 'Go to "Quotations" in the sidebar, find the quotation with "Pending" status, review the pricing details, and click "Confirm & Pay".'],
        ['q' => 'How do I make a payment?', 'a' => 'After confirming your quotation, go to "Payments" and find the pending payment. Transfer the amount using our accepted payment methods, then submit your transaction reference number. Our team will verify and confirm.'],
        ['q' => 'Can I cancel my print request?', 'a' => 'You can cancel a request while it is in "Submitted" status. Once it moves to quotation or production stages, cancellation must be coordinated with our team.'],
        ['q' => 'How do I claim my completed order?', 'a' => 'When your order is "Ready for Pickup", a QR claim reference will be generated. Go to "QR / Claiming" in the sidebar, show the QR code and a valid ID to staff at the designated branch.'],
        ['q' => 'Which branch will handle my order?', 'a' => 'CAPACIPRINT automatically assigns the most suitable production branch based on capacity analysis. You can specify a preferred branch during request submission, but the final assignment is determined by system routing.'],
    ];
    @endphp

    <div class="space-y-3" x-data="{ open: null }">
        @foreach($faqs as $i => $faq)
        <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
            <button @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                    class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-slate-50 transition">
                <span class="font-semibold text-navy-900 text-sm">{{ $faq['q'] }}</span>
                <i class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform"
                   :class="open === {{ $i }} ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open === {{ $i }}" x-collapse class="px-6 pb-4">
                <p class="text-sm text-slate-600 leading-relaxed">{{ $faq['a'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Contact Section --}}
    <div class="bg-navy-800 rounded-2xl p-6 text-white">
        <h3 class="font-bold text-lg font-display mb-1">Still need help?</h3>
        <p class="text-sm text-navy-200 mb-4">Contact our support team and we'll get back to you as soon as possible.</p>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="mailto:support@capaciprint.com"
               class="flex items-center gap-2 text-sm text-brand-300 hover:text-brand-200 font-semibold">
                <i class="fa-solid fa-envelope"></i> support@capaciprint.com
            </a>
            <span class="hidden sm:inline text-navy-500">·</span>
            <a href="tel:+630000000000"
               class="flex items-center gap-2 text-sm text-brand-300 hover:text-brand-200 font-semibold">
                <i class="fa-solid fa-phone"></i> +63 (0) 000 000 0000
            </a>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Alpine collapse plugin for accordion
document.addEventListener('alpine:init', () => {
    Alpine.directive('collapse', (el) => {
        el._x_isShown = false;
        el.style.overflow = 'hidden';
        if (el.style.display === 'none') el.style.maxHeight = '0';
    });
});
</script>
@endsection
@endsection
