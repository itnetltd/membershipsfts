{{-- resources/views/admin/festive-camp/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                Festive Camp – Registered Campers
            </h2>

            <div class="text-xs text-slate-500">
                Fee/child: <span class="font-semibold">{{ number_format($campFee, 0, ',', ' ') }} RWF</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Success message --}}
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TOP SUMMARY: CAPACITY + INCOME --}}
            <div class="grid gap-4 md:grid-cols-2">
                {{-- Capacity / registrations --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100 p-4 text-sm flex items-center justify-between">
                    <div>
                        <p>Total capacity: <strong>{{ $capacity }}</strong> kids</p>
                        <p>Registered: <strong>{{ $registered }}</strong></p>
                        <p class="mt-1 text-xs text-slate-500">
                            Approved: <strong>{{ $approved }}</strong> ·
                            Pending: <strong>{{ $pending }}</strong> ·
                            Other: <strong>{{ $other }}</strong>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Remaining spots</p>
                        <p class="text-lg font-bold {{ $remaining > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $remaining }} spots
                        </p>
                    </div>
                </div>

                {{-- Income summary --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100 p-4 text-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">
                        Income Summary
                    </p>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span>Expected total (all registered)</span>
                            <span class="font-semibold">{{ number_format($expectedTotal, 0, ',', ' ') }} RWF</span>
                        </div>

                        <div class="flex items-center justify-between text-emerald-700">
                            <span>Paid / approved</span>
                            <span class="font-semibold">{{ number_format($paidAmount, 0, ',', ' ') }} RWF</span>
                        </div>

                        <div class="flex items-center justify-between text-amber-700">
                            <span>Unpaid / pending & other</span>
                            <span class="font-semibold">{{ number_format($unpaidAmount, 0, ',', ' ') }} RWF</span>
                        </div>
                    </div>

                    <div class="mt-3 text-[11px] text-slate-500">
                        Note: Paid amount is based on approved registrations (or amount_paid if you enter it).
                    </div>
                </div>
            </div>

            {{-- MOBILE VIEW (cards) --}}
            <div class="space-y-3 lg:hidden">
                @forelse($campers as $camper)
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">
                                    {{ $camper->player_name }}
                                </div>

                                <div class="mt-1 text-xs text-slate-600">
                                    Age: <span class="font-medium">{{ $camper->age ?? '-' }}</span>
                                    <span class="mx-2 text-slate-300">•</span>
                                    Cat: <span class="font-medium">{{ $camper->category ?? '-' }}</span>
                                </div>

                                <div class="mt-1 text-xs text-slate-600">
                                    Parent: <span class="font-medium">{{ $camper->guardian_name }}</span>
                                </div>

                                <div class="mt-1 text-xs text-slate-600">
                                    Phone: <span class="font-medium">{{ $camper->guardian_phone }}</span>
                                </div>

                                <div class="mt-1 text-[11px] text-slate-500">
                                    Registered: {{ $camper->created_at?->format('d M Y H:i') }}
                                </div>
                            </div>

                            <div class="shrink-0">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold
                                    @if($camper->status === 'approved')
                                        bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($camper->status === 'pending')
                                        bg-yellow-50 text-yellow-800 border border-yellow-200
                                    @else
                                        bg-slate-50 text-slate-700 border border-slate-200
                                    @endif">
                                    {{ ucfirst($camper->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-3 grid grid-cols-1 gap-3">
                            <div class="text-xs text-slate-600">
                                Payment: <span class="font-medium">{{ $camper->payment_method }}</span>
                                @if($camper->payment_phone)
                                    <span class="text-slate-400">({{ $camper->payment_phone }})</span>
                                @endif
                                @if($camper->payment_reference)
                                    <div class="text-[11px] text-slate-500">Ref: {{ $camper->payment_reference }}</div>
                                @endif
                            </div>

                            {{-- ✅ Amount + Approve in ONE form (so amount is sent on approve) --}}
                            @if($camper->status !== 'approved')
                                <form
                                    action="{{ route('admin.festive-camp.approve', $camper) }}"
                                    method="POST"
                                    onsubmit="return confirm('Approve this camper?');"
                                    class="grid grid-cols-1 gap-2"
                                >
                                    @csrf

                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">
                                            Amount paid (RWF)
                                        </label>
                                        <input
                                            type="number"
                                            name="amount_paid"
                                            min="0"
                                            step="1"
                                            value="{{ old('amount_paid', $camper->amount_paid ?? $campFee) }}"
                                            class="w-full rounded-xl border-slate-200 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                        <div class="mt-1 text-[11px] text-slate-500">
                                            Tip: change it if parent paid less/more than {{ number_format($campFee, 0, ',', ' ') }}.
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700"
                                    >
                                        Approve
                                    </button>
                                </form>
                            @else
                                <div class="grid grid-cols-1 gap-2">
                                    <div class="text-xs text-slate-600">
                                        Amount paid:
                                        <span class="font-semibold text-slate-900">
                                            {{ number_format((int)($camper->amount_paid ?? $campFee), 0, ',', ' ') }} RWF
                                        </span>
                                    </div>
                                    <div class="w-full text-center text-emerald-700 text-sm font-semibold bg-emerald-50 border border-emerald-200 rounded-xl py-2">
                                        Approved
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100 p-6 text-center text-sm text-slate-500">
                        No campers registered yet.
                    </div>
                @endforelse
            </div>

            {{-- DESKTOP VIEW (table) --}}
            <div class="hidden lg:block bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-xs uppercase tracking-wide text-slate-500">
                                <th class="py-2 pr-4">#</th>
                                <th class="py-2 pr-4">Player</th>
                                <th class="py-2 pr-4">Age / Category</th>
                                <th class="py-2 pr-4">School</th>
                                <th class="py-2 pr-4">Parent / Guardian</th>
                                <th class="py-2 pr-4">Phone</th>
                                <th class="py-2 pr-4">Payment</th>
                                <th class="py-2 pr-4">Amount Paid</th>
                                <th class="py-2 pr-4">Status</th>
                                <th class="py-2 pr-4">Registered at</th>
                                <th class="py-2 pr-4">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($campers as $index => $camper)
                                <tr class="align-top">
                                    <td class="py-3 pr-4 text-xs text-slate-500">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="py-3 pr-4">
                                        <div class="font-semibold">{{ $camper->player_name }}</div>
                                    </td>

                                    <td class="py-3 pr-4 text-xs">
                                        <div>Age: {{ $camper->age ?? '-' }}</div>
                                        <div class="text-slate-500">Cat: {{ $camper->category ?? '-' }}</div>
                                    </td>

                                    <td class="py-3 pr-4 text-xs text-slate-700">
                                        {{ $camper->school ?? '-' }}
                                    </td>

                                    <td class="py-3 pr-4 text-xs">
                                        <div class="font-medium">{{ $camper->guardian_name }}</div>
                                        <div class="text-slate-500">{{ $camper->guardian_email ?? '' }}</div>
                                    </td>

                                    <td class="py-3 pr-4 text-xs">
                                        {{ $camper->guardian_phone }}
                                    </td>

                                    <td class="py-3 pr-4 text-xs">
                                        <div>{{ $camper->payment_method }} {{ $camper->payment_phone ? '('.$camper->payment_phone.')' : '' }}</div>
                                        <div class="text-slate-500">{{ $camper->payment_reference ?? '' }}</div>
                                    </td>

                                    {{-- ✅ Amount + Approve in ONE form --}}
                                    <td class="py-3 pr-4 text-xs">
                                        @if($camper->status !== 'approved')
                                            <input
                                                form="approve-form-{{ $camper->id }}"
                                                type="number"
                                                name="amount_paid"
                                                min="0"
                                                step="1"
                                                value="{{ old('amount_paid', $camper->amount_paid ?? $campFee) }}"
                                                class="w-28 rounded-md border-slate-200 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                            >
                                        @else
                                            <span class="font-semibold text-slate-900">
                                                {{ number_format((int)($camper->amount_paid ?? $campFee), 0, ',', ' ') }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-3 pr-4 text-xs">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px]
                                            @if($camper->status === 'approved')
                                                bg-emerald-50 text-emerald-700 border border-emerald-200
                                            @elseif($camper->status === 'pending')
                                                bg-yellow-50 text-yellow-800 border border-yellow-200
                                            @else
                                                bg-slate-50 text-slate-700 border border-slate-200
                                            @endif">
                                            {{ ucfirst($camper->status) }}
                                        </span>
                                    </td>

                                    <td class="py-3 pr-4 text-xs text-slate-500">
                                        {{ $camper->created_at?->format('d M Y H:i') }}
                                    </td>

                                    <td class="py-3 pr-4 text-xs">
                                        @if($camper->status !== 'approved')
                                            <form
                                                id="approve-form-{{ $camper->id }}"
                                                action="{{ route('admin.festive-camp.approve', $camper) }}"
                                                method="POST"
                                                onsubmit="return confirm('Approve this camper?');"
                                            >
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-md bg-emerald-600 text-white text-[11px] font-medium hover:bg-emerald-700"
                                                >
                                                    Approve
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-emerald-600 font-semibold text-[11px]">
                                                Approved
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="py-6 text-center text-sm text-slate-500">
                                        No campers registered yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if(method_exists($campers, 'links'))
                <div class="pt-2">
                    {{ $campers->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
