<x-app-layout> 
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-100 leading-tight">
            {{ __('My Festive Camp Registrations') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-md bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Top bar --}}
            <div class="mb-4 flex items-center justify-between">
                <p class="text-xs sm:text-sm text-slate-300">
                    You can register more than one child under your account.
                </p>
                <a href="{{ route('festive-camp.register') }}"
                   class="inline-flex items-center px-3 py-1.5 rounded-md bg-yellow-400 text-slate-950 hover:bg-yellow-300 text-xs font-medium shadow-sm">
                    + Register another kid
                </a>
            </div>

            @if(isset($registrations) && $registrations->count())
                @foreach($registrations as $registration)
                    <div class="mb-6 bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-lg border border-slate-200/70 dark:border-slate-700/70">
                        <div class="p-6 text-slate-900 dark:text-slate-100">

                            {{-- Header --}}
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-semibold">
                                    {{ $registration->player_name }}
                                    @if($registration->age)
                                        <span class="text-sm font-normal text-slate-500">
                                            ({{ $registration->age }} years)
                                        </span>
                                    @endif
                                </h3>

                                {{-- Status --}}
                                <div>
                                    @if($registration->status === 'approved')
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                            Approved
                                        </span>
                                    @elseif($registration->status === 'pending')
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-800">
                                            {{ ucfirst($registration->status ?? 'pending') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Details --}}
                            <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <dt class="font-medium text-slate-500">Category</dt>
                                    <dd>{{ $registration->category ?? 'N/A' }}</dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">School</dt>
                                    <dd>{{ $registration->school ?? 'N/A' }}</dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">Location</dt>
                                    <dd>{{ $registration->location ?? 'N/A' }}</dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">Guardian</dt>
                                    <dd>{{ $registration->guardian_name }}</dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">WhatsApp / Phone</dt>
                                    <dd>{{ $registration->guardian_phone }}</dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">Email</dt>
                                    <dd>{{ $registration->guardian_email ?? 'N/A' }}</dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">MoMo Phone</dt>
                                    <dd>{{ $registration->payment_phone ?? 'N/A' }}</dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">Payment Ref</dt>
                                    <dd>{{ $registration->payment_reference ?? 'N/A' }}</dd>
                                </div>

                                <div class="sm:col-span-2">
                                    <dt class="font-medium text-slate-500">Notes</dt>
                                    <dd>{{ $registration->notes ?: '—' }}</dd>
                                </div>
                            </dl>

                            {{-- Actions --}}
                            <div class="mt-6 border-t border-slate-200 dark:border-slate-700 pt-4">

                                @if($registration->status === 'approved')
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <p class="text-xs text-slate-500">
                                            Payment of <strong>50,000 RWF</strong> confirmed.
                                            Please bring your receipt on the first day of camp.
                                        </p>

                                        <a href="{{ route('festive-camp.receipt', $registration) }}"
                                           class="inline-flex items-center px-4 py-2 rounded-md bg-slate-900 text-white hover:bg-slate-800 text-xs font-medium">
                                            View / Download Receipt
                                        </a>
                                    </div>
                                @else
                                    <p class="text-xs text-slate-500 mb-3">
                                        Please pay <strong>50,000 RWF</strong> to MoMo
                                        <span class="font-mono">0788448596</span>.
                                        Admin will approve after confirmation.
                                    </p>

                                    {{-- Edit / Delete (ONLY when pending) --}}
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('festive-camp.edit', $registration) }}"
                                           class="inline-flex items-center px-3 py-1.5 rounded-md bg-sky-100 text-sky-800 text-xs font-medium hover:bg-sky-200">
                                            Edit
                                        </a>

                                        <form action="{{ route('festive-camp.destroy', $registration) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this registration?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 rounded-md bg-red-100 text-red-800 text-xs font-medium hover:bg-red-200">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-lg border border-slate-200/70 dark:border-slate-700/70">
                    <div class="p-6">
                        <p>You don’t have a Festive Camp registration yet.</p>
                        <div class="mt-4">
                            <a href="{{ route('festive-camp.register') }}"
                               class="inline-flex items-center px-4 py-2 rounded-md bg-yellow-400 text-slate-950 hover:bg-yellow-300 text-sm font-medium">
                                Register for Festive Camp
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
