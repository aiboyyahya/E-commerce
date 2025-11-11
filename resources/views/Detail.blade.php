@extends('layouts.app')

@section('title', $product->product_name)

@section('content')
    <section class="min-h-screen bg-white py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-16">
            <div class="bg-white rounded-3xl p-10 shadow-md border border-gray-200">
                <h1 class="text-4xl font-bold text-center  text-gray-900">Detail Produk</h1>
                <p class="text-gray-600 mt-2 mb-10 text-center">{{$product->product_name}}</p>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                    <div>
                        <div class="aspect-square bg-gray-50 rounded-3xl shadow-inner flex items-center justify-center p-8 border border-gray-200">
                            <img id="main-product-image"
                                src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500x500' }}"
                                alt="{{ $product->product_name }}" class="object-contain w-full h-full rounded-2xl">
                        </div>

                        @if ($product->images && $product->images->count())
                            <div class="flex space-x-4 mt-6 justify-center">
                                @foreach ($product->images as $img)
                                    <button type="button"
                                        onclick="document.getElementById('main-product-image').src='{{ asset('storage/' . $img->image_file) }}'"
                                        class="w-20 h-20 rounded-xl overflow-hidden shadow cursor-pointer border border-gray-200 hover:border-orange-500 transition">
                                        <img src="{{ asset('storage/' . $img->image_file) }}" alt="Thumbnail"
                                            class="object-cover w-full h-full">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col max-w-lg border border-gray-200 rounded-3xl p-8 shadow-sm bg-white">
                        <h2 class="text-4xl font-semibold text-gray-900">{{ $product->product_name }}</h2>
                        <p class="mt-2 text-gray-700">{{ $product->description }}</p>

                        <div class="flex items-center mt-4 space-x-2 text-yellow-300">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.947a1 1 0 00.95.69h4.148c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.948c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.286-3.947a1 1 0 00-.364-1.118L2.037 9.374c-.783-.57-.38-1.81.588-1.81h4.148a1 1 0 00.95-.69l1.286-3.947z" />
                                </svg>
                            @endfor
                            <span class="text-gray-600 text-sm font-medium">({{ $product->rating ?? 121 }} ulasan)</span>
                        </div>

                        <p class="mt-6 text-3xl font-bold text-gray-900">
                            Rp {{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}
                        </p>

                        <form action="{{ route('addToCart') }}" method="POST" id="addToCartForm"
                            class="mt-8 flex flex-col space-y-6 max-w-sm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="flex items-center gap-4">
                                <button type="button" id="decrement-btn"
                                    class="w-12 h-12 rounded-full bg-gray-200 text-gray-800 font-bold hover:bg-gray-300">−</button>
                                <input type="number" name="quantity" id="product-quantity" value="1" min="1"
                                    max="{{ $product->stock }}" readonly
                                    class="w-20 text-center rounded-lg border border-gray-300 py-3 font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                                <button type="button" id="increment-btn"
                                    class="w-12 h-12 rounded-full bg-gray-200 text-gray-800 font-bold hover:bg-gray-300">+</button>
                            </div>

                            <p class="text-sm text-gray-600 font-semibold">
                                Hanya tersisa {{ $product->stock }} barang
                            </p>

                            <div class="flex gap-6">
                                <a href="{{ route('checkout.page', ['product_id' => $product->id, 'quantity' => 1, 'direct' => 1]) }}"
                                    class="flex-1 py-4 bg-black text-white rounded-lg font-semibold text-center hover:bg-gray-700 transition">
                                    Beli Sekarang
                                </a>

                                <button type="submit"
                                    class="flex-1 py-4 border border-black text-gray-700 rounded-lg font-semibold hover:bg-green-50 transition">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </form>

                        <div class="mt-10 space-y-6 max-w-sm text-gray-700 text-sm border-t border-gray-200 pt-6">
                            <div class="flex items-start gap-4">
                                <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v13H3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16v4m10-4v4M9 20h6" />
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Pengiriman Gratis</h3>
                                    <p>Masukkan kode pos Anda untuk melihat ketersediaan pengiriman.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Pengembalian Barang</h3>
                                    <p>Gratis pengembalian dalam 30 hari</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const increment = document.getElementById('increment-btn');
            const decrement = document.getElementById('decrement-btn');
            const quantityInput = document.getElementById('product-quantity');
            const maxStock = parseInt(quantityInput.max);

            increment.addEventListener('click', () => {
                let current = parseInt(quantityInput.value);
                if (current < maxStock) quantityInput.value = current + 1;
            });

            decrement.addEventListener('click', () => {
                let current = parseInt(quantityInput.value);
                if (current > 1) quantityInput.value = current - 1;
            });
        });
    </script>
@endsection
