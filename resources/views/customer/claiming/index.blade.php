@extends('layouts.customer')
@section('title', 'QR Claiming')
@section('page-title', 'QR / Claiming')

@section('content')
<div class="space-y-6 w-full">
    <div class="{{ $claims->count() <= 1 ? 'max-w-xl mx-auto text-center' : '' }}">
        <h2 class="text-2xl sm:text-3xl font-black text-cyber-main font-display">My Claim References</h2>
        <p class="text-sm text-cyber-muted mt-1">Present your QR code or claim code when collecting your completed order.</p>
    </div>

    @if($claims->isEmpty())
    <div class="bg-cyber-card border border-cyber rounded-3xl p-16 text-center shadow-xl max-w-xl mx-auto">
        <div class="h-16 w-16 mx-auto rounded-2xl bg-cyber-sub text-cyber-muted border border-cyber flex items-center justify-center text-2xl mb-4">
            <i class="fa-solid fa-qrcode"></i>
        </div>
        <h4 class="font-bold text-cyber-main text-base">No claim references yet</h4>
        <p class="text-xs text-cyber-muted mt-1">Claim references and QR codes will automatically appear here when your order is ready for pickup.</p>
    </div>
    @else
    <div class="{{ $claims->count() === 1 ? 'max-w-lg mx-auto' : ($claims->count() === 2 ? 'max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6' : 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6') }}">
        @foreach($claims as $claim)
        <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden hover:border-cyan-500/40 transition flex flex-col">
            {{-- Card Header --}}
            <div class="px-6 py-4 border-b border-cyber flex items-center justify-between bg-cyber-sub/30">
                <div>
                    <p class="text-[10px] uppercase font-bold text-cyber-muted tracking-wider">Order</p>
                    <p class="font-black text-cyber-main font-display text-lg">#{{ $claim->order->order_number ?? '—' }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 text-xs font-bold rounded-lg uppercase tracking-wider {{ $claim->is_claimed ? 'bg-slate-500/15 text-slate-400 border border-slate-500/20' : 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' }}">
                        {{ $claim->is_claimed ? 'Claimed' : 'Ready for Pickup' }}
                    </span>
                    <a href="{{ route('customer.claiming.show', $claim->order_id) }}" 
                       class="h-8 w-8 rounded-lg bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyan-400 flex items-center justify-center text-sm transition"
                       title="Print Slip">
                        <i class="fa-solid fa-print"></i>
                    </a>
                </div>
            </div>

            {{-- Direct QR Code Section --}}
            <div class="py-6 px-4 bg-cyber-sub/20 border-b border-cyber flex flex-col items-center justify-center relative">
                @if($claim->is_claimed)
                    <div class="relative p-4 bg-white rounded-2xl shadow-sm border border-slate-200 opacity-60">
                        <canvas id="qr-claim-{{ $claim->id }}" class="qr-canvas w-44 h-44 sm:w-48 sm:h-48 filter grayscale" data-code="{{ $claim->claim_code }}"></canvas>
                        <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-[1px] rounded-2xl flex flex-col items-center justify-center text-white">
                            <i class="fa-solid fa-circle-check text-3xl text-emerald-400 mb-1.5"></i>
                            <span class="text-sm font-black uppercase tracking-widest text-emerald-300">Claimed</span>
                        </div>
                    </div>
                @else
                    <div class="qr-clickable-box p-4 bg-white rounded-2xl shadow-lg border border-slate-200/90 inline-flex flex-col items-center cursor-pointer group hover:scale-[1.02] hover:shadow-cyan-500/15 transition"
                         data-code="{{ $claim->claim_code }}"
                         data-order="{{ $claim->order->order_number ?? '' }}"
                         title="Click to enlarge QR code">
                        <canvas id="qr-claim-{{ $claim->id }}" class="qr-canvas w-44 h-44 sm:w-48 sm:h-48" data-code="{{ $claim->claim_code }}"></canvas>
                        <span class="mt-2.5 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-slate-600 bg-slate-100 group-hover:bg-blue-50 group-hover:text-blue-600 transition">
                            <i class="fa-solid fa-magnifying-glass-plus text-[10px]"></i> Tap to Enlarge
                        </span>
                    </div>
                @endif

                {{-- Claim Reference Code with Copy button --}}
                <div class="mt-4 text-center">
                    <span class="text-[11px] uppercase font-bold text-cyber-muted tracking-wider block">Claim Reference Code</span>
                    <div class="inline-flex items-center gap-2.5 mt-1.5 bg-cyber-card px-4 py-2 rounded-xl border border-cyber">
                        <span class="text-base sm:text-lg font-black font-mono tracking-widest text-cyber-main">{{ $claim->claim_code }}</span>
                        <button type="button" 
                                id="copy-btn-{{ $claim->id }}" 
                                onclick="copyClaimCode('{{ $claim->claim_code }}', 'copy-btn-{{ $claim->id }}')" 
                                class="text-cyber-muted hover:text-cyan-400 text-sm transition cursor-pointer p-0.5" 
                                title="Copy code">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Order Details --}}
            <div class="p-6">
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-1 border-b border-cyber/50">
                        <span class="text-cyber-muted font-medium">Service</span>
                        <span class="font-bold text-cyber-main text-right">{{ $claim->order->printRequest->service ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-cyber/50">
                        <span class="text-cyber-muted font-medium">Pickup Branch</span>
                        <span class="font-bold text-cyber-main text-right">{{ $claim->pickup_branch ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-cyber/50">
                        <span class="text-cyber-muted font-medium">Completion</span>
                        <span class="font-bold text-cyber-main">{{ $claim->completion_date?->format('M d, Y') ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1 {{ $claim->is_claimed ? 'border-b border-cyber/50' : '' }}">
                        <span class="text-cyber-muted font-medium">Payment Method</span>
                        <span class="font-bold text-cyber-main">{{ $claim->order->payment->payment_method ?? 'Cash on Pickup' }}</span>
                    </div>
                    @if($claim->is_claimed)
                    <div class="flex justify-between py-1">
                        <span class="text-cyber-muted font-medium">Claimed At</span>
                        <span class="font-bold text-cyber-main">{{ $claim->claimed_at?->format('M d, Y h:i A') }}</span>
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
<div id="qr-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" onclick="if(event.target === this) closeEnlargedQr()">
    <div class="bg-cyber-card border border-cyber rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl relative space-y-4">
        <button type="button" onclick="closeEnlargedQr()" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main flex items-center justify-center text-sm transition cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div>
            <span class="text-[10px] uppercase font-bold text-cyan-400 tracking-wider">Present to Counter Staff</span>
            <h3 class="text-xl font-black text-cyber-main font-display mt-0.5" id="modal-order-num">#ORD-001</h3>
        </div>
        <div class="p-4 bg-white rounded-2xl shadow-inner inline-block mx-auto border-2 border-slate-200">
            <canvas id="modal-qr-canvas" class="w-56 h-56 mx-auto"></canvas>
        </div>
        <div>
            <p class="text-[10px] uppercase font-bold text-cyber-muted tracking-wider">Claim Reference Code</p>
            <p class="text-xl font-black font-mono text-cyan-400 tracking-widest mt-1" id="modal-claim-code">CLM-XXXXXX</p>
        </div>
        <p class="text-xs text-cyber-muted">Hold your screen up to the scanner at the branch pickup counter.</p>
        <button type="button" onclick="closeEnlargedQr()" class="w-full py-2.5 bg-cyber-sub hover:bg-cyber-card border border-cyber rounded-xl text-cyber-main font-bold text-xs uppercase tracking-wider transition cursor-pointer">
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
            btn.innerHTML = '<i class="fa-solid fa-check text-emerald-400"></i>';
            setTimeout(function() {
                btn.innerHTML = originalHtml;
            }, 1800);
        }
    }
</script>
@endsection
