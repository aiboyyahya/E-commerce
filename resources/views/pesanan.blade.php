@extends('layouts.app')

@section('title', 'Pesanan Saya')

@if (session('success'))
<div class="fixed top-6 right-6 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-bounce">
    {{ session('success') }}
</div>
@endif

@section('content')
<section class="py-20 bg-white min-h-screen">
    <div class="max-w-6xl mx-auto px-6 lg:px-12">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-10 flex items-center gap-3">Pesanan Saya</h1>

        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="#all" class="tab-link border-orange-500 text-orange-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-status="all">Semua</a>
                    <a href="#pending" class="tab-link border-gray-100 text-gray-500 hover:text-gray-700 hover:border-gray-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-status="pending">Belum Bayar</a>
                    <a href="#packing" class="tab-link border-gray-100 text-gray-500 hover:text-gray-700 hover:border-gray-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-status="packing">Dikemas</a>
                    <a href="#sent" class="tab-link border-gray-100 text-gray-500 hover:text-gray-700 hover:border-gray-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-status="sent">Dikirim</a>
                    <a href="#done" class="tab-link border-gray-100 text-gray-500 hover:text-gray-700 hover:border-gray-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-status="done">Selesai</a>
                </nav>
            </div>
        </div>

        <div id="orders-container" class="space-y-8">
            @foreach (['all', 'pending', 'packing', 'sent', 'done'] as $statusKey)
                @php
                    $filtered = $statusKey === 'all' ? $transactions : $transactions->where('status', $statusKey);
                @endphp

                <div class="order-section {{ $statusKey }}" data-status="{{ $statusKey }}">
                    @foreach ($filtered as $transaction)
                        @php
                            $statusClass = match ($transaction->status) {
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'packing' => 'bg-blue-100 text-blue-800',
                                'sent' => 'bg-purple-100 text-purple-800',
                                'done' => 'bg-green-100 text-green-800',
                                default => 'bg-red-100 text-red-800',
                            };
                        @endphp

                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition order-item" data-status="{{ $transaction->status }}">
                            <div class="flex justify-between mb-4">
                                <p class="text-gray-500 text-sm font-medium">Tanggal: {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                <span class="inline-flex px-4 py-1.5 rounded-full text-sm font-medium {{ $statusClass }}">{{ ucfirst($transaction->status) }}</span>
                            </div>

                            <div class="space-y-5">
                                @foreach ($transaction->items as $item)
                                    <div class="bg-gray-50 p-5 rounded-2xl flex items-center gap-4">
                                        <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/80' }}" class="w-20 h-20 object-cover rounded-xl shadow-sm">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 text-lg truncate">{{ $item->product->product_name }}</p>
                                            <p class="text-sm text-gray-500 mt-1">Jumlah: {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <p class="text-gray-800 font-bold text-lg">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 flex justify-between items-center border-t pt-5">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Total Pembayaran</p>
                                    <p class="text-xl font-bold text-orange-600">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('order.detail', $transaction->id) }}" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium shadow-md transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>Detail</span>
                                    </a>
                                    @if ($transaction->status == 'pending')
                                        <form method="POST" action="{{ route('order.delete', $transaction->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2.5 rounded-lg font-medium transition shadow-sm">Batal</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-link');
    const sections = document.querySelectorAll('.order-section');

    tabs.forEach(tab => {
        tab.addEventListener('click', e => {
            e.preventDefault();
            tabs.forEach(t => t.classList.remove('border-orange-500', 'text-orange-600'));
            tab.classList.add('border-orange-500', 'text-orange-600');
            const status = tab.dataset.status;
            sections.forEach(section => {
                if (section.dataset.status === status || status === 'all') {
                    section.classList.remove('hidden');
                } else {
                    section.classList.add('hidden');
                }
            });
        });
    });

    document.querySelector('.tab-link[data-status="all"]').click();
});
</script>
@endsection
