<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        @php
            $user = auth()->user();
            $initials = 'G';
            if ($user) {
                if (method_exists($user, 'initials')) {
                    $initials = $user->initials() ?: 'G';
                } else {
                    $name = trim($user->name ?? '');
                    if ($name !== '') {
                        $parts = preg_split('/\s+/', $name);
                        if (count($parts) >= 2) {
                            $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[count($parts)-1], 0, 1));
                        } else {
                            $initials = strtoupper(substr($name, 0, 1));
                        }
                    }
                }
            }

            $dashboardUrl = \Illuminate\Support\Facades\Route::has('dashboard') ? route('dashboard') : url('/dashboard');
            $categoriesUrl = \Illuminate\Support\Facades\Route::has('categories') ? route('categories') : url('/categories');

            $isActive = function ($routeName = null, $path = null) {
                if ($routeName && \Illuminate\Support\Facades\Route::has($routeName) && request()->routeIs($routeName)) {
                    return true;
                }
                if ($path && (request()->is(ltrim($path, '/')) || request()->is(ltrim($path, '/') . '/*'))) {
                    return true;
                }
                return false;
            };

            $linkClass = function ($active) {
                return $active
                    ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100'
                    : 'flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-gray-200 hover:bg-gray-50 hover:text-gray-700';
            };
        @endphp

        <!-- hamburger: visible only on small screens, sits above everything -->
        <button id="sidebar-toggle" aria-label="Toggle sidebar" aria-controls="app-sidebar" aria-expanded="false"
            class="md:hidden fixed top-4 left-4 z-60 bg-white/90 dark:bg-zinc-900/90 p-2 rounded-md shadow-sm ring-1 ring-gray-200">
            <svg class="w-5 h-5 text-gray-700 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                 d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <!-- sidebar (off-canvas on mobile, fixed on md+) -->
        <aside id="app-sidebar" role="navigation" aria-label="Main sidebar"
               class="fixed inset-y-0 left-0 z-50 w-72 transform -translate-x-full md:translate-x-0 transition-transform duration-200 bg-white dark:bg-zinc-900 border-r border-gray-100 dark:border-zinc-800 p-6 flex flex-col justify-between">
            <div>
                {{-- Branding --}}
                <div class="mb-5">
                    <a href="{{ $dashboardUrl }}" class="flex items-center gap-4 no-underline">
                        <div class="flex items-center space-x-3 px-4 py-3">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 rounded-xl">
                            <span class="text-xl font-semibold text-white">Sustainability Tracker</span>
                        </div>
                    </a>
                </div>

                {{-- User card --}}
                <div class="bg-gray-50 dark:bg-zinc-800/40 rounded-lg p-4 mb-4 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-md bg-emerald-600 flex items-center justify-center text-white font-semibold text-lg">
                            {{ $initials }}
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ optional($user)->name ?? 'Guest' }}</div>
                            <div class="text-xs text-gray-500 dark:text-zinc-300 truncate">{{ optional($user)->email ?? '' }}</div>
                            @if(optional($user)->created_at)
                                <div class="text-xs text-gray-400 mt-1">Member since {{ optional($user)->created_at->format('M Y') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <nav class="mt-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ $dashboardUrl }}" data-close-sidebar class="{{ $linkClass($isActive('dashboard','/dashboard')) }}">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" /></svg>
                                <span class="truncate">Dashboard</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ $categoriesUrl }}" data-close-sidebar class="{{ $linkClass($isActive('categories','/categories')) }}">
                                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                                <span class="truncate">Categories</span>
                            </a>
                        </li>

                        <li>
                            <hr class="border-t border-gray-100 dark:border-zinc-800 my-2">
                        </li>

                        {{-- additional links... --}}
                    </ul>
                </nav>
            </div>

            <div class="mt-6">
                <div class="border-t border-gray-100 dark:border-zinc-800 pt-4 space-y-2">
                    @php
                        $hasProfileRoute = \Illuminate\Support\Facades\Route::has('profile');
                        $profileId = optional($user)->id ?? auth()->id() ?? null;
                        $profileUrl = $hasProfileRoute ? route('profile', $profileId) : $dashboardUrl;
                    @endphp

                    <a href="{{ $profileUrl }}" data-close-sidebar class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-gray-50 hover:bg-gray-50 hover:text-gray-700">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.636 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>Profile</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" /></svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- overlay for mobile when sidebar is open -->
        <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/40 hidden md:hidden" aria-hidden="true"></div>

        <!-- Page container: sidebar is out-of-flow (fixed), so main uses margin to avoid overlap -->
        <div class="flex">
            <main id="main-content" class="flex-1 bg-gray-50 ml-0 md:ml-72 min-h-screen transition-all duration-200" aria-labelledby="page-title">
                {{-- page content gets injected here --}}
                {{ $slot }}
            </main>
        </div>

        @fluxScripts

        <!-- Sidebar behavior script: only handles open/close and scroll lock -->
        <script>
            (function () {
                const toggle = document.getElementById('sidebar-toggle');
                const sidebar = document.getElementById('app-sidebar');
                const overlay = document.getElementById('sidebar-overlay');

                if (!sidebar) return;

                function openSidebar() {
                    sidebar.classList.remove('-translate-x-full');
                    if (overlay) overlay.classList.remove('hidden');
                    document.documentElement.classList.add('overflow-hidden');
                    document.body.classList.add('overflow-hidden');
                    if (toggle) toggle.setAttribute('aria-expanded', 'true');
                }

                function closeSidebar() {
                    sidebar.classList.add('-translate-x-full');
                    if (overlay) overlay.classList.add('hidden');
                    document.documentElement.classList.remove('overflow-hidden');
                    document.body.classList.remove('overflow-hidden');
                    if (toggle) toggle.setAttribute('aria-expanded', 'false');
                }

                // Toggle button
                if (toggle) {
                    toggle.addEventListener('click', function (e) {
                        e.stopPropagation();
                        const expanded = toggle.getAttribute('aria-expanded') === 'true';
                        if (expanded) closeSidebar(); else openSidebar();
                    });
                }

                // Overlay click closes sidebar
                if (overlay) overlay.addEventListener('click', closeSidebar);

                // Close mobile sidebar when nav links with data-close-sidebar clicked
                document.addEventListener('click', (e) => {
                    const el = e.target.closest('[data-close-sidebar]');
                    if (!el) return;
                    if (window.innerWidth < 768) closeSidebar();
                });

                // Close on Escape
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        // if modal open, let modal handle it. Otherwise close sidebar.
                        closeSidebar();
                    }
                });

                // Ensure correct orientation on resize
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 768) {
                        sidebar.classList.remove('-translate-x-full');
                        if (overlay) overlay.classList.add('hidden');
                        if (toggle) toggle.setAttribute('aria-expanded', 'true');
                        document.documentElement.classList.remove('overflow-hidden');
                        document.body.classList.remove('overflow-hidden');
                    } else {
                        sidebar.classList.add('-translate-x-full');
                        if (toggle) toggle.setAttribute('aria-expanded', 'false');
                    }
                });

                // Initialize state
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('-translate-x-full');
                    if (overlay) overlay.classList.add('hidden');
                    if (toggle) toggle.setAttribute('aria-expanded', 'true');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    if (toggle) toggle.setAttribute('aria-expanded', 'false');
                }
            })();
        </script>
    </body>
</html>
