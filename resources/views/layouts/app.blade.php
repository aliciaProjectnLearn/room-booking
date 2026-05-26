<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-800 bg-sky-50 dark:bg-gray-900 dark:text-gray-400">
        <div x-data="{ sidebarExpanded: false, sidebarHovered: false, mobileSidebarOpen: false }" class="min-h-screen xl:flex">
            
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Backdrop for Mobile -->
            <div x-show="mobileSidebarOpen" 
                 @click="mobileSidebarOpen = false" 
                 class="fixed inset-0 z-40 bg-black/50 lg:hidden">
            </div>

            <!-- Content Area -->
            <div
                class="flex-1 transition-all duration-300 ease-in-out"
                :class="[sidebarExpanded || sidebarHovered ? 'lg:ml-[290px]' : 'lg:ml-[90px]']"
            >
                <!-- Header -->
                @include('layouts.header')

                <!-- Main Content -->
                <main class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6 pt-0">
                    <p class="text-sm text-center text-gray-500 dark:text-gray-400">
                        Room Booking System &copy; {{ date('Y') }}
                    </p>
                </footer>
            </div>
        </div>
    </body>
</html>
