<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Shoot for the Stars Membership Portal') }}</title>

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- Assets --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Favicon (optional) --}}
        <link rel="icon" type="image/png" href="{{ asset('brand/ram-logo.png') }}">
        <meta name="theme-color" content="#0096D6">
    </head>
    <body class="font-sans antialiased bg-gray-50 text-ram-dark min-h-screen flex flex-col">

        {{-- ===== RAM Brand Bar (new) ===== --}}
         

        {{-- ===== Existing Breeze Navigation (kept) ===== --}}
        @include('layouts.navigation')

        {{-- ===== Page Heading (kept) ===== --}}
        @isset($header)
            <header class="bg-white shadow-soft">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- ===== Page Content (kept) ===== --}}
        <main class="flex-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </div>
        </main>

        {{-- ===== Footer (new) ===== --}}
        <footer class="bg-white border-t shadow-soft">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-sm text-gray-600 flex items-center justify-between">
                <span>© {{ date('Y') }} Shoot for the Stars Basketball Academy</span>
                <span class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-ram-yellow rounded-full"></span>
                    <span><a href="https://wa.me/250788448596?text=Hello%20IT%20NET%20Ltd%2C%20I%20need%20support." 
   target="_blank" 
   class="hover:underline">
    Developed by IT NET Ltd
</a></span>
</span>
                </span>
            </div>
        </footer>
    </body>
</html>
