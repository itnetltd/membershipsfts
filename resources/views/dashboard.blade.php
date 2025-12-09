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

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-slate-900 text-sm">
                            Festive Camp Registration
                        </div>

                        @if($hasFestive)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Registered · {{ $festiveCount }} kid{{ $festiveCount > 1 ? 's' : '' }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600">
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

                        <div class="mt-4 flex flex-col gap-1">
                            <a href="{{ route('festive-camp.my') }}"
                               class="text-xs font-medium text-slate-900 hover:underline">
                                View registrations & receipts
                            </a>
                            <a href="{{ route('festive-camp.register') }}"
                               class="text-[11px] font-medium text-slate-600 hover:underline">
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
                        <span>Mobile Money & card payment integration planned.</span>
                    </div>
                </div>

                {{-- Teams & Programs --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-slate-900 text-sm">
                            Teams & Programs
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
                            View teams & categories
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
