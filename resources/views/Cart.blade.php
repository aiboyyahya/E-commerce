@extends('layouts.app')

@section('title', 'Keranjang Belanja ')

@section('content')
    <section class="py-16 bg-gray-100 min-h-screen">
        <div class="max-w-6xl mx-auto px-6 lg:px-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Keranjang Belanja</h1>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if (count($cart) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-4">
                        @php $total = 0; @endphp
                        @foreach ($cart as $id => $item)
                            @php
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            @endphp
                            <div class="bg-white rounded-2xl shadow p-6 hover:shadow-xl transition">
                                <div class="flex flex-col md:flex-row items-center md:items-start gap-5">
                                    <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/120' }}"
                                        alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded-xl shadow-sm">

                                    <div class="flex-1 text-center md:text-left">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $item['name'] }}</h3>
                                        <p class="text-orange-600 font-bold text-lg">Rp
                                            {{ number_format($item['price'], 0, ',', '.') }}</p>

                                        <div class="flex justify-center md:justify-start items-center gap-3 mt-3">
                                            <form action="{{ route('updateCart', $id) }}" method="POST"
                                                class="flex items-center gap-2" id="quantity-form-{{ $id }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button"
                                                    onclick="updateQuantity({{ $id }}, {{ $item['quantity'] - 1 }})"
                                                    class="w-8 h-8 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition"
                                                    {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>-</button>

                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                                    min="1" readonly id="quantity-{{ $id }}"
                                                    class="w-12 text-center  rounded px-2 py-1 text-sm">

                                                <button type="button"
                                                    onclick="updateQuantity({{ $id }}, {{ $item['quantity'] + 1 }})"
                                                    class="w-8 h-8 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition">+</button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="lg:col-span-2 text-center lg:text-right mt-5">
                                        <p class="text-sm text-gray-600 mb-1">Subtotal</p>
                                        <p class="text-lg font-bold text-gray-900">Rp
                                            {{ number_format($subtotal, 0, ',', '.') }}</p>
                                    </div> 
                                    
                                    <div class="lg:col-span-2 flex justify-center lg:justify-end mt-7">
                                        <form action="{{ route('removeCart', $id) }}" method="POST" class="inline"> @csrf
                                            @method('DELETE') <button type="submit"
                                                class="text-red-500 hover:text-red-700 transition p-2 rounded-full hover:bg-red-50">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg> 
                                            </button> 
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 h-fit sticky top-24">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Belanja</h2>

                        <div class="flex justify-between text-gray-700 mb-2">
                            <span>Total Produk</span>
                            <span>{{ count($cart) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700 mb-4">
                            <span>Total Pembayaran</span>
                            <span class="text-lg font-bold text-orange-600">Rp
                                {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('checkout.page') }}"
                            class="block w-full bg-orange-600 text-white py-3 rounded-xl text-center font-medium hover:bg-orange-700 transition shadow-lg hover:shadow-xl mb-3">
                            Checkout Sekarang
                        </a>
                        <a href="{{ route('home') }}"
                            class="block w-full border border-gray-400 text-gray-700 py-3 rounded-xl text-center font-medium hover:bg-gray-100 transition">
                            Lanjut Belanja
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13l-1.1 5M7 13h10m0 0v8a2 2 0 01-2 2H9a2 2 0 01-2-2v-8z">
                        </path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Keranjang Kosong</h3>
                    <p class="text-gray-600 mb-4">Belum ada produk di keranjang belanja Anda.</p>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center bg-orange-600 text-white py-3 px-8 rounded-xl hover:bg-orange-700 transition font-medium shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Mulai Belanja
                    </a>
                </div>
            @endif
        </div>
    </section>

    <script>
        function updateQuantity(productId, newQuantity) {
            if (newQuantity < 1) return;
            const form = document.getElementById('quantity-form-' + productId);
            const quantityInput = document.getElementById('quantity-' + productId);
            quantityInput.value = newQuantity;
            form.submit();
        }
    </script>
@endsection
