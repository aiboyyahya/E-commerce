@extends('layouts.app')

@section('title', $product->product_name . ' - SHOP')

@section('content')
    <section class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-16 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <div class="bg-white rounded-2xl shadow-sm p-4 flex justify-center">
                    <img id="main-product-image"
                        src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500x500' }}"
                        alt="{{ $product->product_name }}"
                        class="w-full max-w-md rounded-xl object-cover">
                </div>
                @if ($product->images && $product->images->count())
                    <div class="flex gap-2 mt-4 justify-center">
                        @foreach ($product->images as $img)
                            <img src="{{ asset('storage/' . $img->image_file) }}" alt="Gambar Tambahan"
                                class="w-20 h-20 object-cover rounded-lg shadow cursor-pointer hover:ring-2 hover:ring-orange-500 transition"
                                onclick="document.getElementById('main-product-image').src='{{ asset('storage/' . $img->image_file) }}'">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex flex-col gap-2">
                <div class="bg-white rounded-2xl shadow p-6 space-y-5 mb-2">
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">{{ $product->product_name }}</h1>
                    <p class="text-2xl font-semibold text-orange-600">
                        Rp {{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-gray-700 text-base">{{ $product->description }}</p>

                    <div class="flex flex-col gap-4">
                        <form action="{{ route('addToCart') }}" method="POST" id="addToCartForm" class="flex flex-col gap-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="flex items-center gap-2">
                                <button type="button" id="decrement-btn"
                                    class="w-10 h-10 rounded-full bg-gray-200 text-gray-800 font-bold hover:bg-gray-300 transition">-</button>
                                <input type="number" name="quantity" id="product-quantity" value="1" min="1"
                                    max="{{ $product->stock }}"
                                    class="w-20 py-2 px-3 rounded-lg text-center focus:ring-2 focus:ring-orange-500" readonly>
                                <button type="button" id="increment-btn"
                                    class="w-10 h-10 rounded-full bg-gray-200 text-gray-800 font-bold hover:bg-gray-300 transition">+</button>
                            </div>

                            <div class="mt-4 bg-white border rounded-lg p-3 shadow-sm text-sm">
                                <h2 class="font-semibold text-gray-800 mb-2 text-center">Informasi Produk</h2>
                                <table class="w-full text-gray-700">
                                    <tbody>
                                        <tr>
                                            <td class="py-1 font-medium">Kategori</td>
                                            <td class="py-1">{{ $product->category->category_name ?? 'Tidak ada' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-1 font-medium">Stok</td>
                                            <td class="py-1">{{ $product->stock ?? '0' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <button type="submit"
                                    class="flex-1 bg-gray-800 text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-900 transition">
                                    Tambah ke Keranjang
                                </button>
                                <a id="checkout-link"
                                    href="{{ route('checkout.page', ['product_id' => $product->id, 'quantity' => 1]) }}"
                                    class="flex-1 bg-orange-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-orange-700 transition text-center">
                                    Checkout Sekarang
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const increment = document.getElementById('increment-btn');
            const decrement = document.getElementById('decrement-btn');
            const quantityInput = document.getElementById('product-quantity');
            const checkoutLink = document.getElementById('checkout-link');
            const maxStock = parseInt(quantityInput.max);

            function updateQuantity(incrementing) {
                let val = parseInt(quantityInput.value);
                if (incrementing && val < maxStock) {
                    quantityInput.value = val + 1;
                } else if (!incrementing && val > 1) {
                    quantityInput.value = val - 1;
                }
                updateCheckoutLink();
            }

            function updateCheckoutLink() {
                const currentQuantity = quantityInput.value;
                const baseUrl =
                    `{{ route('checkout.page', ['product_id' => $product->id, 'quantity' => '__QUANTITY__']) }}`;
                checkoutLink.href = baseUrl.replace('__QUANTITY__', currentQuantity);
            }

            increment.addEventListener('click', () => updateQuantity(true));
            decrement.addEventListener('click', () => updateQuantity(false));
        });
    </script>
@endsection
