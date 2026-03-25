<aside x-data="{ activeMenu: null }" id="sidebar"
       :class="$store.sidebar.sidebarToggle ? 'w-0 opacity-0 pointer-events-none' : 'w-72'"
       class="bg-emerald-600 text-white flex flex-col h-screen sticky top-0 transition-all duration-300 shadow-2xl text-sm">

    <!-- Logo with modern design -->
    <div class="relative py-6 text-center overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-700 to-emerald-500 opacity-50"></div>
        <div class="absolute -left-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-emerald-800/30 rounded-full blur-2xl"></div>

        <!-- Logo content -->
        <a href="#" class="relative inline-block font-bold text-xl tracking-wider">
            <span class="bg-gradient-to-r from-white to-emerald-100 bg-clip-text text-transparent font-extrabold">
                {{ __('Nepalese College') }}
            </span>
            <div class="h-0.5 w-12 bg-white/60 mx-auto mt-2 rounded-full"></div>
        </a>
    </div>

    <!-- Navigation -->
    <ul class="flex-1 overflow-y-auto py-4 space-y-1 px-3">

        @php
            $user = Auth::user();
            $isAdmin = $user->username == 'admin@gmail.com';
        @endphp

        @foreach ($menus as $menu)

            {{-- ================= SINGLE MENU ================= --}}
            @if (!isset($menu['children']))
                @php
                    $hasPermission = true;
                    if (!$isAdmin && isset($menu['ability']) && $menu['ability']) {
                        $hasPermission = auth()->user()->can($menu['ability']);
                    }
                @endphp

                @if($hasPermission)
                    <li>
                        <a href="{{ route($menu['route']) }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 group
                           {{ request()->routeIs($menu['route'])
                                ? 'bg-white text-emerald-800 shadow-lg shadow-emerald-800/30 font-medium'
                                : 'text-emerald-50 hover:bg-emerald-700 hover:shadow-md hover:shadow-emerald-800/30 hover:translate-x-1' }}">

                            <i class="fa-solid {{ $menu['icon'] ?? 'fa-circle' }} w-5 text-center transition-transform group-hover:scale-110
                                {{ request()->routeIs($menu['route']) ? 'text-emerald-600' : 'text-emerald-300' }}"></i>
                            <span>{{ __($menu['title']) }}</span>

                            @if(request()->routeIs($menu['route']))
                                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                            @endif
                        </a>
                    </li>
                @endif
            @endif

            {{-- ================= DROPDOWN MENU ================= --}}
            @if (isset($menu['children']))
                @php
                    // Filter children based on permissions (skip for admin)
                    $filteredChildren = collect($menu['children'])->filter(function($child) use ($isAdmin) {
                        if (!$isAdmin && isset($child['ability']) && $child['ability']) {
                            return auth()->user()->can($child['ability']);
                        }
                        return true;
                    })->values()->toArray();

                    $hasAnyChildPermission = count($filteredChildren) > 0;

                    $isChildActive = collect($filteredChildren)->contains(fn($child) =>
                        request()->routeIs($child['route'])
                    );
                @endphp

                @if($hasAnyChildPermission)
                    <li>

                        <!-- Parent Button -->
                        <button
                            x-init="if({{ $isChildActive ? 'true' : 'false' }}) activeMenu='{{ $menu['title'] }}'"
                            @click="activeMenu === '{{ $menu['title'] }}'
                                    ? activeMenu = null
                                    : activeMenu = '{{ $menu['title'] }}'"

                            class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 group cursor-pointer
                            {{ $isChildActive
                                ? 'bg-white text-emerald-800 shadow-lg shadow-emerald-800/30 font-medium'
                                : 'text-emerald-50 hover:bg-emerald-700 hover:shadow-md hover:shadow-emerald-800/30 hover:translate-x-1' }}">

                            <div class="flex items-center gap-3">
                                <i class="fa-solid {{ $menu['icon'] ?? 'fa-circle' }} w-5 text-center transition-transform group-hover:scale-110
                                    {{ $isChildActive ? 'text-emerald-600' : 'text-emerald-300' }}"></i>
                                <span>{{ __($menu['title']) }}</span>
                            </div>

                            <div class="flex items-center">
                                @if($isChildActive)
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse mr-2"></span>
                                @endif
                                <i class="fa-solid fa-chevron-down text-xs transition-all duration-300
                                    {{ $isChildActive ? 'text-emerald-600' : 'text-emerald-300' }}"
                                   :class="{ 'rotate-180': activeMenu === '{{ $menu['title'] }}' }"></i>
                            </div>
                        </button>

                        <!-- Children with glass morphism effect -->
                        <ul x-show="activeMenu === '{{ $menu['title'] }}'"
                            x-collapse.duration.200ms
                            class="mt-1 ml-4 space-y-0.5 relative">

                            <!-- Decorative line -->
                            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-emerald-500/30 rounded-full"></div>

                            @foreach ($filteredChildren as $index => $child)
                                <li class="relative">
                                    <a href="{{ route($child['route']) }}"
                                       class="block px-4 py-2 ml-6 text-sm rounded-lg transition-all duration-200
                                       {{ request()->routeIs($child['route'])
                                            ? 'bg-emerald-700 text-white shadow-md font-medium'
                                            : 'text-emerald-100 hover:bg-emerald-700/70 hover:text-white hover:translate-x-1' }}">

                                        <div class="flex items-center gap-3">
                                            <!-- Active/Inactive Indicator -->
                                            <span class="text-xs w-4 text-center">
                                                @if(request()->routeIs($child['route']))
                                                    <span class="text-yellow-300">●</span>
                                                @else
                                                    <span class="text-emerald-400">○</span>
                                                @endif
                                            </span>
                                            <span>{{ __($child['title']) }}</span>

                                            @if(request()->routeIs($child['route']))
                                                <span class="ml-auto text-yellow-300 text-xs">●</span>
                                            @endif
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </li>
                @endif
            @endif

        @endforeach

    </ul>

    <!-- Modern Footer with User Profile -->
    <div class="relative mt-auto border-t border-emerald-500/30">
        <!-- Decorative gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-emerald-700/50 to-transparent pointer-events-none"></div>
        @php
            $user = Auth::user();
        @endphp

        <div class="relative p-4">
            <div
                class="flex items-center gap-3 rounded-xl bg-emerald-700/50 p-3 backdrop-blur-sm border border-emerald-500/30">
                <!-- Avatar with status -->
                <div class="relative">
                    @if($user->profile && $user->profile->photo)
                        <img class="w-10 h-10 rounded-xl object-cover" src="{{ asset('storage/'.$user->profile->photo) }}"
                             alt="Profile">
                    @else
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg">
                            <i class="fa-regular fa-user text-white text-lg"></i>
                        </div>
                    @endif
                    <div
                        class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 rounded-full border-2 border-emerald-700"></div>
                </div>

                <!-- User info -->
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-white truncate">{{ $user->profile ? $user->profile->first_name .' ' . $user->profile->last_name : "Admin" }}</p>
                    <p class="text-xs text-emerald-200 truncate">{{ $user->username }}</p>
                </div>

                <!-- Settings icon -->
                <button class="text-emerald-300 hover:text-white transition-colors">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
            </div>

            <!-- Quick actions -->
            <div class="flex justify-around mt-3 text-emerald-300 text-xs">
                <button class="hover:text-white transition-colors flex flex-col items-center gap-1">
                    <i class="fa-regular fa-bell"></i>
                    <span>Alerts</span>
                </button>
                <button class="hover:text-white transition-colors flex flex-col items-center gap-1">
                    <i class="fa-regular fa-message"></i>
                    <span>Messages</span>
                </button>
                <button class="hover:text-white transition-colors flex flex-col items-center gap-1">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('sidebar', {
            sidebarToggle: false,

            handelToggle() {
                this.sidebarToggle = !this.sidebarToggle;
            },
        });
    });
</script>
