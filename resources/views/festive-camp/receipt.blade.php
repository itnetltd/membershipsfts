{{-- resources/views/festive-camp/receipt.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-100 leading-tight">
            Festive Camp Receipt
        </h2>
    </x-slot>

    @php
        $amountPaid = (int) ($registration->amount_paid ?? 0);
        $balance    = max($campFee - $amountPaid, 0);

        // Build absolute URLs (helps QR/image loading on production behind HTTPS/proxy)
        $verifyUrl = url(route('festive-camp.verify', $registration->verification_token, false));
        $qrUrl     = url(route('festive-camp.qr', $registration, false));

        // Payment label (informational, does NOT replace your status field)
        $paymentLabel = 'Unpaid';
        if ($amountPaid >= $campFee) $paymentLabel = 'Paid';
        elseif ($amountPaid > 0)     $paymentLabel = 'Partial';
    @endphp

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Top print + status bar --}}
            <div class="mb-4 flex items-center justify-between">
                <div class="text-xs text-slate-400">
                    Receipt generated on {{ now()->format('d M Y H:i') }}
                </div>
                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center px-3 py-1.5 rounded-md bg-slate-900 text-white text-xs font-medium shadow-sm hover:bg-slate-800 print:hidden"
                >
                    Print / Save as PDF
                </button>
            </div>

            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200/70 dark:border-slate-700/70">
                <div class="px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-700 flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <img
                            src="{{ asset('images/sfts-logo.png') }}"
                            alt="Shoot For The Stars"
                            class="h-12 w-12 rounded-full bg-slate-900 p-1"
                        >
                        <div>
                            <div class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                                Shoot For The Stars Basketball Academy
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                X-Mas & New Year Festive Camp · Green Hills Academy Indoor Gymnasium
                            </div>
                        </div>
                    </div>

                    <div class="text-right text-xs">
                        <div class="font-mono text-slate-500">
                            Receipt # {{ 'SFTS-FC-' . str_pad($registration->id, 4, '0', STR_PAD_LEFT) }}
                        </div>

                        <div class="mt-1 flex flex-col items-end gap-1">
                            {{-- Status pill (your existing logic) --}}
                            @if($registration->status === 'approved')
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold text-emerald-800">
                                    Paid & Approved
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-[11px] font-semibold text-amber-800">
                                    Pending Confirmation
                                </span>
                            @endif

                            {{-- Payment label (based on amount_paid) --}}
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-semibold
                                @if($paymentLabel === 'Paid')
                                    bg-emerald-50 text-emerald-700 border border-emerald-200
                                @elseif($paymentLabel === 'Partial')
                                    bg-yellow-50 text-yellow-800 border border-yellow-200
                                @else
                                    bg-slate-50 text-slate-700 border border-slate-200
                                @endif
                            ">
                                {{ $paymentLabel }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6 text-sm text-slate-900 dark:text-slate-100 space-y-6">

                    {{-- Player & Guardian info --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Player Details
                            </h3>

                            {{-- Player photo + details --}}
                            <div class="mt-2 flex items-start gap-4">
                                @if(!empty($registration->player_photo_path))
                                    <img
                                        src="{{ asset('storage/'.$registration->player_photo_path) }}"
                                        alt="Player photo"
                                        class="h-20 w-20 rounded-xl object-cover border border-slate-200"
                                    >
                                @else
                                    <div class="h-20 w-20 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] text-slate-400">
                                        No photo
                                    </div>
                                @endif

                                <dl class="space-y-1.5">
                                    <div>
                                        <dt class="text-xs text-slate-500">Name</dt>
                                        <dd class="font-medium">{{ $registration->player_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-slate-500">Age / Category</dt>
                                        <dd>
                                            {{ $registration->age ? $registration->age . ' years' : '—' }}
                                            @if($registration->category)
                                                · <span class="text-slate-600 dark:text-slate-300">{{ $registration->category }}</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-slate-500">School</dt>
                                        <dd>{{ $registration->school ?? '—' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-slate-500">Location</dt>
                                        <dd>{{ $registration->location ?? 'Kigali / Gasabo / Remera' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Parent / Guardian
                            </h3>
                            <dl class="mt-2 space-y-1.5">
                                <div>
                                    <dt class="text-xs text-slate-500">Name</dt>
                                    <dd class="font-medium">{{ $registration->guardian_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-500">WhatsApp / Phone</dt>
                                    <dd>{{ $registration->guardian_phone }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-500">Email</dt>
                                    <dd>{{ $registration->guardian_email ?? '—' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- Camp details --}}
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Camp Details
                        </h3>
                        <dl class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <dt class="text-xs text-slate-500">Dates</dt>
                                <dd>15 Dec 2025 – 2 Jan 2026</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">Schedule</dt>
                                <dd>Mon · Wed · Fri · 10:00 – 12:00</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">Venue</dt>
                                <dd>Green Hills Academy Indoor Gymnasium</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Payment summary --}}
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Payment Summary
                        </h3>

                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="text-sm">
                                <div class="text-xs text-slate-500">Payment Method</div>
                                <div class="font-medium">
                                    {{ $registration->payment_method ?? 'MoMo' }}
                                    @if($registration->payment_phone)
                                        · {{ $registration->payment_phone }}
                                    @endif
                                </div>
                                @if($registration->payment_reference)
                                    <div class="text-xs text-slate-500 mt-1">
                                        Ref: {{ $registration->payment_reference }}
                                    </div>
                                @endif
                            </div>

                            <div class="sm:justify-self-end w-full sm:w-[240px]">
                                <div class="space-y-1.5 text-xs">
                                    <div class="flex items-center justify-between text-slate-500">
                                        <span>Camp fee (1 child)</span>
                                        <span>{{ number_format($campFee, 0, ',', ' ') }} RWF</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500">Amount paid</span>
                                        <span class="font-semibold text-slate-900 dark:text-slate-100">
                                            {{ number_format($amountPaid, 0, ',', ' ') }} RWF
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500">Balance</span>
                                        <span class="font-semibold {{ $balance > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                                            {{ number_format($balance, 0, ',', ' ') }} RWF
                                        </span>
                                    </div>

                                    <div class="mt-2 border-t border-slate-200 dark:border-slate-700 pt-2 flex items-center justify-between">
                                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-100">
                                            Total
                                        </span>
                                        <span class="text-base font-semibold text-slate-900 dark:text-slate-50">
                                            {{ number_format($campFee, 0, ',', ' ') }} RWF
                                        </span>
                                    </div>

                                    <div class="mt-1 text-[11px] text-slate-500">
                                        @if($registration->status === 'approved')
                                            Marked as <span class="font-semibold text-emerald-700">APPROVED</span> by SFTS admin.
                                        @else
                                            Status: <span class="font-semibold text-amber-700">Pending confirmation</span>.
                                            Please ensure payment of {{ number_format($campFee, 0, ',', ' ') }} RWF to MoMo
                                            <span class="font-mono">0788448596</span>.
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    @if(!empty($registration->notes))
                        <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Notes
                            </h3>
                            <p class="mt-2 text-sm text-slate-700 dark:text-slate-200">
                                {{ $registration->notes }}
                            </p>
                        </div>
                    @endif

                    {{-- QR verification block --}}
                    @if(!empty($registration->verification_token))
                        <div class="border-t border-slate-100 dark:border-slate-700 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Verification
                                </h3>
                                <p class="mt-1 text-xs text-slate-600 dark:text-slate-300">
                                    Scan this QR code at the camp entrance to verify that this receipt is authentic
                                    and linked to <span class="font-semibold">{{ $registration->player_name }}</span>.
                                </p>

                                {{-- Fallback clickable URL (important if QR image fails to load) --}}
                                <p class="mt-2 text-[11px] text-slate-500 break-all">
                                    If QR does not open, use this link:
                                    <a href="{{ $verifyUrl }}" class="text-slate-900 dark:text-slate-100 font-semibold underline">
                                        {{ $verifyUrl }}
                                    </a>
                                </p>
                            </div>

                            <div class="shrink-0 flex flex-col items-center gap-2">
                                <img
                                    src="{{ $qrUrl }}"
                                    alt="Verification QR code"
                                    class="h-32 w-32 border border-slate-200 rounded-lg bg-white"
                                    loading="lazy"
                                >
                                <div class="text-[11px] text-slate-500">
                                    Scan to verify
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Footer note --}}
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4 text-xs text-slate-500">
                        Please present this receipt (printed or on your phone) at the Festive Camp check-in desk.
                        <br>
                        For any questions, contact the SFTS staff at the venue or via WhatsApp.
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
