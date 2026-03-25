<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>403 - Unauthorized</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-200 to-emerald-400">

    <div class="bg-white/80 backdrop-blur-lg shadow-2xl rounded-2xl p-10 max-w-md w-full text-center border border-white/30">

        <!-- Error Code -->
        <h1 class="text-7xl font-extrabold text-emerald-600">403</h1>

        <!-- Title -->
        <h2 class="text-2xl font-semibold mt-4 text-gray-800">
            Access Denied 🚫
        </h2>

        <!-- Message -->
        <p class="text-gray-600 mt-2">
            You don’t have permission to view this page.
        </p>

        <!-- Optional dynamic message -->
        @if(!empty($exception->getMessage()))
            <p class="text-sm text-red-500 mt-2">
                {{ $exception->getMessage() }}
            </p>
        @endif

        <!-- Buttons -->
        <div class="mt-6 flex flex-col gap-3">

            <a href="{{ url()->previous() }}"
               class="w-full py-2 rounded-lg bg-emerald-500 text-white font-medium hover:bg-emerald-600 transition">
                ⬅ Go Back
            </a>

            <a href="{{ route('dashboard') }}"
               class="w-full py-2 rounded-lg bg-gray-800 text-white font-medium hover:bg-gray-900 transition">
                🏠 Dashboard
            </a>

        </div>

    </div>

</body>
</html>
