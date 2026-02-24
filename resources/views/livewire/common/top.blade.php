<div x-data class="px-4 flex justify-between items-center shadow-sm z-100">
    <div class="flex gap-1 items-center">
        <button class="px-3 py-2 rounded-md cursor-pointer" @click.prevent="$store.sidebar.handelToggle()">
            <i class="fa-solid fa-bars-staggered text-sm"></i>
        </button>

        <img class="w-10" src="{{ asset('storage/images/logo.png') }}" alt="">
        <div class="flex flex-col">
            <span class="text-[14px] font-bold">{{ __('Nepalese College') }}</span>
            <span class="text-xs font-semibold">{{ __('New baneshwor, Ktm') }}</span>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <!-- Language Switch -->
        <div class="flex gap-3">
            <a href="{{ route('lang.switch', 'en') }}"
                class="text-xs hover:bg-blue-500 p-1 rounded-md text-blue-500 hover:text-white flex gap-1.5 items-center {{ app()->getLocale() === 'en' ? 'bg-blue-500 text-white' : '' }}">
                <img class="w-4" src="{{ Storage::url('images/flag/en.png') }}" alt="">
                English
            </a>
            <a href="{{ route('lang.switch', 'np') }}"
                class="text-xs hover:bg-blue-500 p-1 rounded-md text-blue-500 hover:text-white flex items-center gap-1.5 {{ app()->getLocale() === 'np' ? 'bg-blue-500 text-white' : '' }}">
                <img class="w-4" src="{{ asset('storage/images/flag/np.png') }}" alt="">
                नेपाली
            </a>
        </div>

        <x-theme-toggle />

        <!-- User Dropdown -->
        <div x-data="{ open: false }" class="relative" x-cloak>
            <div class="flex items-center cursor-pointer gap-2" @click="open = !open">
                {{-- <img class="w-7 h-7 rounded-full object-cover" src="{{ asset('storage/images/logo.png') }}"
                    alt=""> --}}
                {{-- <x-avatar placeholder="{{ Auth()->user()->short_name }}" /> --}}
                    <div class="p-1 rounded-full bg-emerald-600 flex items-center justify-between font-medium text-white">
                        {{ Auth()->user()->short_name }}
                    </div>

                <button class="text-xs font-medium cursor-pointer">{{ __(auth()->user()->username) }}</button>
                <i class="fa-solid fa-angle-down duration-300" :class="open ? 'rotate-180' : ''"></i>
            </div>

            <!-- Dropdown menu -->
            <div x-show="open" x-transition @click.outside="open = false"
                class="absolute top-full right-0 mt-2 bg-emerald-600 text-white px-2 py-3 text-xs rounded-md shadow-md z-1000">
                <div class="border-b-2 pb-1 border-white">
                    <h4 class="font-semibold text-white">{{ __(auth()->user()->username) }}</h4>
                    <p class="text-xs">{{ __(auth()->user()->user_type) }}</p>
                </div>
                <ul class="pt-1">
                    <li class="hover:text-gray-300 flex items-center gap-2 py-1">
                        <i class="fa-solid fa-user"></i>
                        <a href="#">{{ __('Profile') }}</a>
                    </li>
                    <li class="hover:text-gray-300 flex items-center gap-2 py-1">
                        <i class="fa-solid fa-gear"></i>
                        <a href="#">{{ __('Settings') }}</a>
                    </li>
                    <li class="hover:text-gray-300 flex items-center gap-2 py-1">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <a href="#">{{ __('Logout') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
