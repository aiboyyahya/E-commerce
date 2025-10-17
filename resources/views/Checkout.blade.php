@extends('layouts.app')

@section('title', 'Checkout - SHOP')

@section('content')
    <section class="py-16 bg-gray-100">
        <div class="max-w-4xl mx-auto px-6 lg:px-16">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Detail Pesanan</h2>
                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-gray-700">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-4">Produk</th>
                                <th class="text-center py-4">Harga</th>
                                <th class="text-center py-4">Jumlah</th>
                                <th class="text-center py-4">Total</th>
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
                                    <td class="text-center py-4">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td class="text-center py-4">{{ $item['quantity'] }}</td>
                                    <td class="text-center py-4">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-right mb-6">
                    <div class="text-xl font-semibold">
                        Total: Rp {{ number_format($total, 0, ',', '.') }}
                    </div>
                </div>

                <form action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman</label>
                            <textarea name="address" id="address" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500" required>{{ Auth::user()->address }}</textarea>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <a href="{{ route('cart') }}" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700 transition">Kembali ke Keranjang</a>
                        <button type="submit" class="bg-orange-600 text-white py-2 px-4 rounded hover:bg-orange-700 transition">Konfirmasi Pesanan</button>
                    </div>
                </form>

                <div class="mt-4 text-sm text-gray-600">
                    <p><strong>Catatan:</strong> Status pembayaran akan diatur sebagai "Pending" dan siap untuk integrasi dengan Midtrans.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
