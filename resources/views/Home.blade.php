@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

<section class="bg-gray-100 py-10 md:py-16 rounded-3xl mx-6 mt-4">
    <div class="max-w-7xl mx-auto px-5 md:px-8 lg:px-16 flex flex-col lg:flex-row items-center gap-10">
        <div class="lg:w-1/2 text-center lg:text-left space-y-5">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight">Selamat Datang</h1>
            <p class="text-gray-700 text-base md:text-lg">Temukan produk favoritmu dengan harga spesial !!</p>

            <a href="{{ route('products') }}"
                class="inline-block border border-black text-black px-6 md:px-8 py-3 rounded-lg font-semibold hover:bg-black hover:text-white transition text-sm md:text-base">
                BELANJA SEKARANG
            </a>
        </div>

        <div class="lg:w-1/2 flex justify-center">
            <img src="{{ asset('images/helm.png') }}" alt="Sweater"
                class="w-56 sm:w-64 md:w-72 lg:w-80 object-contain drop-shadow-xl">
        </div>
    </div>
</section>

<section class="py-14 bg-white">
    <div class="max-w-7xl mx-auto px-5 md:px-8 lg:px-16">

        <h2 class="text-2xl md:text-3xl font-bold mb-10 text-center">Produk Unggulan</h2>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-12 ">
            @forelse($products as $product)
                <div class="group bg-gray-50 rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden flex flex-col">
                    <a href="{{ route('product.show', $product->id) }}">
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->product_name }}"
                            class="w-full h-48 sm:h-52 lg:h-56 object-cover group-hover:scale-105 transition duration-300">
                    </a>

                    <div class="p-4 text-center flex flex-col flex-1">
                        <h4 class="text-base md:text-lg font-bold text-black truncate mb-1">
                            {{ $product->product_name }}
                        </h4>

                        @if ($product->description)
                            <p class="text-xs text-gray-500 line-clamp-2 mb-2">{{ $product->description }}</p>
                        @endif

                        <p class="text-lg font-bold text-gray-600 mb-4">
                            Rp {{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}
                        </p>

                        <form action="{{ route('addToCart') }}" method="POST" class="mt-auto w-full">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button
                                class="w-full bg-gray-800 text-white py-2 rounded-lg font-medium hover:bg-gray-900 transition text-sm">
                                Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center col-span-full">Belum ada produk tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

@endsection
