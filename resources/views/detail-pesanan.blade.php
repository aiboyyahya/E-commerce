@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<section class="py-16 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-6 lg:px-12">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">

            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8 pb-5 border-b">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan</h1>
                    <p class="text-gray-600 font-medium">#{{ $transaction->order_code }}</p>
                    <p class="text-sm text-gray-400">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                </div>
                @php
                    $statusClass = match($transaction->status) {
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'packing' => 'bg-blue-100 text-blue-700',
                        'sent' => 'bg-purple-100 text-purple-700',
                        'done' => 'bg-green-100 text-green-700',
                        default => 'bg-red-100 text-red-700'
                    };
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold mb-3">Produk Dipesan</h3>
                    <div class="space-y-3">
                        @foreach($transaction->items as $item)
                        <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border hover:bg-gray-100 transition">
                            <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/80' }}"
                                class="w-20 h-20 rounded-xl object-cover shadow-sm">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-lg">{{ $item->product->product_name }}</p>
                                <p class="text-sm text-gray-600">Jumlah: {{ $item->quantity }}</p>
                                <p class="text-sm text-gray-600">Harga: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-bold text-gray-900 text-lg">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 space-y-3">
                    <h3 class="text-lg font-semibold">Informasi Pengiriman</h3>
                    <div>
                        <p class="text-sm text-gray-600">Alamat</p>
                        <p class="font-semibold text-gray-900">{{ $transaction->address }}</p>
                    </div>
                    @if($transaction->notes)
                    <div>
                        <p class="text-sm text-gray-600">Catatan</p>
                        <p class="font-semibold text-gray-900">{{ $transaction->notes }}</p>
                    </div>
                    @endif
                </div>

                <div class="bg-white border rounded-xl p-6">
                    <h3 class="text-lg font-semibold mb-4">Ringkasan Pembayaran</h3>
                    <div class="flex justify-between mb-2 text-gray-700">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700 mb-4">
                        <span>Biaya Pengiriman</span>
                        <span>Gratis</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between font-semibold mb-5">
                        <span>Total</span>
                        <span class="text-orange-600 font-bold">
                            Rp {{ number_format($transaction->total, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if($transaction->payment_status == 'pending')
                        <a href="{{ route('checkout.payment', $transaction->id) }}"
                           class="inline-block bg-green-600 text-white text-sm px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition shadow-sm">
                            Bayar
                        </a>
                        @endif

                        @if($transaction->status == 'pending')
                        <form method="POST" action="{{ route('order.delete', $transaction->id) }}"
                              onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 text-white text-sm px-4 py-2 rounded-lg font-medium hover:bg-red-700 transition shadow-sm">
                                Batal
                            </button>
                        </form>
                        @endif

                        <a href="{{ route('orders') }}"
                           class="inline-block border border-gray-400 text-gray-700 text-sm px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
