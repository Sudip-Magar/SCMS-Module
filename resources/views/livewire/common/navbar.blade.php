<aside x-data="{ activeMenu: null }"
    id="sidebar"
    :class="$store.sidebar.sidebarToggle ? 'w-0 opacity-0 pointer-events-none' : 'w-64'"
    class="bg-blue-600 text-white flex flex-col h-screen sticky top-0 transition-all duration-300 text-xs">

    <!-- Logo -->
    <div class="py-5 text-center font-extrabold text-lg border-b border-gray-700">
        <a href="#">{{ __('Nepalese College') }}</a>
    </div>

    <!-- Navigation -->
    <ul class="flex-1 overflow-y-auto">

        @foreach ($menus as $menu)

            {{-- Single Link (No Children) --}}
            @if (!isset($menu['children']))
                <li>
                    <a href="{{ route($menu['route']) }}"
                        class="flex items-center gap-3 px-6 py-3 hover:bg-emerald-700 transition">

                        <i class="fa-solid {{ $menu['icon'] ?? 'fa-circle' }}"></i>
                        <span>{{ __($menu['title']) }}</span>
                    </a>
                </li>
            @endif


            {{-- Dropdown Menu --}}
            @if (isset($menu['children']))
                <li>
                    <button
                        @click.prevent="activeMenu === '{{ $menu['title'] }}' ? activeMenu = null : activeMenu = '{{ $menu['title'] }}'"
                        class="w-full flex items-center justify-between px-6 py-3 hover:bg-emerald-700 transition cursor-pointer">

                        <div class="flex items-center gap-3">
                            <i class="{{ $menu['icon'] ?? 'fa-solid fa-circle' }}"></i>
                            <span>{{ __($menu['title']) }}</span>
                        </div>

                        <i class="fa-solid fa-chevron-down text-xs transition-transform"
                            :class="{ 'rotate-180': activeMenu === '{{ $menu['title'] }}' }"></i>
                    </button>

                    <ul x-show="activeMenu === '{{ $menu['title'] }}'" x-collapse>
                        @foreach ($menu['children'] as $child)
                            <li>
                                <a href="{{ route($child['route']) }}"
                                    class="block px-12 py-2 hover:bg-emerald-700 transition">
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
