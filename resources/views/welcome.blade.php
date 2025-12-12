<!DOCTYPE html>  
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Shoot For The Stars Membership Portal</title>

    {{-- Fonts & Assets --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Use SFTS logo as favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/sfts-logo.png') }}">
    <meta name="theme-color" content="#0F172A"> {{-- dark/navy --}}
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">

    {{-- Top brand bar --}}
    <header class="bg-slate-950 text-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/sfts-logo.png') }}" alt="Shoot For The Stars" class="h-10 w-auto rounded-full bg-slate-900">
                <div class="leading-tight hidden sm:block">
                    <div class="font-semibold tracking-wide uppercase text-xs text-sky-300">
                        Shoot For The Stars
                    </div>
                    <div class="text-sm opacity-95">
                        Youth Basketball Membership Portal
                    </div>
                </div>
            </a>

            <nav class="flex items-center gap-4 text-sm">
                {{-- Always allow Festive Camp registration --}}
                <a href="{{ route('festive-camp.register') }}" class="hover:text-yellow-300 transition">
                    Festive Camp Registration
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="hover:text-yellow-300 transition">Dashboard</a>
                    {{-- Temporarily hide normal player registration --}}
                    {{-- <a href="{{ route('application.create') }}" class="hover:text-yellow-300 transition">
                        My Player Profile
                    </a> --}}
                    @if(auth()->user()->is_admin ?? false)
                        <a href="{{ route('admin.apps.index') }}" class="hover:text-yellow-300 transition">
                            Admin
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="hover:text-yellow-300 transition">Log in</a>
                    {{-- Disable normal account registration for now --}}
                    {{-- <a href="{{ route('register') }}" class="hover:text-yellow-300 transition">Register</a> --}}
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-1">

        {{-- HERO – FESTIVE CAMP FOCUS --}}
        <section class="relative">
            <div class="absolute inset-0 -z-10 opacity-90"
                 style="background:
                    radial-gradient(1100px 380px at 60% -10%, rgba(250,204,21,0.18), transparent),
                    radial-gradient(900px 320px at 15% 0%, rgba(56,189,248,0.14), transparent);">
            </div>

            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid lg:grid-cols-12 gap-10 items-center">
                    <div class="lg:col-span-7">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-500 mb-2">
                            X-Mas &amp; New Year Festive Camp
                        </p>
                        <h1 class="text-3xl sm:text-5xl font-semibold tracking-tight text-slate-900">
                            Holiday Hoops Camp for
                            <span class="text-yellow-500">Young Stars</span> in Kigali.
                        </h1>
                        <p class="mt-4 text-slate-700 max-w-2xl">
                            From <strong>15 December 2025</strong> to <strong>2 January 2026</strong>, every
                            <strong>Monday, Wednesday &amp; Friday</strong>, your child will train, play and have fun
                            with our coaches at <strong>Green Hills Academy Indoor Gymnasium</strong> – from
                            <strong>10:00 to 12:00</strong>.
                        </p>
                        <p class="mt-2 text-sm text-slate-700">
                            Full camp fee: <span class="font-semibold text-yellow-600">50,000 RWF</span> for the whole festive period.
                            Payment is done manually via MoMo: <span class="font-mono text-slate-900">07885448596</span>.
                        </p>

                        {{-- Primary CTA – ONLY FESTIVE CAMP --}}
                        <div class="mt-6 flex flex-wrap items-center gap-4">
                            <a href="{{ route('festive-camp.register') }}"
                               class="inline-flex items-center px-6 py-3 rounded-lg text-slate-950 bg-yellow-400 hover:bg-yellow-300 shadow-soft font-medium">
                                Register for Festive Camp
                            </a>

                            @isset($remaining_spots, $campCapacity)
                                <span class="inline-flex items-center px-4 py-2 rounded-full border border-emerald-400/70 bg-emerald-400/10 text-emerald-700 text-xs sm:text-sm font-semibold">
                                    {{ $remaining_spots }} spots remaining out of {{ $campCapacity }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full border border-emerald-400/70 bg-emerald-400/10 text-emerald-700 text-xs sm:text-sm font-semibold">
                                    Few spots remaining
                                </span>
                            @endisset
                        </div>

                        <p class="mt-4 text-xs sm:text-sm text-slate-600">
                            Fun drills, games, mini-tournaments, and a positive environment to finish the year strong
                            and start the new year with energy. Boys and girls from 4 to 16 Years old are welcome.
                        </p>

                        {{-- Normal membership temporarily disabled message (optional) --}}
                        <p class="mt-3 text-xs text-slate-500">
                            Note: Regular academy membership registration is temporarily paused. We are currently
                            registering <strong>Festive Camp participants only</strong>.
                        </p>
                    </div>

                    {{-- Compact card – Camp snapshot --}}
                    <div class="lg:col-span-5">
                        <div class="bg-white rounded-xl shadow-soft p-6 border border-slate-100">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('images/sfts-logo.png') }}" class="h-9 w-9 rounded-full bg-slate-900" alt="Shoot For The Stars">
                                <div class="font-medium">
                                    Festive Basketball Camp 2025–2026
                                    <div class="text-xs text-slate-500">Shoot For The Stars Basketball Academy</div>
                                </div>
                            </div>
                            <ul class="mt-5 space-y-2 text-slate-700 text-sm">
                                <li class="flex items-center gap-2">
                                    <span class="h-2 w-2 bg-yellow-400 rounded-full"></span>
                                    Dates: 15 December 2025 – 2 January 2026.
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-2 w-2 bg-sky-400 rounded-full"></span>
                                    Days: Monday, Wednesday &amp; Friday • Time: 10:00 – 12:00.
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-2 w-2 bg-emerald-400 rounded-full"></span>
                                    Venue: Green Hills Academy Indoor Gymnasium.
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-2 w-2 bg-purple-400 rounded-full"></span>
                                    Age groups: 4-7, U8, U10, U12, U14, U16 (boys &amp; girls).
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-2 w-2 bg-rose-400 rounded-full"></span>
                                    Fee: 50,000 RWF · MoMo: 0788448596 (Names: Mugunga Louis)manual payment, approval after confirmation).
                                </li>
                            </ul>
                            <a href="{{ route('festive-camp.register') }}"
                               class="mt-6 w-full inline-flex justify-center px-4 py-2.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 text-sm font-medium">
                               Go to Festive Camp Registration Form
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- FEATURES – Fun & exciting for the camp --}}
        <section class="py-12">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl border border-slate-100 p-6 shadow-soft">
                        <div class="flex items-center gap-2 text-slate-900 font-semibold">
                            <span class="h-2.5 w-2.5 rounded-full bg-yellow-400"></span>
                            Holiday Hoops Fun
                        </div>
                        <p class="mt-2 text-slate-600 text-sm">
                            High-energy sessions with fun drills, shooting games and friendly competitions to keep
                            kids active during the festive season.
                        </p>
                    </div>
                    <div class="bg-white rounded-xl border border-slate-100 p-6 shadow-soft">
                        <div class="flex items-center gap-2 text-slate-900 font-semibold">
                            <span class="h-2.5 w-2.5 rounded-full bg-sky-400"></span>
                            Skills & Confidence
                        </div>
                        <p class="mt-2 text-slate-600 text-sm">
                            Focus on ball handling, footwork, finishing and game understanding – helping young players
                            grow in skills and confidence.
                        </p>
                    </div>
                    <div class="bg-white rounded-xl border border-slate-100 p-6 shadow-soft">
                        <div class="flex items-center gap-2 text-slate-900 font-semibold">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                            Safe Indoor Environment
                        </div>
                        <p class="mt-2 text-slate-600 text-sm">
                            Indoor gym at Green Hills Academy – safe, weather-proof and supervised by experienced
                            Shoot For The Stars coaching staff.
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-slate-600 flex items-center justify-between">
            <span>© {{ date('Y') }} Shoot For The Stars Basketball Academy</span>
            <span class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-yellow-400"></span>
                <span>Youth basketball membership &amp; player management portal<a href="https://wa.me/250788448596?text=Hello%20IT%20NET%20Ltd%2C%20I%20need%20support." 
   target="_blank" 
   class="hover:underline">
    Developed by IT NET Ltd
</a></span>
            </span>
        </div>
    </footer>
</body>
</html>
