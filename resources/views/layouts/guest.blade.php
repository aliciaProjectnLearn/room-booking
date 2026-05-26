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
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-blue-50 dark:bg-gray-900 relative overflow-hidden">
            <!-- Decorative Dashboard-Aligned Background -->
            <div class="absolute top-0 w-full h-1/2 bg-blue-900 dark:bg-gray-800 shadow-xl rounded-b-[100px] md:rounded-b-[200px]"></div>

            <div class="z-10 text-center mb-8 mt-12 sm:mt-0">
                <a href="/" class="flex flex-col items-center gap-3 group">
                    <div class="bg-white p-4 rounded-full shadow-lg transform transition-transform duration-300 group-hover:scale-105">
                        <x-application-logo class="w-16 h-16 fill-current text-blue-900" />
                    </div>
                    <h1 class="text-3xl font-bold text-white tracking-wide">Room Booking System</h1>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md shadow-2xl overflow-hidden sm:rounded-3xl z-10 border border-white dark:border-gray-700">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
