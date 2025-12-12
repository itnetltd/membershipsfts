{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Welcome + main CTA --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                <div class="px-6 py-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div>
                        <div class="text-sm font-semibold text-slate-500 uppercase tracking-[0.18em]">
                            Shoot For The Stars · Festive Camp
                        </div>
                        <h3 class="mt-1 text-xl font-semibold text-slate-900">
                            Welcome, {{ Str::headline(auth()->user()->name ?? 'Coach/Parent') }}.
                        </h3>
                        <p class="mt-2 text-sm text-slate-600 max-w-xl">
                            Register your child for the X-Mas & New Year Festive Camp at Green Hills Academy Indoor Gymnasium
                            and follow up their spot and payment status.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route('festive-camp.register') }}"
                            class="inline-flex items-center px-5 py-2.5 rounded-lg bg-yellow-400 text-slate-950 text-sm font-medium shadow-sm hover:bg-yellow-300 transition"
                        >
                            Register for Festive Camp
                        </a>
                    </div>

                </div>
            </div>

            {{-- 4 key cards row --}}
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">

                {{-- Festive Camp Registration (repurposed from Player Application) --}}
                @php
                    $festiveCount    = $festiveCount ?? 0;
                    $festiveApproved = $festiveApproved ?? 0;
                    $hasFestive      = $festiveCount > 0;
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col justify-between overflow-hidden">
                    <div class="flex items-center justify-between mb-3 gap-3">
                        <div class="font-semibold text-slate-900 text-sm">
                            Festive Camp Registration
                        </div>

                        @if($hasFestive)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                                Registered · {{ $festiveCount }} kid{{ $festiveCount > 1 ? 's' : '' }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600 shrink-0">
                                Not registered yet
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-slate-600">
                        Sign up your player for the 15 Dec 2025 – 2 Jan 2026 festive camp (Mon, Wed & Fri · 10:00–12:00)
                        at Green Hills Academy Indoor Gymnasium.
                    </p>

                    @if($hasFestive)
                        <div class="mt-4 space-y-1 text-xs text-slate-600">
                            <p>
                                Approved registrations:
                                <span class="font-semibold text-slate-900">{{ $festiveApproved }}</span>
                            </p>
                            <p class="text-slate-500">
                                Approved kids can download a receipt to present at the camp.
                            </p>
                        </div>

                        {{-- ✅ Highlighted CTA (fixed: no overflow / no overlap) --}}
                        <div class="mt-4 space-y-2">
                            <div class="max-w-full inline-flex items-center gap-2 text-[11px] font-semibold
                                        text-emerald-700 bg-emerald-50 border border-emerald-200
                                        px-3 py-1 rounded-full">
                                <span class="shrink-0">Important</span>
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                <span class="truncate">Receipts available after approval</span>
                            </div>

                            <a href="{{ route('festive-camp.my') }}"
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl
                                      bg-emerald-600 text-white font-semibold text-sm shadow
                                      hover:bg-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-200 transition">
                                <span class="text-center leading-tight">View registrations &amp; receipts</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-95" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>

                            <a href="{{ route('festive-camp.register') }}"
                               class="text-[12px] font-medium text-slate-700 hover:underline">
                                + Register another kid
                            </a>
                        </div>
                    @else
                        <div class="mt-4">
                            <a href="{{ route('festive-camp.register') }}"
                               class="text-xs font-medium text-slate-900 hover:underline">
                                Manage Festive Camp registration
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Documents --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-slate-900 text-sm">
                            Player Documents
                        </div>
                        <div class="text-xs text-slate-500">
                            Uploaded: 0
                        </div>
                    </div>
                    <ul class="text-sm text-slate-600 space-y-1.5">
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-yellow-400"></span>
                            Birth certificate
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                            Player photo (passport style)
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            Medical / insurance document
                        </li>
                    </ul>
                    <div class="mt-4">
                        <a href="#"
                           class="text-xs font-medium text-slate-900 hover:underline">
                            Upload / view documents
                        </a>
                    </div>
                </div>

                {{-- Payments --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-slate-900 text-sm">
                            Payments
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-yellow-50 text-yellow-700 border border-yellow-100">
                            Coming soon
                        </span>
                    </div>
                    <p class="text-sm text-slate-600">
                        Track camp or membership payments, see balances, and view payment history once payment tracking is enabled.
                    </p>
                    <div class="mt-4 text-xs text-slate-500 flex items-center gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-yellow-400"></span>
                        <span>Mobile Money &amp; card payment integration planned.</span>
                    </div>
                </div>

                {{-- Teams & Programs --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-slate-900 text-sm">
                            Teams &amp; Programs
                        </div>
                        <div class="text-xs text-slate-500">
                            SFTS Academy
                        </div>
                    </div>
                    <p class="text-sm text-slate-600">
                        See which team and age category your player belongs to, and which programs they’re enrolled in:
                        Practice Only, Full Program + Game Day, or Shooting Clinics.
                    </p>
                    <div class="mt-4">
                        <a href="#"
                           class="text-xs font-medium text-slate-900 hover:underline">
                            View teams &amp; categories
                        </a>
                    </div>
                </div>
            </div>

            {{-- Recent section --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-sm text-slate-900">
                        Recent Activity
                    </h3>
                    <span class="text-xs text-slate-500">
                        Last 30 days
                    </span>
                </div>
                <div class="px-6 py-6 text-sm text-slate-500">
                    No recent updates yet. Once you start registration, upload documents, or record payments,
                    they will appear here.
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
