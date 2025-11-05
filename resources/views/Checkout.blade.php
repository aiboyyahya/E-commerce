@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<section class="py-16 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto border border-gray-200 rounded-3xl bg-white shadow-md p-10 grid grid-cols-1 md:grid-cols-2 gap-12">
        
        <div class="md:col-span-2 text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
            <p class="text-gray-600 mt-2">Pastikan data dan pesanan Anda sudah benar sebelum melakukan pembayaran.</p>
        </div>

        <form id="checkout-form" action="{{ route('checkout') }}" method="POST" class="space-y-8">
            @csrf
            <div class="border border-gray-200 rounded-3xl p-8 shadow-sm bg-white">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Metode Pembayaran</h2>

                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman</label>
                    <textarea name="address" id="address" rows="3" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-orange-600 focus:border-orange-600">{{ old('address', Auth::user()->address ?? '') }}</textarea>
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-orange-600 focus:border-orange-600"
                        placeholder="Tambahkan catatan untuk penjual jika ada..."></textarea>
                </div>

                <div class="border border-gray-300 rounded-xl p-5 bg-gray-50">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="payment_method" value="midtrans" checked
                            class="form-radio text-orange-600" />
                        <span class="ml-3 font-medium text-gray-900">
                            Midtrans (QRIS / Virtual Account / E-Wallet)
                        </span>
                    </label>
                </div>
            </div>

            <button type="submit"
                class="mt-6 w-full bg-black text-white font-semibold py-3 rounded-lg hover:bg-gray-800 transition">
                KONFIRMASI PESANAN
            </button>
        </form>

        <div class="flex flex-col max-w-lg border border-gray-200 rounded-3xl p-8 shadow-sm bg-white">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Detail Produk</h2>

            <ul class="space-y-6">
                @php $total = 0; @endphp
                @php
                    $items = isset($directCheckout) && !empty($directCheckout) ? $directCheckout : $cart;
                @endphp
                @foreach ($items as $id => $item)
                    @php
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    @endphp
                    <li class="flex gap-4 items-center border-b border-gray-100 pb-4">
                        <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/100' }}"
                            alt="{{ $item['name'] }}" class="w-24 h-20 object-cover rounded-lg" />
                        <div>
                            <p class="text-lg font-semibold text-gray-900">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-600">Kategori: Helm</p>
                            <p class="text-sm text-gray-600">Jumlah: {{ $item['quantity'] }}</p>
                        </div>
                        <div class="ml-auto font-semibold text-gray-900">
                            Rp{{ number_format($subtotal, 0, ',', '.') }}
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-8 border-t pt-6 text-gray-900">
                <h3 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h3>
                <div class="flex justify-between mb-2">
                    <span>Subtotal</span>
                    <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between mb-6">
                    <span>Ongkir</span>
                    <span>Rp0</span>
                </div>
                <div class="flex justify-between font-bold text-lg border-t pt-4">
                    <span>Total</span>
                    <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Memproses...';
        this.submit();
    });
</script>
@endsection
