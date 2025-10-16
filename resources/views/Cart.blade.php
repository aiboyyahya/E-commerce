@extends('layouts.app')

@section('title', 'Keranjang Belanja - SHOP')

@section('content')
    <section class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-16">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (count($cart) > 0)
                <div class="bg-white rounded-2xl shadow p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-gray-700">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-4">Produk</th>
                                    <th class="text-center py-4">Harga</th>
                                    <th class="text-center py-4">Jumlah</th>
                                    <th class="text-center py-4">Total</th>
                                    <th class="text-center py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach ($cart as $id => $item)
                                    @php
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total += $subtotal;
                                    @endphp
                                    <tr class="border-b">
                                        <td class="py-4 flex items-center gap-4">
                                            <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/100' }}"
                                                alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                                            <span>{{ $item['name'] }}</span>
                                        </td>
                                        <td class="text-center py-4">Rp {{ number_format($item['price'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center py-4">{{ $item['quantity'] }}</td>
                                        <td class="text-center py-4">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        <td class="text-center py-4">
                                            <form action="{{ route('removeCart', $id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>   
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-xl font-semibold">
                            Total: Rp {{ number_format($total, 0, ',', '.') }}
                        </div>
                        <div class="flex gap-4">
                            <a href="{{ route('home') }}"
                                class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700 transition">Lanjut
                                Belanja</a>
                            <a href="#"
                                class="bg-orange-600 text-white py-2 px-4 rounded hover:bg-orange-700 transition">Checkout</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <p class="text-gray-600 mb-4">Keranjang Anda kosong.</p>
                    <a href="{{ route('home') }}"
                        class="bg-orange-600 text-white py-2 px-4 rounded hover:bg-orange-700 transition">Mulai Belanja</a>
                </div>
            @endif
        </div>
    </section>
@endsection
