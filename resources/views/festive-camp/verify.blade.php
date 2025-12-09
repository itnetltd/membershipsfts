{{-- resources/views/festive-camp/verify.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-100 leading-tight">
            Festive Camp Receipt Verification
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200/70 dark:border-slate-700/70">
                <div class="p-6 text-sm text-slate-900 dark:text-slate-100">

                    @if (! $valid)
                        {{-- ❌ Invalid / unknown QR --}}
                        <div class="text-center">
                            <div class="text-3xl mb-2">❌</div>
                            <h3 class="text-lg font-semibold text-red-700">
                                Invalid or Unknown Receipt
                            </h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                                This QR code does not match any Festive Camp registration
                                in the system. Please ask the parent to contact the SFTS
                                camp coordinator or present another receipt.
                            </p>
                        </div>
                    @else
                        {{-- ✅ Valid registration --}}
                        <div class="flex items-start gap-4">
                            @if($registration->player_photo_path)
                                <img
                                    src="{{ asset('storage/'.$registration->player_photo_path) }}"
                                    alt="Player photo"
                                    class="h-16 w-16 rounded-xl object-cover border border-slate-200"
                                >
                            @endif

                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <h3 class="text-lg font-semibold">
                                            {{ $registration->player_name }}
                                        </h3>
                                        <p class="text-xs text-slate-500">
                                            Age: {{ $registration->age ?? '—' }}
                                            @if($registration->category)
                                                · Category: {{ $registration->category }}
                                            @endif
                                        </p>
                                        @if($registration->school)
                                            <p class="text-xs text-slate-500">
                                                School: {{ $registration->school }}
                                            </p>
                                        @endif
                                        @if($registration->location)
                                            <p class="text-xs text-slate-500">
                                                Location: {{ $registration->location }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Status pill --}}
                                    <div class="shrink-0 text-right">
                                        @if($registration->status === 'approved')
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold text-emerald-800">
                                                Valid & Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-[11px] font-semibold text-amber-800">
                                                Valid · Pending Approval
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 border-t border-slate-200 dark:border-slate-700 pt-4 space-y-2">
                            <p class="text-xs text-slate-500 uppercase tracking-wide">
                                Receipt Status
                            </p>

                            @if($registration->status === 'approved')
                                <p class="text-sm">
                                    ✅ This receipt is <span class="font-semibold text-emerald-700">AUTHENTIC</span>
                                    and the registration is marked as <span class="font-semibold">PAID & APPROVED</span>
                                    in the SFTS system.
                                </p>
                            @else
                                <p class="text-sm">
                                    ⚠️ This receipt is authentic, but the registration is
                                    <span class="font-semibold text-amber-700">not yet approved</span>.
                                    Please confirm payment with the SFTS admin before allowing full participation.
                                </p>
                            @endif

                            <p class="text-xs text-slate-500 mt-3">
                                Registered at:
                                {{ $registration->created_at?->format('d M Y H:i') }}
                            </p>
                            <p class="text-xs text-slate-500">
                                Guardian: {{ $registration->guardian_name }}
                                · Phone (WhatsApp): {{ $registration->guardian_phone }}
                            </p>
                            @if($registration->payment_phone || $registration->payment_reference)
                                <p class="text-xs text-slate-500">
                                    Payment: {{ $registration->payment_method ?? 'MoMo' }}
                                    @if($registration->payment_phone)
                                        · {{ $registration->payment_phone }}
                                    @endif
                                    @if($registration->payment_reference)
                                        · Ref: {{ $registration->payment_reference }}
                                    @endif
                                </p>
                            @endif
                        </div>

                        <div class="mt-4 border-t border-slate-200 dark:border-slate-700 pt-3 text-[11px] text-slate-500">
                            For SFTS staff only: if details on this page do not match
                            the printed receipt or the child at check-in, please
                            contact the camp coordinator before admitting the player.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
