@php
    $store = \App\Models\Store::first();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $store->store_name ?? 'SHOP')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-800">

<nav class="flex justify-between items-center px-8 py-4 shadow-sm border-b relative">
    <div class="text-2xl font-bold">{{ $store->store_name ?? 'SHOP' }}</div>

    <div class="flex space-x-6 text-sm font-medium">
        <a href="{{ url('/') }}" class="hover:text-gray-900 transition">Home</a>
        <a href="{{ url('/shop') }}" class="hover:text-gray-900 transition">Shop</a>
        <a href="#" class="hover:text-gray-900 transition">About</a>
        <a href="#" class="hover:text-gray-900 transition">Contact</a>
    </div>

    <div class="flex items-center space-x-4 text-sm relative">
        @auth
            <div class="relative group">
                <button class="flex items-center space-x-2 px-2 py-1 rounded-full hover:bg-gray-100 transition font-medium">
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span>{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all">
                    <a href="{{ url('/profile') }}" class="block px-4 py-2 hover:bg-gray-100 transition">Profil</a>
                    <a href="{{ url('/orders') }}" class="block px-4 py-2 hover:bg-gray-100 transition">Pesanan</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 transition">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('google.redirect') }}" 
               class="px-4 py-2 bg-white border border-black rounded-full hover:bg-black hover:text-white transition font-medium">
                Login
            </a>
        @endauth
        <span class="text-xl cursor-pointer hover:text-gray-700 transition">🛒</span>
    </div>
</nav>

<main class="min-h-screen">
    @yield('content')
</main>

<footer class="text-center text-gray-500 py-6 border-t text-sm">
    @if($store)
        <div class="flex flex-col md:flex-row justify-center items-center space-y-2 md:space-y-0 md:space-x-6 mb-2">
            @if(!empty($store->instagram))
                <div>
                    <a href="https://instagram.com/{{ ltrim($store->instagram, '@') }}" target="_blank" class="hover:underline">
                        Instagram: {{ $store->instagram }}
                    </a>
                </div>
            @endif
            @if(!empty($store->tiktok))
                <div>
                    <a href="https://www.tiktok.com/@{{ ltrim($store->tiktok, '@') }}" target="_blank" class="hover:underline">
                        TikTok: {{ $store->tiktok }}
                    </a>
                </div>
            @endif
            @if(!empty($store->facebook))
                <div>
                    <a href="{{ $store->facebook }}" target="_blank" class="hover:underline">
                        Facebook: {{ $store->facebook }}
                    </a>
                </div>
            @endif
            @if(!empty($store->whatsapp))
                <div>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $store->whatsapp) }}" target="_blank" class="hover:underline">
                        WhatsApp: {{ $store->whatsapp }}
                    </a>
                </div>
            @endif
        </div>

        <div class="flex flex-col md:flex-row justify-center items-center space-y-2 md:space-y-0 md:space-x-6 mb-2">
            @if(!empty($store->shopee))
                <div>
                    <a href="{{ $store->shopee }}" target="_blank" class="hover:underline">Shopee</a>
                </div>
            @endif
            @if(!empty($store->tokopedia))
                <div>
                    <a href="{{ $store->tokopedia }}" target="_blank" class="hover:underline">Tokopedia</a>
                </div>
            @endif
        </div>
    @endif
    <div class="mt-2">&copy; {{ date('Y') }} {{ $store->store_name ?? 'SHOP' }}. All rights reserved.</div>
</footer>



</body>
</html>
