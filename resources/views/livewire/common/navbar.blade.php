<aside x-data="{ activeMenu: null }" id="sidebar"
    :class="$store.sidebar.sidebarToggle ? 'w-0 opacity-0 pointer-events-none' : 'w-64'"
    class="bg-emerald-600 text-white flex flex-col h-screen sticky top-0 transition-all duration-300 text-xs">

    <!-- Logo -->
    <div class="py-5 text-center font-extrabold text-lg border-b border-emerald-500">
        <a href="#">{{ __('Nepalese College') }}</a>
    </div>

    <!-- Navigation -->
    <ul class="flex-1 overflow-y-auto">

        @foreach ($menus as $menu)

            {{-- ================= SINGLE MENU ================= --}}
            @if (!isset($menu['children']))
                <li>
                    <a href="{{ route($menu['route']) }}"
                       class="flex items-center gap-3 px-6 py-3 transition
                       {{ request()->routeIs($menu['route'])
                            ? 'bg-emerald-800 font-semibold border-l-4 border-white'
                            : 'hover:bg-emerald-700' }}">

                        <i class="fa-solid {{ $menu['icon'] ?? 'fa-circle' }}"></i>
                        <span>{{ __($menu['title']) }}</span>
                    </a>
                </li>
            @endif



            {{-- ================= DROPDOWN MENU ================= --}}
            @if (isset($menu['children']))

                @php
                    $isChildActive = collect($menu['children'])->contains(fn($child) =>
                        request()->routeIs($child['route'])
                    );
                @endphp

                <li>

                    <!-- Parent Button -->
                    <button
                        x-init="if({{ $isChildActive ? 'true' : 'false' }}) activeMenu='{{ $menu['title'] }}'"
                        @click="activeMenu === '{{ $menu['title'] }}'
                                ? activeMenu = null
                                : activeMenu = '{{ $menu['title'] }}'"

                        class="w-full flex items-center justify-between px-6 py-3 transition cursor-pointer
                        {{ $isChildActive
                            ? 'bg-emerald-800 font-semibold border-l-4 border-white'
                            : 'hover:bg-emerald-700' }}">

                        <div class="flex items-center gap-3">
                            <i class="{{ $menu['icon'] ?? 'fa-solid fa-circle' }}"></i>
                            <span>{{ __($menu['title']) }}</span>
                        </div>

                        <i class="fa-solid fa-chevron-down text-xs transition-transform"
                           :class="{ 'rotate-180': activeMenu === '{{ $menu['title'] }}' }"></i>
                    </button>


                    <!-- Children -->
                    <ul x-show="activeMenu === '{{ $menu['title'] }}'"
                        x-collapse
                        class="bg-emerald-700/30">

                        @foreach ($menu['children'] as $child)
                            <li>
                                <a href="{{ route($child['route']) }}"
                                   class="block px-12 py-2 text-sm transition flex items-center gap-2
                                   {{ request()->routeIs($child['route'])
                                        ? 'bg-emerald-700 border-l-4 border-yellow-300 font-medium'
                                        : 'hover:bg-emerald-700 text-emerald-100' }}">

                                    <!-- Active Dot -->
                                    <span>
                                        {{ request()->routeIs($child['route']) ? '●' : '○' }}
                                    </span>

                                    {{ __($child['title']) }}
                                </a>
                            </li>
                        @endforeach

                    </ul>

                </li>
            @endif

        @endforeach

    </ul>

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