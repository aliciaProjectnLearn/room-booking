<aside
    class="fixed mt-16 flex flex-col lg:mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-[99999] border-r border-gray-200"
    :class="[
        sidebarExpanded || sidebarHovered ? 'lg:w-[290px]' : 'lg:w-[90px]',
        mobileSidebarOpen ? 'translate-x-0 w-[290px]' : '-translate-x-full lg:translate-x-0'
    ]"
    @mouseenter="!sidebarExpanded && (sidebarHovered = true)"
    @mouseleave="sidebarHovered = false"
>
    <!-- Logo area -->
    <div
      class="py-8 flex items-center transition-all duration-300"
      :class="!sidebarExpanded && !sidebarHovered ? 'lg:justify-center' : 'justify-start'"
    >
      <a href="/">
        <div class="flex items-center overflow-hidden">
            <img
              x-show="sidebarExpanded || sidebarHovered || mobileSidebarOpen"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              class="dark:hidden w-32"
              src="{{ asset('images/logo/logo_smk.png') }}"
              alt="Logo"
            />
            <img
              x-show="sidebarExpanded || sidebarHovered || mobileSidebarOpen"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              class="hidden dark:block w-32"
              src="{{ asset('images/logo/logo_smk.png') }}"
              alt="Logo"
            />
            <img
              x-show="!(sidebarExpanded || sidebarHovered || mobileSidebarOpen)"
              x-transition:enter="transition ease-out duration-300 delay-100"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              src="{{ asset('images/logo/logo_smk.png') }}"
              alt="Logo"
              class="w-8"
            />
        </div>
      </a>
    </div>

    <!-- Navigation -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar overflow-x-hidden">
      <nav class="mb-6">
        <div class="flex flex-col gap-4">
          <!-- Menu Group -->
          <div>
            <h2
              class="mb-4 text-xs uppercase flex leading-[20px] text-gray-400"
              :class="!sidebarExpanded && !sidebarHovered ? 'lg:justify-center' : 'justify-start'"
            >
              <span x-show="sidebarExpanded || sidebarHovered || mobileSidebarOpen" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="whitespace-nowrap">Menu</span>
              <span x-show="!(sidebarExpanded || sidebarHovered || mobileSidebarOpen)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">...</span>
            </h2>
            <ul class="flex flex-col gap-4">
              <!-- Dashboard -->
              <li>
                <a href="{{ route('admin.dashboard') }}"
                  class="menu-item group {{ request()->routeIs('admin.dashboard') ? 'menu-item-active' : 'menu-item-inactive' }} flex items-center overflow-hidden"
                >
                  <span class="shrink-0 {{ request()->routeIs('admin.dashboard') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                  </span>
                  <span x-show="sidebarExpanded || sidebarHovered || mobileSidebarOpen" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="menu-item-text whitespace-nowrap ml-3">Dashboard</span>
                </a>
              </li>

              <!-- Verifikasi Booking -->
              <li>
                <a href="{{ route('admin.verifikasi.index') }}"
                  class="menu-item group {{ request()->routeIs('admin.verifikasi.*') ? 'menu-item-active' : 'menu-item-inactive' }} flex items-center overflow-hidden"
                >
                  <span class="shrink-0 {{ request()->routeIs('admin.verifikasi.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </span>
                  <span x-show="sidebarExpanded || sidebarHovered || mobileSidebarOpen" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="menu-item-text whitespace-nowrap ml-3">Verifikasi Booking</span>
                </a>
              </li>

              <!-- Reset Booking -->
              <li>
                <a href="{{ route('admin.reset.index') }}"
                  class="menu-item group {{ request()->routeIs('admin.reset.*') ? 'menu-item-active' : 'menu-item-inactive' }} flex items-center overflow-hidden"
                >
                  <span class="shrink-0 {{ request()->routeIs('admin.reset.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                  </span>
                  <span x-show="sidebarExpanded || sidebarHovered || mobileSidebarOpen" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="menu-item-text whitespace-nowrap ml-3">Reset Booking</span>
                </a>
              </li>

              <!-- Calendar -->
              <li>
                <a href="/admin/calendar"
                  class="menu-item group {{ request()->is('admin/calendar') ? 'menu-item-active' : 'menu-item-inactive' }} flex items-center overflow-hidden"
                >
                  <span class="shrink-0 {{ request()->is('admin/calendar') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </span>
                  <span x-show="sidebarExpanded || sidebarHovered || mobileSidebarOpen" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="menu-item-text whitespace-nowrap ml-3">Calendar</span>
                </a>
              </li>
            </ul>
          </div>
          
          <!-- Laporan Group -->
          <div class="mt-4">
            <h2
              class="mb-4 text-xs uppercase flex leading-[20px] text-gray-400"
              :class="!sidebarExpanded && !sidebarHovered ? 'lg:justify-center' : 'justify-start'"
            >
              <span x-show="sidebarExpanded || sidebarHovered || mobileSidebarOpen" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="whitespace-nowrap">Laporan & Pengaturan</span>
              <span x-show="!(sidebarExpanded || sidebarHovered || mobileSidebarOpen)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">...</span>
            </h2>
            <ul class="flex flex-col gap-4">
              <!-- Laporan Penggunaan -->
              <li>
                <a href="{{ route('admin.reports.index') }}"
                  class="menu-item group {{ request()->routeIs('admin.reports.*') ? 'menu-item-active' : 'menu-item-inactive' }} flex items-center overflow-hidden"
                >
                  <span class="shrink-0 {{ request()->routeIs('admin.reports.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </span>
                  <span x-show="sidebarExpanded || sidebarHovered || mobileSidebarOpen" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="menu-item-text whitespace-nowrap ml-3">Laporan Penggunaan</span>
                </a>
              </li>

              <!-- Manajemen User -->
              <li>
                <a href="{{ route('admin.users.index') }}"
                  class="menu-item group {{ request()->routeIs('admin.users.*') ? 'menu-item-active' : 'menu-item-inactive' }} flex items-center overflow-hidden"
                >
                  <span class="shrink-0 {{ request()->routeIs('admin.users.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                  </span>
                  <span x-show="sidebarExpanded || sidebarHovered || mobileSidebarOpen" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="menu-item-text whitespace-nowrap ml-3">Manajemen User</span>
                </a>
              </li>

              <!-- Manajemen Ruang -->
              <li>
                <a href="{{ route('admin.rooms.index') }}"
                  class="menu-item group {{ request()->routeIs('admin.rooms.*') ? 'menu-item-active' : 'menu-item-inactive' }} flex items-center overflow-hidden"
                >
                  <span class="shrink-0 {{ request()->routeIs('admin.rooms.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                  </span>
                  <span x-show="sidebarExpanded || sidebarHovered || mobileSidebarOpen" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="menu-item-text whitespace-nowrap ml-3">Manajemen Ruang</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
</aside>
