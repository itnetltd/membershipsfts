<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Edit Registration
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl border border-slate-100 p-6">
                <form method="POST" action="{{ route('festive-camp.update', $registration) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-600">Player name</label>
                            <input name="player_name" value="{{ old('player_name', $registration->player_name) }}"
                                   class="mt-1 w-full rounded-lg border-slate-200" required>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600">Age</label>
                            <input type="number" name="age" value="{{ old('age', $registration->age) }}"
                                   class="mt-1 w-full rounded-lg border-slate-200" min="4" max="18">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600">Category</label>
                            <input name="category" value="{{ old('category', $registration->category) }}"
                                   class="mt-1 w-full rounded-lg border-slate-200">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600">School</label>
                            <input name="school" value="{{ old('school', $registration->school) }}"
                                   class="mt-1 w-full rounded-lg border-slate-200">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600">Location</label>
                            <input name="location" value="{{ old('location', $registration->location) }}"
                                   class="mt-1 w-full rounded-lg border-slate-200">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600">Player photo (optional)</label>
                            <input type="file" name="player_photo" accept="image/*"
                                   class="mt-1 w-full rounded-lg border-slate-200">
                            @if(!empty($registration->player_photo_path))
                                <img src="{{ asset('storage/'.$registration->player_photo_path) }}"
                                     class="mt-2 h-20 w-20 rounded-xl object-cover border border-slate-200" alt="">
                            @endif
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600">Guardian name</label>
                            <input name="guardian_name" value="{{ old('guardian_name', $registration->guardian_name) }}"
                                   class="mt-1 w-full rounded-lg border-slate-200" required>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600">Guardian phone</label>
                            <input name="guardian_phone" value="{{ old('guardian_phone', $registration->guardian_phone) }}"
                                   class="mt-1 w-full rounded-lg border-slate-200" required>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600">Guardian email</label>
                            <input type="email" name="guardian_email" value="{{ old('guardian_email', $registration->guardian_email) }}"
                                   class="mt-1 w-full rounded-lg border-slate-200">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600">Payment phone</label>
                            <input name="payment_phone" value="{{ old('payment_phone', $registration->payment_phone) }}"
                                   class="mt-1 w-full rounded-lg border-slate-200">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-slate-600">Payment reference</label>
                            <input name="payment_reference" value="{{ old('payment_reference', $registration->payment_reference) }}"
                                   class="mt-1 w-full rounded-lg border-slate-200">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-slate-600">Notes</label>
                            <textarea name="notes" rows="3" class="mt-1 w-full rounded-lg border-slate-200">{{ old('notes', $registration->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <a href="{{ route('festive-camp.my') }}" class="text-sm text-slate-600 hover:underline">
                            Back
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-5 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
