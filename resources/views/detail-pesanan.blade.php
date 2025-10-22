@extends('layouts.app')

@section('title', 'Detail Pesanan ')

@section('content')
<section class="py-16 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-6 lg:px-16">
        
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 space-y-10">

            <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan</h1>
                    <p class="text-gray-600 font-medium">Order #{{ $transaction->order_code }}</p>
                    <p class="text-sm text-gray-400">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="mt-4 lg:mt-0">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if($transaction->status == 'pending') bg-yellow-100 text-yellow-700
                        @elseif($transaction->status == 'packing') bg-blue-100 text-blue-700
                        @elseif($transaction->status == 'sent') bg-purple-100 text-purple-700
                        @elseif($transaction->status == 'done') bg-green-100 text-green-700
                        @else bg-red-100 text-red-700
                        @endif">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-4">Status Pengiriman</h2>
                <div class="flex items-center justify-between relative">
                    @php
                        $statuses = ['pending', 'packing', 'sent', 'done'];
                        $currentIndex = array_search($transaction->status, $statuses);
                    @endphp

                    @foreach($statuses as $index => $status)
                        <div class="flex flex-col items-center flex-1 relative">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold
                                @if($index <= $currentIndex)
                                    @if($status == 'pending') bg-yellow-500 text-white
                                    @elseif($status == 'packing') bg-blue-500 text-white
                                    @elseif($status == 'sent') bg-purple-500 text-white
                                    @elseif($status == 'done') bg-green-500 text-white
                                    @endif
                                @else bg-gray-300 text-gray-600
                                @endif">
                                {{ $index + 1 }}
                            </div>

                            @if($index < count($statuses)-1)
                                <div class="absolute top-1/2 left-full w-full h-1
                                    @if($index < $currentIndex) bg-orange-500
                                    @else bg-gray-200
                                    @endif"></div>
                            @endif

                            <span class="text-sm text-gray-600 mt-2">
                                {{ $status == 'pending' ? 'Menunggu' : ($status == 'packing' ? 'Dikemas' : ($status == 'sent' ? 'Dikirim' : 'Selesai')) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-xl p-6 space-y-3">
                    <h3 class="text-lg font-semibold">Informasi Pengiriman</h3>
                    <div>
                        <p class="text-sm text-gray-600">Alamat Pengiriman</p>
                        <p class="font-semibold">{{ $transaction->address }}</p>
                    </div>
                    @if($transaction->notes)
                        <div>
                            <p class="text-sm text-gray-600">Catatan</p>
                            <p class="font-semibold">{{ $transaction->notes }}</p>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 rounded-xl p-6 space-y-3">
                    <h3 class="text-lg font-semibold">Ringkasan Pembayaran</h3>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya Pengiriman</span>
                        <span class="font-semibold">Gratis</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="font-bold">Total</span>
                        <span class="font-extrabold text-orange-600">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Produk yang Dipesan</h3>
                <div class="space-y-4">
                    @foreach($transaction->items as $item)
                        <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl bg-white">
                            <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/100' }}"
                                alt="{{ $item->product->product_name }}"
                                class="w-20 h-20 object-cover rounded-xl shadow-sm">

                            <div class="flex-1">
                                <p class="font-semibold text-lg">{{ $item->product->product_name }}</p>
                                <p class="text-gray-600">Jumlah: {{ $item->quantity }}</p>
                                <p class="text-gray-600">Harga: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-6 flex flex-col md:flex-row items-center justify-center gap-4">
                @if($transaction->status == 'pending')
                    <form method="POST" action="{{ route('order.delete', $transaction->id) }}"
                          onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white py-3 px-8 rounded-xl font-semibold hover:bg-red-700 transition shadow">
                            Batalkan Pesanan
                        </button>
                    </form>
                @endif

                <a href="{{ route('orders') }}"
                   class="bg-gray-200 text-gray-800 py-3 px-8 rounded-xl font-semibold hover:bg-gray-300 transition shadow">
                    Kembali
                </a>
            </div>

        </div>
    </div>
</section>
@endsection
