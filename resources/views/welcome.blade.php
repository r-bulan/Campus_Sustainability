<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="bg-gray-100 min-h-screen flex flex-col">

        

        {{-- Main Content Area --}}
        <main class="flex-1 flex items-center justify-center p-6">

            <div class="bg-white shadow-md rounded-xl p-8 max-w-3xl w-full text-center">
                
                {{-- Title --}}
                <h2 class="text-3xl font-bold text-gray-800 mb-4">
                    Welcome to EcoTrack
                </h2>

                {{-- Subtitle --}}
                <p class="text-gray-600 mb-8">
                    sustainability initiatives made simple
                </p>

                {{-- Keep your original SVG --}}
                <div class="flex justify-center mb-8">
                    
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-center gap-4">
                    @auth
                        <a href="/dashboard"
                           class="px-6 py-3 bg-emerald-600 text-white rounded-lg shadow hover:bg-emerald-700 transition">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-6 py-3 bg-gray-800 text-white rounded-lg shadow hover:bg-gray-900 transition">
                            Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-6 py-3 bg-emerald-600 text-white rounded-lg shadow hover:bg-emerald-700 transition">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>

            </div>

        </main>
    </body>
</html>
