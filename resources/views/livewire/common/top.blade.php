<div x-data
     class="px-3 py-2 flex justify-between items-center bg-white dark:bg-[#1D232A] border-b border-gray-200 dark:border-gray-700 shadow-sm sticky top-0 z-50">

    <!-- Left Section - Logo & Toggle -->
    <div class="flex gap-3 items-center">
        <!-- Sidebar Toggle Button -->
        <button
            class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-emerald-600 text-gray-600 dark:text-white hover:text-white flex items-center justify-center transition-all duration-200 group cursor-pointer"
            @click.prevent="$store.sidebar.handelToggle()">
            <i class="fa-solid fa-bars-staggered text-sm group-hover:scale-110 transition-transform"></i>
        </button>

        <!-- Logo & College Info -->
        <div class="flex items-center gap-3 pl-2 border-l-2 border-emerald-200">
            <div
                class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-md">
                <img class="w-8 h-8 object-contain" src="{{ asset('storage/images/logo.png') }}" alt="College Logo">
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold text-gray-800 dark:text-white">{{ __('Nepalese College') }}</span>
                <span class="text-xs text-gray-500 flex items-center gap-1">
                    <i class="fa-solid fa-location-dot text-emerald-500 text-[10px]"></i>
                    {{ __('New baneshwor, Ktm') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Right Section - Actions & User -->
    <div class="flex items-center gap-4">
        <!-- Language Switcher - Modern Toggle Style -->
        <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-xl p-1">
            <a href="{{ route('lang.switch', 'en') }}"
               class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200
               {{ app()->getLocale() === 'en' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-600 dark:text-white hover:text-gray-900 dark:hover:text-gray-300' }}">
                <img class="w-4 h-4 rounded-sm object-cover" src="{{ Storage::url('images/flag/en.png') }}" alt="">
                <span class="hidden sm:inline">English</span>
            </a>
            <a href="{{ route('lang.switch', 'np') }}"
               class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200
               {{ app()->getLocale() === 'np' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-600 dark:text-white hover:text-gray-900 dark:hover:text-gray-300' }}">
                <img class="w-4 h-4 rounded-sm object-cover" src="{{ asset('storage/images/flag/np.png') }}" alt="">
                <span class="hidden sm:inline">नेपाली</span>
            </a>
        </div>

        <!-- Theme Toggle with enhanced styling -->
        <div
            class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center hover:bg-emerald-600 group transition-colors duration-200">
            <x-theme-toggle/>
        </div>

        <!-- User Dropdown -->
        <div x-data="{ open: false }" class="relative" x-cloak>
            <!-- User Button -->
            <div
                class="flex items-center gap-3 cursor-pointer bg-gray-100 dark:bg-gray-800 hover:bg-emerald-600 rounded-xl pl-3 pr-2 py-1.5 transition-all duration-200 group"
                @click="open = !open">

                @php
                    $user = Auth::user();
                @endphp
                    <!-- User Avatar -->
                @if($user->profile && $user->profile->photo)
                    <img class="w-8 h-8 rounded-lg" src="{{ asset('storage/'.$user->profile->photo) }}"
                         alt="Profile">
                @else
                    <div
                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-sm shadow-sm group-hover:scale-105 transition-transform">
                        {{ Auth()->user()->short_name }}
                    </div>
                @endif

                <!-- User Info -->
                <div class="hidden md:block text-left">
                    <p class="text-xs font-medium text-gray-700 dark:text-gray-200 group-hover:text-white">{{ __(auth()->user()->username) }}</p>
                    <p class="text-[10px] text-gray-500 dark:text-gray-200 group-hover:text-emerald-100">{{ __(auth()->user()->user_type) }}</p>
                </div>

                <!-- Dropdown Icon -->
                <i class="fa-solid fa-chevron-down text-xs text-gray-400 group-hover:text-white transition-all duration-200"
                   :class="{ 'rotate-180': open }"></i>
            </div>

            <!-- Dropdown Menu - Modern Card Design -->
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 @click.outside="open = false"
                 class="absolute top-full right-0 mt-2 w-64 bg-white dark:bg-[#1D232A] rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">

                <!-- User Header -->
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 p-4">
                    <div class="flex items-center gap-3">

                        @if($user->profile && $user->profile->photo)
                            <img class="w-8 h-8 rounded-lg" src="{{ asset('storage/'.$user->profile->photo) }}"
                                 alt="Profile">
                        @else
                            <div
                                class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-white font-bold text-lg">
                                {{ Auth()->user()->short_name }}
                            </div>
                        @endif
                            <div class="text-white">
                                <h4 class="font-semibold text-sm">{{ __(auth()->user()->username) }}</h4>
                                @if($user->profile_type == 'App\Models\Student\Student')
                                    <p class="text-xs text-emerald-100">{{ __('Student') }} </p>
                                @else
                                    <p class="text-xs text-emerald-100">{{ __('Admin') }} </p>

                                @endif
                            </div>
                    </div>
                </div>

                <!-- Menu Items -->
                <div class="p-2">
                    <a href="#"
                       class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 dark:text-gray-200   group-hover:bg-emerald-100 flex items-center justify-center text-gray-500 group-hover:text-emerald-600">
                            <i class="fa-solid fa-user text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium">{{ __('Profile') }}</p>
                            <p class="text-xs text-gray-500">{{ __('View and edit your profile') }}</p>
                        </div>
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 dark:text-gray-200 group-hover:bg-emerald-100 flex items-center justify-center text-gray-500 group-hover:text-emerald-600">
                            <i class="fa-solid fa-gear text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium">{{ __('Settings') }}</p>
                            <p class="text-xs text-gray-500">{{ __('Manage preferences') }}</p>
                        </div>
                    </a>

                    <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

                    <a href="#"
                       class="flex items-center gap-3 px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-8 h-8 rounded-lg bg-red-50 group-hover:bg-red-100 flex items-center justify-center text-red-500">
                            <i class="fa-solid fa-right-from-bracket text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium">{{ __('Logout') }}</p>
                            <p class="text-xs text-gray-500">{{ __('Sign out of your account') }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
