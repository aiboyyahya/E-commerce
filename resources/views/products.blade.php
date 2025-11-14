@extends('layouts.app')

@section('title', 'Produk')

@section('content')
    <section class="py-16 bg-white">
        <div class="max-w-full mx-auto px-6 lg:px-16">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">✨ Semua Produk</h1>
                <p class="text-gray-500 text-base">Temukan produk terbaik kami dengan kualitas unggulan dan harga menarik.
                </p>
            </div>
            <div class="mb-10 bg-white rounded-2xl shadow-sm p-6">
                <form id="filterForm" method="GET" action="{{ route('products') }}">
                    <div class="flex flex-col gap-5">

                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Ketik untuk mencari produk..."
                                class="w-full px-4 py-3 pl-12 border border-gray-600 rounded-xl transition">
                            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            @if (request('search'))
                                <a href="{{ route('products', ['category' => request('category')]) }}"
                                    class="absolute right-4 top-3 text-gray-500 hover:text-gray-700 transition"
                                    title="Hapus pencarian">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <div class="flex flex-wrap justify-center gap-3 mt-3">
                            <button type="submit" name="category" value=""
                                class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-all 
                            {{ !request('category') ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Semua
                            </button>
                            @foreach ($categories as $category)
                                <button type="submit" name="category" value="{{ $category->id }}"
                                    class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-all 
                                {{ request('category') == $category->id ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    {{ $category->category_name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-12 ">
                @forelse($products as $product)
                    <div
                        class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden flex flex-col">
                        <a href="{{ route('product.show', $product->id) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}"
                                class="w-full h-48 sm:h-52 lg:h-56 object-cover group-hover:scale-105 transition duration-300">
                        </a>

                        <div class="p-5 flex flex-col flex-1 text-left">
                            <h4 class="text-base md:text-lg font-bold text-black truncate mb-1">
                                {{ $product->product_name }}
                            </h4>

                            @if ($product->ratingCount > 0)
                                <div class="flex items-center justify gap-1 mb-2 ml-1">
                                    @php $avg = round($product->avgRating); @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $avg ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.947a1 1 0 00.95.69h4.148c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.948c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.286-3.947a1 1 0 00-.364-1.118L2.037 9.374c-.783-.57-.38-1.81.588-1.81h4.148a1 1 0 00.95-.69l1.286-3.947z" />
                                        </svg>
                                    @endfor
                                    <span class="text-xs text-gray-600 ml-1">({{ $product->ratingCount }})</span>
                                </div>
                            @endif

                            @if ($product->description)
                                <p class="text-sm text-gray-500 line-clamp-2 mb-3 ml-1">{{ $product->description }}</p>
                            @endif

                            <p class="text-xl font-extrabold text-gray-900 mb-5 tracking-tight">
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

            @if ($products->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </section>


@endsection
