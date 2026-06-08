<header class="sticky top-0 flex w-full bg-blue-900 border-blue-900 z-[99999] dark:border-gray-800 dark:bg-gray-900 lg:border-b text-white">
    <div class="flex flex-col items-center justify-between grow lg:flex-row lg:px-6">
        <div class="flex items-center justify-between w-full gap-2 px-3 py-3 border-b border-gray-200 dark:border-gray-800 sm:gap-4 lg:justify-normal lg:border-b-0 lg:px-0 lg:py-4">
            
            <button @click="if(window.innerWidth >= 1024) { sidebarExpanded = !sidebarExpanded } else { mobileSidebarOpen = !mobileSidebarOpen }"
                class="flex items-center justify-center w-10 h-10 text-white/80 border-blue-800 rounded-lg z-[99999] dark:border-gray-800 dark:text-gray-400 lg:h-11 lg:w-11 lg:border hover:text-white"
                :class="mobileSidebarOpen ? 'lg:bg-transparent dark:lg:bg-transparent bg-blue-800 dark:bg-gray-800' : ''">
                
                <svg x-show="mobileSidebarOpen" class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z" fill=""/>
                </svg>
                <svg x-show="!mobileSidebarOpen" width="16" height="12" viewBox="0 0 16 12" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25L14.6666 10.25C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z" fill="currentColor"/>
                </svg>
            </button>

            <!-- Search Bar (Optional) -->
            <div class="hidden sm:block">
                <form action="#" method="POST">
                    <div class="relative">
                        <span class="absolute top-1/2 left-0 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" placeholder="Type to search..." class="w-full bg-transparent pl-9 pr-4 text-white placeholder-white/70 border-0 focus:ring-0 dark:text-white xl:w-[300px]" />
                    </div>
                </form>
            </div>
        </div>

        <div class="flex items-center justify-between w-full gap-4 px-5 py-4 shadow-theme-md lg:flex lg:justify-end lg:px-0 lg:shadow-none">
            <!-- User Menu -->
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-4">
                    <span class="hidden text-right lg:block">
                        <span class="block text-sm font-medium text-white dark:text-gray-300">{{ Auth::user()->name ?? 'User' }}</span>
                        <span class="block text-xs text-blue-200 dark:text-gray-400">{{ Auth::user()->role ?? 'Admin' }}</span>
                    </span>
                    <span class="h-10 w-10 rounded-full border border-gray-200 dark:border-gray-800 bg-gray-100 flex items-center justify-center text-gray-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                </button>

                <!-- Dropdown -->
                <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" x-transition 
                     class="absolute right-0 mt-4 flex flex-col rounded-lg border border-gray-200 bg-white shadow-theme-md dark:border-gray-800 dark:bg-gray-900 w-48 text-gray-800 dark:text-gray-200">
                    <ul class="flex flex-col gap-5 border-b border-gray-200 px-6 py-5 dark:border-gray-800">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-brand-500 lg:text-base">
                                Profile
                            </a>
                        </li>
                    </ul>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full gap-3.5 px-6 py-4 text-sm font-medium duration-300 ease-in-out hover:text-brand-500 lg:text-base">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
