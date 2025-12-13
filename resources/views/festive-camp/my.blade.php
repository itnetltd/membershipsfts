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

            {{-- Top bar: total + "add another kid" --}}
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
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-semibold">
                                    {{ $registration->player_name }}
                                    @if($registration->age)
                                        <span class="text-sm font-normal text-slate-500">
                                            ({{ $registration->age }} years)
                                        </span>
                                    @endif
                                </h3>

                                {{-- Status pill --}}
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

                            <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <dt class="font-medium text-slate-500">Category</dt>
                                    <dd class="text-slate-900 dark:text-slate-100">
                                        {{ $registration->category ?? 'N/A' }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">School</dt>
                                    <dd class="text-slate-900 dark:text-slate-100">
                                        {{ $registration->school ?? 'N/A' }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">Location</dt>
                                    <dd class="text-slate-900 dark:text-slate-100">
                                        {{ $registration->location ?? 'N/A' }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">Guardian Name</dt>
                                    <dd class="text-slate-900 dark:text-slate-100">
                                        {{ $registration->guardian_name }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">Guardian Phone (WhatsApp)</dt>
                                    <dd class="text-slate-900 dark:text-slate-100">
                                        {{ $registration->guardian_phone }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">Guardian Email</dt>
                                    <dd class="text-slate-900 dark:text-slate-100">
                                        {{ $registration->guardian_email ?? 'N/A' }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">Payment Phone (MoMo)</dt>
                                    <dd class="text-slate-900 dark:text-slate-100">
                                        {{ $registration->payment_phone ?? 'N/A' }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="font-medium text-slate-500">Payment Reference</dt>
                                    <dd class="text-slate-900 dark:text-slate-100">
                                        {{ $registration->payment_reference ?? 'N/A' }}
                                    </dd>
                                </div>

                                <div class="sm:col-span-2">
                                    <dt class="font-medium text-slate-500">Notes</dt>
                                    <dd class="text-slate-900 dark:text-slate-100">
                                        {{ $registration->notes ?: '—' }}
                                    </dd>
                                </div>
                            </dl>

                            {{-- Payment / receipt message --}}
                            @if($registration->status === 'approved')
                                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <p class="text-xs text-slate-500">
                                        Your payment of
                                        <span class="font-semibold text-slate-900 dark:text-slate-100">50,000 RWF</span>
                                        has been confirmed. Please bring your receipt on the first day of camp.
                                    </p>
                                    <a href="{{ route('festive-camp.receipt', $registration) }}"
                                       class="inline-flex items-center px-4 py-2 rounded-md bg-slate-900 text-white hover:bg-slate-800 text-xs font-medium">
                                        View / Download Receipt
                                    </a>
                                </div>
                            @else
                                <p class="mt-6 text-xs text-slate-500">
                                    To confirm this spot, please pay
                                    <span class="font-semibold text-slate-900 dark:text-slate-100">50,000 RWF</span>
                                    to MoMo
                                    <span class="font-mono">0788448596</span>.
                                    The admin will mark the status as <strong>approved</strong> after confirming payment.
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-lg border border-slate-200/70 dark:border-slate-700/70">
                    <div class="p-6 text-slate-900 dark:text-slate-100">
                        <p>You don’t have a Festive Camp registration yet.</p>
                        <p class="mt-3 text-sm text-slate-500">
                            Go to the registration form to register your child for the camp.
                        </p>
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
