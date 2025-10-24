@extends('layouts.app')

@section('title', 'Checkout Berhasil - SHOP')

@section('content')
    <section class="py-16 bg-gray-100">
        <div class="max-w-4xl mx-auto px-6 lg:px-16 text-center">
            <div class="bg-white rounded-2xl shadow p-8">
                <div class="text-green-500 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Checkout Berhasil!</h1>
                <p class="text-gray-600 mb-6">Pesanan Anda telah diterima dan sedang diproses.</p>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif



                <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
                    <h2 class="text-xl font-semibold mb-4">Detail Pesanan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Kode Pesanan</p>
                            <p class="font-semibold">{{ $transaction->order_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="font-semibold text-orange-600">{{ ucfirst($transaction->status) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status Pembayaran</p>
                            <p
                                class="font-semibold
                                @if ($transaction->payment_status == 'paid') text-green-600
                                @elseif($transaction->payment_status == 'pending') text-orange-600
                                @elseif($transaction->payment_status == 'failed') text-red-600
                                @else text-gray-600 @endif">
                                {{ ucfirst($transaction->payment_status) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total</p>
                            <p class="font-semibold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal</p>
                            <p class="font-semibold">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">Alamat Pengiriman</p>
                        <p class="font-semibold">{{ $transaction->address }}</p>
                    </div>
                    @if ($transaction->notes)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">Catatan</p>
                            <p class="font-semibold">{{ $transaction->notes }}</p>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
                    <h3 class="text-lg font-semibold mb-4">Produk yang Dipesan</h3>
                    <div class="space-y-4">
                        @foreach ($transaction->items as $item)
                            <div class="flex items-center gap-4">
                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/100' }}"
                                    alt="{{ $item->product->product_name }}" class="w-16 h-16 object-cover rounded">
                                <div class="flex-1">
                                    <p class="font-semibold">{{ $item->product->product_name }}</p>
                                    <p class="text-sm text-gray-600">Jumlah: {{ $item->quantity }} | Harga: Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">Rp
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('home') }}"
                        class="bg-orange-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-orange-700 transition">Lanjut
                        Belanja</a>
                    <a href="{{ route('orders') }}"
                        class="bg-gray-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-700 transition">Lihat
                        Pesanan</a>
                </div>
            </div>
        </div>
    </section>



@endsection
