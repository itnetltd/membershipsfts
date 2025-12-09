{{-- resources/views/festive-camp/register.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Festive Camp Registration
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="text-lg font-semibold text-slate-900">
                        X-Mas & New Year Festive Camp · 15 Dec 2025 – 2 Jan 2026
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Days: <strong>Monday, Wednesday & Friday</strong> · Time: <strong>10:00 – 12:00</strong> · Venue:
                        <strong>Green Hills Academy Indoor Gymnasium</strong>.
                    </p>

                    {{-- Show which parent account is being used --}}
                    <div class="mt-3 text-xs text-slate-600 bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 flex items-center justify-between">
                        <div>
                            You are logged in as
                            <span class="font-semibold text-slate-900">
                                {{ auth()->user()->name }}
                            </span>
                            <span class="text-slate-500">
                                ({{ auth()->user()->email }})
                            </span>.
                            This account will be linked to this player’s Festive Camp registration.
                        </div>
                        <a href="{{ route('profile.edit') }}" class="ml-3 text-[11px] text-sky-600 hover:underline">
                            Edit account
                        </a>
                    </div>
                </div>

                {{-- ✅ added enctype for file upload --}}
                <form action="{{ route('festive-camp.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-6">
                    @csrf

                    {{-- Player section --}}
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 mb-3">
                            Player Information
                        </h4>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Player full name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="player_name" value="{{ old('player_name') }}"
                                       class="mt-1 block w-full rounded-lg border-slate-200 text-sm"
                                       required>
                                @error('player_name')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Age
                                </label>
                                <input type="number" name="age" value="{{ old('age') }}"
                                       class="mt-1 block w-full rounded-lg border-slate-200 text-sm" min="4" max="18">
                                @error('age')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Category
                                </label>
                                <select name="category" class="mt-1 block w-full rounded-lg border-slate-200 text-sm">
                                    <option value="">Select category</option>
                                    <option value="4-7" @selected(old('category') === '4-7')>4–7 years old</option>
                                    <option value="U8"  @selected(old('category') === 'U8')>U8</option>
                                    <option value="U10" @selected(old('category') === 'U10')>U10</option>
                                    <option value="U12" @selected(old('category') === 'U12')>U12</option>
                                    <option value="U14" @selected(old('category') === 'U14')>U14</option>
                                    <option value="U16" @selected(old('category') === 'U16')>U16</option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    School
                                </label>
                                <input type="text" name="school" value="{{ old('school') }}"
                                       class="mt-1 block w-full rounded-lg border-slate-200 text-sm">
                                @error('school')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- New location field --}}
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Location (City / District / Sector)
                                </label>
                                <input type="text" name="location"
                                       value="{{ old('location', 'Kigali / Gasabo / Remera') }}"
                                       class="mt-1 block w-full rounded-lg border-slate-200 text-sm">
                                @error('location')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ✅ Player photo upload (upload or camera) --}}
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Player photo (passport style)
                                </label>
                                <input
                                    type="file"
                                    name="player_photo"
                                    accept="image/*"
                                    capture="environment"
                                    class="mt-1 block w-full rounded-lg border-slate-200 text-sm bg-slate-50 px-2 py-1.5"
                                >
                                <p class="mt-1 text-[11px] text-slate-500">
                                    You can take a photo using your phone camera or upload an existing image. Clear face, no cap if possible.
                                </p>
                                @error('player_photo')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror>
                            </div>
                        </div>
                    </div>

                    {{-- Guardian section --}}
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 mb-3">
                            Parent / Guardian Information
                        </h4>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Parent / Guardian name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="guardian_name"
                                       value="{{ old('guardian_name', auth()->user()->name) }}"
                                       class="mt-1 block w-full rounded-lg border-slate-200 text-sm"
                                       required>
                                @error('guardian_name')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    WhatsApp / Phone number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="guardian_phone"
                                       value="{{ old('guardian_phone', auth()->user()->phone ?? '') }}"
                                       class="mt-1 block w-full rounded-lg border-slate-200 text-sm"
                                       required>
                                @error('guardian_phone')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Email
                                </label>
                                <input type="email" name="guardian_email"
                                       value="{{ old('guardian_email', auth()->user()->email) }}"
                                       class="mt-1 block w-full rounded-lg border-slate-200 text-sm">
                                @error('guardian_email')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Payment info (manual MoMo) --}}
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 mb-3">
                            Payment Information (Manual MoMo)
                        </h4>
                        <p class="text-xs text-slate-600 mb-3">
                            Camp fee is <span class="font-semibold text-slate-900">50,000 RWF</span>.
                            Please pay to <span class="font-mono text-slate-900">MoMo 07885448596</span>.
                            After payment, fill in the phone number and reference used so we can confirm and approve your spot.
                        </p>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Payment phone used
                                </label>
                                <input type="text" name="payment_phone" value="{{ old('payment_phone') }}"
                                       class="mt-1 block w-full rounded-lg border-slate-200 text-sm">
                                @error('payment_phone')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Payment reference (if available)
                                </label>
                                <input type="text" name="payment_reference" value="{{ old('payment_reference') }}"
                                       class="mt-1 block w-full rounded-lg border-slate-200 text-sm">
                                @error('payment_reference')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Notes (injuries, medical conditions, special info)
                        </label>
                        <textarea name="notes" rows="3"
                                  class="mt-1 block w-full rounded-lg border-slate-200 text-sm">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="pt-2 flex items-center justify-between">
                        <p class="text-xs text-slate-500">
                            By submitting, you agree that your child will participate in the Festive Camp under SFTS staff supervision.
                        </p>
                        <button type="submit"
                                class="inline-flex items-center px-5 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-medium shadow-sm hover:bg-slate-800 transition">
                            Submit Festive Camp registration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
