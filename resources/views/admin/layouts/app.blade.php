<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Room Booking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Smooth transition for sidebar and main content */
        .sidebar-transition {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), margin 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50 dark:bg-gray-900 dark:text-gray-400">
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
            :class="[sidebarExpanded || sidebarHovered ? 'lg:ml-[250px]' : 'lg:ml-[90px]']"
        >
            <!-- Header -->
            @include('layouts.header')

            <!-- Main Content -->
            <main class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                {{-- Notifikasi sukses --}}
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-theme-sm">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Notifikasi error --}}
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-theme-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6 pt-0">
                <p class="text-sm text-center text-gray-500 dark:text-gray-400">
                    Room Booking System &copy; {{ date('Y') }}
                </p>
            </footer>
        </div>
    </div>

    <script>
        function confirmAction(event, message = 'Apakah Anda yakin ingin melanjutkan?') {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'rounded-lg px-4 py-2 text-sm font-medium',
                    cancelButton: 'rounded-lg px-4 py-2 text-sm font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>