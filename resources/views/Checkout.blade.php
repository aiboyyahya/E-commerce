@extends('layouts.app')

@section('title', 'Checkout')

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
                            @php
                                $items = isset($directCheckout) && !empty($directCheckout) ? $directCheckout : $cart;
                            @endphp
                            @foreach ($items as $id => $item)
                                @php
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                @endphp
                                <tr class="border-b">
                                    <td class="py-4 flex items-center gap-4">
                                        <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/100' }}"
                                            class="w-16 h-16 object-cover rounded">
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

                <form id="checkout-form" action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman</label>
                            <textarea name="address" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500" required>{{ Auth::user()->address }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea name="notes" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"></textarea>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Metode Pembayaran</label>
                        <div class="space-y-3">
                            <label
                                class="flex items-center p-4 border rounded-xl cursor-pointer hover:border-orange-500 transition">
                                <input type="radio" name="payment_method" value="midtrans"
                                    class="w-5 h-5 text-orange-600 focus:ring-orange-500 border-gray-300" checked>
                                <span class="ml-3 text-gray-800 font-medium">Midtrans (QRIS / VA / E-Wallet)</span>
                            </label>

                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <button type="submit" id="checkout-btn"
                            class="bg-orange-600 text-white py-2 px-4 rounded hover:bg-orange-700 transition">
                            <span id="btn-text">Konfirmasi Pesanan</span>
                            <span id="btn-loading" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('checkout-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');

            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');

            this.submit();
        });
    </script>
@endsection
