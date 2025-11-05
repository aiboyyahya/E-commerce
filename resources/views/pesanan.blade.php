@extends('layouts.app')

@section('title', 'Pesanan Saya')

@if (session('success'))
    <div class="fixed top-6 right-6 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-bounce">
        {{ session('success') }}
    </div>
@endif

@section('content')
<section class="py-20 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-6 lg:px-12">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-10 flex items-center gap-3">
            Pesanan Saya
        </h1>

        @if ($transactions->isEmpty())
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="text-gray-300 mb-6">
                    <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2" />
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-3">Belum ada pesanan</h2>
                <p class="text-gray-600 mb-8">Anda belum melakukan pembelian apapun.</p>
                <a href="{{ route('products') }}"
                    class="inline-block bg-orange-500 hover:bg-orange-600 text-white py-3 px-8 rounded-xl font-medium shadow-md transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-8">
                @foreach ($transactions as $transaction)
                    @php
                        $statusClass = match ($transaction->status) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'packing' => 'bg-blue-100 text-blue-800',
                            'sent' => 'bg-purple-100 text-purple-800',
                            'done' => 'bg-green-100 text-green-800',
                            default => 'bg-red-100 text-red-800',
                        };
                    @endphp

                    <div
                        class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-all duration-300 relative overflow-hidden">
                        <div class="flex justify-between mb-4">
                            <p class="text-gray-500 text-sm font-medium">
                                Tanggal: {{ $transaction->created_at->format('d M Y, H:i') }}
                            </p>
                            <span
                                class="inline-flex px-4 py-1.5 rounded-full text-sm font-medium {{ $statusClass }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>

                        <div class="space-y-5">
                            @foreach ($transaction->items as $item)
                                <div class="bg-gray-50 p-5 rounded-2xl hover:bg-gray-100 transition">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/80' }}"
                                            class="w-20 h-20 object-cover rounded-xl shadow-sm">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 text-lg truncate">
                                                {{ $item->product->product_name }}
                                            </p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                Jumlah: {{ $item->quantity }} × Rp
                                                {{ number_format($item->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <p class="text-gray-800 font-bold text-lg">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex justify-between items-center border-t pt-5">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Pembayaran</p>
                                <p class="text-xl font-bold text-orange-600">
                                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <a href="{{ route('order.detail', $transaction->id) }}"
                                    class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium shadow-md transition"
                                    aria-label="Detail Pesanan {{ $transaction->order_code }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Detail</span>
                                </a>


                                @if ($transaction->status == 'pending')
                                    <form method="POST" action="{{ route('order.delete', $transaction->id) }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2.5 rounded-lg font-medium transition shadow-sm">
                                            Batal
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
