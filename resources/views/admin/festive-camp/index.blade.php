<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Festive Camp – Registered Campers
        </h2>
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-sm flex items-center justify-between">
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">
                        Income Summary (Fee per child: {{ number_format($campFee, 0, ',', ' ') }} RWF)
                    </p>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span>Expected total (all registered)</span>
                            <span class="font-semibold">
                                {{ number_format($expectedTotal, 0, ',', ' ') }} RWF
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-emerald-700">
                            <span>Paid / approved</span>
                            <span class="font-semibold">
                                {{ number_format($paidAmount, 0, ',', ' ') }} RWF
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-amber-700">
                            <span>Unpaid / pending & other</span>
                            <span class="font-semibold">
                                {{ number_format($unpaidAmount, 0, ',', ' ') }} RWF
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
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
                                <th class="py-2 pr-4">Status</th>
                                <th class="py-2 pr-4">Registered at</th>
                                <th class="py-2 pr-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($campers as $index => $camper)
                                <tr>
                                    <td class="py-2 pr-4 text-xs text-slate-500">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="py-2 pr-4">
                                        <div class="font-semibold">{{ $camper->player_name }}</div>
                                    </td>
                                    <td class="py-2 pr-4 text-xs">
                                        <div>Age: {{ $camper->age ?? '-' }}</div>
                                        <div class="text-slate-500">Cat: {{ $camper->category ?? '-' }}</div>
                                    </td>
                                    <td class="py-2 pr-4 text-xs text-slate-700">
                                        {{ $camper->school ?? '-' }}
                                    </td>
                                    <td class="py-2 pr-4 text-xs">
                                        <div>{{ $camper->guardian_name }}</div>
                                        <div class="text-slate-500">{{ $camper->guardian_email ?? '' }}</div>
                                    </td>
                                    <td class="py-2 pr-4 text-xs">
                                        {{ $camper->guardian_phone }}
                                    </td>
                                    <td class="py-2 pr-4 text-xs">
                                        <div>{{ $camper->payment_method }} {{ $camper->payment_phone ? '('.$camper->payment_phone.')' : '' }}</div>
                                        <div class="text-slate-500">{{ $camper->payment_reference ?? '' }}</div>
                                    </td>
                                    <td class="py-2 pr-4 text-xs">
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
                                    <td class="py-2 pr-4 text-xs text-slate-500">
                                        {{ $camper->created_at?->format('d M Y H:i') }}
                                    </td>
                                    <td class="py-2 pr-4 text-xs">
                                        @if($camper->status !== 'approved')
                                            <form
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
                                    <td colspan="10" class="py-6 text-center text-sm text-slate-500">
                                        No campers registered yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
