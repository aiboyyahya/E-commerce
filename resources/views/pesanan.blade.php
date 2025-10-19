@extends('layouts.app')

@section('title', 'Pesanan Saya - SHOP')

@if(session('success'))
<div class="fixed top-6 right-6 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-50">
    {{ session('success') }}
</div>
@endif

@section('content')
<section class="py-16 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-6 lg:px-16">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Pesanan Saya</h1>

        @if($transactions->isEmpty())
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-10 text-center">
                <div class="text-gray-300 mb-6">
                    <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Belum ada pesanan</h2>
                <p class="text-gray-600 mb-6">Anda belum melakukan pembelian apapun.</p>
                <a href="{{ route('products') }}" class="inline-block bg-orange-500 text-white py-3 px-6 rounded-lg font-medium hover:bg-orange-600 transition-shadow shadow">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($transactions as $transaction)
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Order #{{ $transaction->order_code }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                </div>

                                <div class="ml-4">
                                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                                        @if($transaction->status == 'pending') bg-yellow-50 text-yellow-700
                                        @elseif($transaction->status == 'packing') bg-blue-50 text-blue-700
                                        @elseif($transaction->status == 'sent') bg-purple-50 text-purple-700
                                        @elseif($transaction->status == 'done') bg-green-50 text-green-700
                                        @else bg-red-50 text-red-700
                                        @endif">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-10">
                                @php
                                    $statuses = [
                                        ['key' => 'pending', 'title' => 'Pending', 'label' => 'Menunggu'],
                                        ['key' => 'packing', 'title' => 'Packing', 'label' => 'Dikemas'],
                                        ['key' => 'sent', 'title' => 'Dikirim', 'label' => 'Dalam Pengiriman'],
                                        ['key' => 'done', 'title' => 'Selesai', 'label' => 'Pesanan Selesai']
                                    ];
                                    $currentIndex = array_search($transaction->status, array_column($statuses, 'key'));
                                @endphp

                                <div class="flex justify-between items-start relative">
                                    @foreach($statuses as $index => $step)
                                        <div class="flex-1 flex flex-col items-center text-center">
                                            <p class="text-xs font-semibold mb-2 {{ $index <= $currentIndex ? 'text-orange-600' : 'text-gray-400' }}">
                                                {{ $step['title'] }}
                                            </p>
                                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold {{ $index <= $currentIndex ? 'bg-orange-500 text-white' : 'bg-gray-300 text-gray-500' }}">
                                                {{ $index + 1 }}
                                            </div>
                                            @if($index < count($statuses) - 1)
                                                <div class="w-full h-1 {{ $index < $currentIndex ? 'bg-orange-400' : 'bg-gray-300' }}"></div>
                                            @endif
                                            <p class="text-xs mt-2 {{ $index <= $currentIndex ? 'text-orange-600 font-medium' : 'text-gray-500' }}">
                                                {{ $step['label'] }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="border-t border-gray-100 mt-6 pt-6">
                                <h4 class="text-sm font-semibold text-gray-800 mb-4">Detail Produk</h4>
                                <div class="space-y-4">
                                    @foreach($transaction->items as $item)
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/80' }}" class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate">{{ $item->product->product_name }}</p>
                                            <p class="text-sm text-gray-500 mt-1">Jumlah: {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-gray-700 font-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="mt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Alamat Pengiriman</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $transaction->address }}</p>
                                        @if($transaction->notes)
                                        <p class="text-sm text-gray-500 mt-1">Catatan: {{ $transaction->notes }}</p>
                                        @endif
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Total Pembayaran</p>
                                        <p class="text-xl font-extrabold text-orange-500">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="mt-6 flex items-center gap-3">
                                    <a href="{{ route('order.detail', $transaction->id) }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                        Detail
                                    </a>

                                    @if($transaction->status == 'pending')
                                    <form method="POST" action="{{ route('order.delete', $transaction->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                            Batalkan
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            
            </div>
        @endif
    </div>
</section>
@endsection
