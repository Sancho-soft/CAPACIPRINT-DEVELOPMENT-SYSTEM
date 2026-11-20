@extends('layouts.customer')
@section('title', 'QR Claiming')
@section('page-title', 'QR / Claiming')

@section('content')
<div class="space-y-6 w-full">
    <div class="{{ $claims->count() <= 1 ? 'max-w-xl mx-auto text-center' : '' }}">
        <h2 class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight">My Claim References</h2>
        <p class="text-xs sm:text-sm text-cyber-muted mt-1">Present your QR code or claim code when collecting your completed order.</p>
    </div>

    @if($claims->isEmpty())
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-14 text-center max-w-xl mx-auto">
        <div class="h-14 w-14 mx-auto rounded-xl bg-cyber-sub text-cyber-muted border border-cyber flex items-center justify-center text-xl mb-4">
            <i class="fa-solid fa-qrcode"></i>
        </div>
        <h4 class="font-bold text-cyber-main text-base font-display">No claim references yet</h4>
        <p class="text-xs text-cyber-muted mt-1">Claim references and QR codes will automatically appear here when your order is ready for pickup.</p>
    </div>
    @else
    <div class="{{ $claims->count() === 1 ? 'max-w-lg mx-auto' : ($claims->count() === 2 ? 'max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6' : 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6') }}">
        @foreach($claims as $claim)
        <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden hover:border-brand-600/40 dark:hover:border-blue-500/40 transition-colors flex flex-col">
            {{-- Card Header --}}
            <div class="px-5 py-4 border-b border-cyber flex items-center justify-between bg-cyber-sub">
                <div>
                    <p class="text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Order</p>
                    <p class="font-bold text-cyber-main font-sans tabular-nums text-base">#{{ $claim->order->order_number ?? '—' }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 text-[11px] font-sans tabular-nums font-semibold rounded-full {{ $claim->is_claimed ? 'bg-slate-500/15 text-cyber-muted border border-cyber' : 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30' }}">
                        {{ $claim->is_claimed ? 'Claimed' : 'Ready for Pickup' }}
                    </span>
                    <a href="{{ route('customer.claiming.show', $claim->order_id) }}" 
                       class="h-8 w-8 rounded-xl bg-cyber-card hover:border-blue-600/40 border border-cyber text-cyber-muted hover:text-blue-600 dark:hover:text-blue-400 flex items-center justify-center text-sm transition"
                       title="Print Slip">
                        <i class="fa-solid fa-print"></i>
                    </a>
                </div>
            </div>

            {{-- Direct QR Code Section --}}
            <div class="py-6 px-4 bg-cyber-sub/30 border-b border-cyber flex flex-col items-center justify-center relative">
                @if($claim->is_claimed)
                    <div class="relative p-4 bg-white rounded-xl border border-cyber opacity-60">
                        <canvas id="qr-claim-{{ $claim->id }}" class="qr-canvas w-44 h-44 sm:w-48 sm:h-48 filter grayscale" data-code="{{ $claim->claim_code }}"></canvas>
                        <div class="absolute inset-0 bg-[#0C0E12]/75 backdrop-blur-[1px] rounded-xl flex flex-col items-center justify-center text-white">
                            <i class="fa-solid fa-circle-check text-3xl text-emerald-400 mb-1.5"></i>
                            <span class="text-xs font-sans font-semibold text-emerald-300">Claimed</span>
                        </div>
                    </div>
                @else
                    <div class="qr-clickable-box p-4 bg-white rounded-xl border border-cyber inline-flex flex-col items-center cursor-pointer group hover:border-blue-600 transition-colors"
                         data-code="{{ $claim->claim_code }}"
                         data-order="{{ $claim->order->order_number ?? '' }}"
                         title="Click to enlarge QR code">
                        <canvas id="qr-claim-{{ $claim->id }}" class="qr-canvas w-44 h-44 sm:w-48 sm:h-48" data-code="{{ $claim->claim_code }}"></canvas>
                        <span class="mt-2.5 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-sans font-semibold text-cyber-muted bg-cyber-sub group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-magnifying-glass-plus text-[11px]"></i> Tap to Enlarge
                        </span>
                    </div>
                @endif

                {{-- Claim Reference Code with Copy button --}}
                <div class="mt-4 text-center">
                    <span class="text-[11px] font-sans tabular-nums uppercase font-semibold text-cyber-muted tracking-wider block">Claim Reference Code</span>
                    <div class="inline-flex items-center gap-2.5 mt-1.5 bg-cyber-card px-4 py-2 rounded-lg border border-cyber">
                        <span class="text-base sm:text-lg font-bold font-sans tabular-nums tracking-wide text-cyber-main tabular-nums">{{ $claim->claim_code }}</span>
                        <button type="button" 
                                id="copy-btn-{{ $claim->id }}" 
                                onclick="copyClaimCode('{{ $claim->claim_code }}', 'copy-btn-{{ $claim->id }}')" 
                                aria-label="Copy claim code"
                                class="text-cyber-muted hover:text-brand-600 dark:hover:text-blue-400 text-sm transition cursor-pointer p-0.5" 
                                title="Copy code">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Order Details --}}
            <div class="p-5">
                <div class="space-y-2.5 text-xs tabular-nums">
                    <div class="flex justify-between py-1 border-b border-cyber-sub">
                        <span class="text-cyber-muted">Service</span>
                        <span class="font-semibold text-cyber-main text-right">{{ $claim->order->printRequest->service ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-cyber-sub">
                        <span class="text-cyber-muted">Pickup Branch</span>
                        <span class="font-semibold text-cyber-main text-right">{{ $claim->pickup_branch ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-cyber-sub">
                        <span class="text-cyber-muted">Completion</span>
                        <span class="font-sans tabular-nums font-semibold text-cyber-main">{{ $claim->completion_date?->format('M d, Y') ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1 {{ $claim->is_claimed ? 'border-b border-cyber-sub' : '' }}">
                        <span class="text-cyber-muted">Payment Method</span>
                        <span class="font-semibold text-cyber-main">{{ $claim->order->payment->payment_method ?? 'Cash on Pickup' }}</span>
                    </div>
                    @if($claim->is_claimed)
                    <div class="flex justify-between py-1">
                        <span class="text-cyber-muted">Claimed At</span>
                        <span class="font-sans tabular-nums font-semibold text-cyber-main">{{ $claim->claimed_at?->format('M d, Y h:i A') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- Enlarge QR Code Modal --}}
<div id="qr-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/75 backdrop-blur-sm p-4 overscroll-contain" onclick="if(event.target === this) closeEnlargedQr()">
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm max-w-sm w-full p-6 text-center shadow-lg relative space-y-4">
        <button type="button" onclick="closeEnlargedQr()" aria-label="Close QR modal" class="absolute top-4 right-4 h-8 w-8 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main flex items-center justify-center text-sm transition cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div>
            <span class="text-[11px] font-sans tabular-nums uppercase font-semibold text-brand-600 dark:text-blue-400 tracking-wider">Present to Counter Staff</span>
            <h3 class="text-xl font-bold text-cyber-main font-sans tabular-nums mt-0.5" id="modal-order-num">#ORD-001</h3>
        </div>
        <div class="p-4 bg-white rounded-xl inline-block mx-auto border border-cyber">
            <canvas id="modal-qr-canvas" class="w-56 h-56 mx-auto"></canvas>
        </div>
        <div>
            <p class="text-[11px] font-sans tabular-nums uppercase font-semibold text-cyber-muted tracking-wider">Claim Reference Code</p>
            <p class="text-xl font-bold font-sans tabular-nums text-brand-600 dark:text-blue-400 tracking-wide mt-1" id="modal-claim-code">CLM-XXXXXX</p>
        </div>
        <p class="text-xs text-cyber-muted">Hold your screen up to the scanner at the branch pickup counter.</p>
        <button type="button" onclick="closeEnlargedQr()" class="w-full py-2.5 bg-cyber-sub hover:bg-cyber-card border border-cyber rounded-lg text-cyber-main font-semibold text-xs transition cursor-pointer">
            Close
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/qrious.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.qr-canvas').forEach(function(canvas) {
            const code = canvas.getAttribute('data-code');
            if (code) {
                new QRious({
                    element: canvas,
                    value: code,
                    size: 192,
                    level: 'H'
                });
            }
        });

        document.querySelectorAll('.qr-clickable-box').forEach(function(box) {
            box.addEventListener('click', function() {
                const code = box.getAttribute('data-code');
                const orderNum = box.getAttribute('data-order');
                showEnlargedQr(code, orderNum);
            });
        });
    });

    let modalQr = null;
    function showEnlargedQr(code, orderNum) {
        document.getElementById('modal-order-num').textContent = '#' + (orderNum || '');
        document.getElementById('modal-claim-code').textContent = code || '';
        const canvas = document.getElementById('modal-qr-canvas');
        if (!modalQr) {
            modalQr = new QRious({
                element: canvas,
                value: code || '',
                size: 224,
                level: 'H'
            });
        } else {
            modalQr.value = code || '';
        }
        const modal = document.getElementById('qr-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEnlargedQr() {
        const modal = document.getElementById('qr-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // ESC key closes modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEnlargedQr();
        }
    });

    function copyClaimCode(code, btnId) {
        if (!navigator.clipboard) {
            const temp = document.createElement('input');
            temp.value = code;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
        } else {
            navigator.clipboard.writeText(code);
        }
        const btn = document.getElementById(btnId);
        if (btn) {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check text-emerald-700 dark:text-emerald-400"></i>';
            setTimeout(function() {
                btn.innerHTML = originalHtml;
            }, 1800);
        }
    }
</script>
@endsection
