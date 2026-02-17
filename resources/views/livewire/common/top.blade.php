<div x-data class="px-4 flex justify-between items-center shadow-lg z-100"
    :class="$store.darkmode.toggle ? 'bg-gray-900 text-white' : 'bg-white text-black'">
    <div class="flex gap-1 items-center">
        <button class="px-3 py-2 rounded-md cursor-pointer" @click.prevent="$store.sidebar.handelToggle()">
            <i class="fa-solid fa-bars-staggered text-sm"></i>
        </button>

        <img class="w-10" src="{{ asset('storage/images/logo.png') }}" alt="">
        <div class="flex flex-col">
            <span class="text-[14px] font-bold">Nepalese College</span>
            <span class="text-[11px] font-semibold">New baneshwor, Ktm</span>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <!-- Language Switch -->
        <div class="flex gap-3">
            <a href="{{ route('lang.switch', 'en') }}"
                class="text-[13px] hover:bg-blue-500 p-1 rounded-md text-blue-500 hover:text-white flex gap-1.5 {{ app()->getLocale() === 'en' ? 'bg-blue-500 text-white' : '' }}">
                <img class="w-5" src="{{ Storage::url('images/flag/en.png') }}" alt="">
                English
            </a>
            <a href="{{ route('lang.switch', 'np') }}"
                class="text-[13px] hover:bg-blue-500 p-1 rounded-md text-blue-500 hover:text-white flex {{ app()->getLocale() === 'np' ? 'bg-blue-500 text-white' : '' }}">
                <img class="w-5" src="{{ asset('storage/images/flag/np.png') }}" alt="">
                नेपाली
            </a>
        </div>

        <!-- Theme toggle -->
        <button @click.prevent="$store.darkmode.toggleTheme()" class="cursor-pointer">
            <i :class="$store.darkmode.toggle ? 'fa-regular fa-sun' : 'fa-regular fa-moon'"></i>
        </button>

        <!-- User Dropdown -->
        <div x-data="{ open: false }" class="relative" x-cloak>
            <div class="flex items-center cursor-pointer gap-2" @click="open = !open">
                <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('storage/images/logo.png') }}"
                    alt="">
                <button class="text-[14px] cursor-pointer">{{ __('Sudeep Magar') }}</button>
                <i class="fa-solid fa-angle-down duration-300" :class="open ? 'rotate-180' : ''"></i>
            </div>

            <!-- Dropdown menu -->
            <div x-show="open" x-transition @click.outside="open = false"
                class="absolute top-full right-0 mt-2 bg-emerald-600 text-white px-2 py-3 text-sm rounded-md shadow-md z-[1000]">
                <div class="border-b-2 pb-1 border-white">
                    <h4 class="font-semibold text-white">Sudeep Magar</h4>
                    <p class="text-xs">Admin</p>
                </div>
                <ul class="pt-1">
                    <li class="hover:text-gray-300 flex items-center gap-2 py-1">
                        <i class="fa-solid fa-user"></i>
                        <a href="#">Profile</a>
                    </li>
                    <li class="hover:text-gray-300 flex items-center gap-2 py-1">
                        <i class="fa-solid fa-gear"></i>
                        <a href="#">Settings</a>
                    </li>
                    <li class="hover:text-gray-300 flex items-center gap-2 py-1">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <a href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
