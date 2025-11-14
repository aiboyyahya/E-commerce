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

<section class="py-16 bg-white">
    <div class="max-w-full mx-auto px-5 md:px-8 lg:px-16">

        <h2 class="text-3xl md:text-4xl font-extrabold mb-14 text-center tracking-tight">
            Produk Unggulan
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10">
            @forelse($products as $product)
                <div class="group bg-white border border-gray-200 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1">

                    <a href="{{ route('product.show', $product->id) }}" class="relative block">
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->product_name }}"
                            class="w-full h-52 md:h-60 object-cover rounded-t-3xl group-hover:scale-[1.07] transition duration-500 ease-out">
                    </a>

                    <div class="p-5 flex flex-col flex-1 text-left">
                        <h4 class="text-lg md:text-xl font-bold text-gray-900 mb-1 line-clamp-1">
                            {{ $product->product_name }}
                        </h4>

                        @if ($product->ratingCount > 0)
                            <div class="flex items-center justify gap-1 mb-2 ml-1">
                                @php $avg = round($product->avgRating); @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $avg ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.947a1 1 0 00.95.69h4.148c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.948c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.286-3.947a1 1 0 00-.364-1.118L2.037 9.374c-.783-.57-.38-1.81.588-1.81h4.148a1 1 0 00.95-.69l1.286-3.947z"/>
                                    </svg>
                                @endfor
                                <span class="text-xs text-gray-500">({{ $product->ratingCount }})</span>
                            </div>
                        @endif

                        @if ($product->description)
                            <p class="text-sm text-gray-500 line-clamp-2 mb-3 ml-1 ">
                                {{ $product->description }}
                            </p>
                        @endif

                        <p class="text-xl font-extrabold text-gray-900 mb-5 tracking-tight ">
                            Rp {{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}
                        </p>

                        <form action="{{ route('addToCart') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">

                            <button
                                class="w-full bg-gray-900 text-white py-2.5 rounded-xl font-semibold transition-all duration-300 hover:bg-black hover:shadow-md active:scale-95">
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
