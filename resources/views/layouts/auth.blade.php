<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body x-data class="transition-colors duration-300"
    :class="$store.darkmode.toggle ? 'bg-gray-900 text-white' : 'bg-white text-black'">
    {{-- @include('livewire.common.navbar') --}}
    {{ $slot }}

    @livewireScripts
    <x-toast />
</body>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('darkmode', {
            toggle: localStorage.getItem('theme') === 'dark', // load saved theme
            toggleTheme() {
                this.toggle = !this.toggle;
                document.documentElement.classList.toggle('dark', this.toggle);
                localStorage.setItem('theme', this.toggle ? 'dark' : 'light');
            },
        });

        // Apply theme immediately
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    });
</script>

</html>
