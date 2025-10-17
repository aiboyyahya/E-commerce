@extends('layouts.app')

@section('title', 'Produk - ' . ($store->store_name ?? 'SHOP'))

@section('content')
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">✨ Semua Produk</h1>
            <p class="text-gray-500 text-base">Temukan produk terbaik kami dengan kualitas unggulan dan harga menarik.</p>
        </div>
        <div class="mb-10 bg-white rounded-2xl shadow-sm p-6">
            <form id="filterForm" method="GET" action="{{ route('products') }}">
                <div class="flex flex-col gap-5">

                    <div class="relative w-full">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Ketik untuk mencari produk..."
                            class="w-full px-4 py-3 pl-12 border border-gray-600 rounded-xl transition"
                        >
                        <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        @if(request('search'))
                            <a href="{{ route('products', ['category' => request('category')]) }}" 
                               class="absolute right-4 top-3 text-gray-500 hover:text-gray-700 transition"
                               title="Hapus pencarian">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
                        @foreach($categories as $category)
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

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @forelse($products as $product)
                <div class="group bg-white rounded-2xl border border-gray-100 shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col">
                    <a href="{{ route('product.show', $product->id) }}" class="relative overflow-hidden">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                            alt="{{ $product->product_name }}" 
                            class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent opacity-0 group-hover:opacity-100 transition"></div>
                    </a>

                    <div class="p-5 text-center flex flex-col flex-1">
                        <h4 class="text-lg font-semibold text-gray-900 truncate mb-1">{{ $product->product_name }}</h4>

                        @if (!empty($product->description))
                            <p class="text-sm text-gray-500 line-clamp-2 mb-2">{{ $product->description }}</p>
                        @endif

                        <p class="text-lg font-bold text-orange-600 mb-4">
                            Rp {{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}
                        </p>

                        <form action="{{ route('addToCart') }}" method="POST" class="mt-auto w-full">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                             <button
                                    class="w-full bg-gray-800 text-white py-2 rounded-lg font-medium hover:bg-gray-900 transition">
                                    Tambah ke Keranjang
                                </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center col-span-full">Belum ada produk ditemukan.</p>
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</section>


@endsection
