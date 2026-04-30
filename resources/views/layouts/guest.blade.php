<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-white antialiased bg-gradient-to-br from-[#110627] via-[#2b106c] to-[#10071a]">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#140622]">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-[#190f3b]/95 shadow-[0_0_40px_rgba(146,94,255,0.22)] overflow-hidden sm:rounded-lg border border-[#7c5cff]/35 text-white">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
