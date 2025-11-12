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
                        <a href="{{ route('orders', ['status' => 'all']) }}"
                            class="tab-link {{ isset($status) && $status == 'all' ? 'border-orange-500 text-orange-600' : 'border-gray-100 text-gray-500 hover:text-gray-700 hover:border-gray-600' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                            data-status="all">Semua</a>
                        <a href="{{ route('orders', ['status' => 'pending']) }}"
                            class="tab-link {{ isset($status) && $status == 'pending' ? 'border-orange-500 text-orange-600' : 'border-gray-100 text-gray-500 hover:text-gray-700 hover:border-gray-600' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                            data-status="pending">Belum Bayar</a>
                        <a href="{{ route('orders', ['status' => 'packing']) }}"
                            class="tab-link {{ isset($status) && $status == 'packing' ? 'border-orange-500 text-orange-600' : 'border-gray-100 text-gray-500 hover:text-gray-700 hover:border-gray-600' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                            data-status="packing">Dikemas</a>
                        <a href="{{ route('orders', ['status' => 'sent']) }}"
                            class="tab-link {{ isset($status) && $status == 'sent' ? 'border-orange-500 text-orange-600' : 'border-gray-100 text-gray-500 hover:text-gray-700 hover:border-gray-600' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                            data-status="sent">Dikirim</a>
                        <a href="{{ route('orders', ['status' => 'done']) }}"
                            class="tab-link {{ isset($status) && $status == 'done' ? 'border-orange-500 text-orange-600' : 'border-gray-100 text-gray-500 hover:text-gray-700 hover:border-gray-600' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                            data-status="done">Selesai</a>
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

                            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition order-item"
                                data-status="{{ $transaction->status }}">
                                <div class="flex justify-between mb-4">
                                    <p class="text-gray-500 text-sm font-medium">Tanggal:
                                        {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                    <span
                                        class="inline-flex px-4 py-1.5 rounded-full text-sm font-medium {{ $statusClass }}">{{ ucfirst($transaction->status) }}</span>
                                </div>

                                @if ($transaction->status == 'sent' && $transaction->tracking_number)
                                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-blue-900">Nomor Resi</p>
                                                <p class="text-sm font-mono text-blue-800">
                                                    {{ $transaction->tracking_number }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="space-y-5">
                                    @foreach ($transaction->items as $item)
                                        <div class="bg-gray-50 p-5 rounded-2xl">
                                            <div class="flex items-center gap-4">
                                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/80' }}"
                                                    class="w-20 h-20 object-cover rounded-xl shadow-sm">
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-semibold text-gray-900 text-lg truncate">
                                                        {{ $item->product->product_name }}</p>
                                                    <p class="text-sm text-gray-500 mt-1">Jumlah: {{ $item->quantity }} ×
                                                        Rp
                                                        {{ number_format($item->price, 0, ',', '.') }}</p>
                                                </div>
                                                <p class="text-gray-800 font-bold text-lg">Rp
                                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                            </div>

                                            @if ($transaction->status === 'done')
                                                <div class="mt-4 pt-4 border-t border-gray-200">
                                                    @php
                                                        $productId = $item->product->id;
                                                        $userRating = \App\Models\Rating::where(
                                                            'customer_id',
                                                            auth()->id(),
                                                        )
                                                            ->where('product_id', $productId)
                                                            ->where('transaction_id', $transaction->id)
                                                            ->first();
                                                    @endphp

                                                    @if ($userRating)
                                                        <div class="text-sm text-gray-700">
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <span class="font-medium text-gray-900">Rating Anda:</span>
                                                                <div class="flex items-center text-yellow-400 gap-1">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        @if ($i <= $userRating->rating)
                                                                            <svg class="w-4 h-4" fill="currentColor"
                                                                                viewBox="0 0 20 20">
                                                                                <path
                                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.947a1 1 0 00.95.69h4.148c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.948c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.286-3.947a1 1 0 00-.364-1.118L2.037 9.374c-.783-.57-.38-1.81.588-1.81h4.148a1 1 0 00.95-.69l1.286-3.947z" />
                                                                            </svg>
                                                                        @else
                                                                            <svg class="w-4 h-4 text-gray-300"
                                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                                <path
                                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.947a1 1 0 00.95.69h4.148c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.948c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.286-3.947a1 1 0 00-.364-1.118L2.037 9.374c-.783-.57-.38-1.81.588-1.81h4.148a1 1 0 00.95-.69l1.286-3.947z" />
                                                                            </svg>
                                                                        @endif
                                                                    @endfor
                                                                </div>
                                                                <span
                                                                    class="text-gray-600 font-semibold">{{ $userRating->rating }}/5</span>
                                                            </div>
                                                            @if ($userRating->comment)
                                                                <p class="text-gray-700 italic">
                                                                    "{{ $userRating->comment }}"</p>
                                                            @endif
                                                            @if ($userRating->image)
                                                                <div class="mt-2">
                                                                    <img src="{{ asset('storage/' . $userRating->image) }}"
                                                                        alt="Rating image"
                                                                        class="w-24 h-24 object-cover rounded-lg">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="bg-transparent rounded-lg p-4">
                                                            <div class="flex justify-end">
                                                                <a href="{{ route('ratings.create', ['product_id' => $productId, 'transaction_id' => $transaction->id]) }}"
                                                                    class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-sm font-medium transition">
                                                                    Kirim Ulasan
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-6 flex justify-between items-center border-t pt-5">
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Total Pembayaran</p>
                                        <p class="text-xl font-bold text-orange-600">Rp
                                            {{ number_format($transaction->total, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('order.detail', $transaction->id) }}"
                                            class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium shadow-md transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                                                    class="bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2.5 rounded-lg font-medium transition shadow-sm">Batal</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($filtered->isEmpty())
                            <div class="empty-state {{ $statusKey }}" data-status="{{ $statusKey }}">
                                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-5.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H3" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pesanan</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        @switch($statusKey)
                                            @case('pending')
                                                Belum ada pesanan yang belum dibayar.
                                            @break

                                            @case('packing')
                                                Belum ada pesanan yang sedang dikemas.
                                            @break

                                            @case('sent')
                                                Belum ada pesanan yang sedang dikirim.
                                            @break

                                            @case('done')
                                                Belum ada pesanan yang telah selesai.
                                            @break
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-link');
            const sections = document.querySelectorAll('.order-section');
            const emptyStates = document.querySelectorAll('.empty-state');

            tabs.forEach(tab => {
                tab.addEventListener('click', e => {
                    e.preventDefault();
                    tabs.forEach(t => t.classList.remove('border-orange-500', 'text-orange-600'));
                    tab.classList.add('border-orange-500', 'text-orange-600');
                    const status = tab.dataset.status;
                    sections.forEach(section => {
                        if (status === 'all') {
                            if (section.dataset.status === 'all') {
                                section.classList.remove('hidden');
                            } else {
                                section.classList.add('hidden');
                            }
                        } else {
                            if (section.dataset.status === status) {
                                section.classList.remove('hidden');
                            } else {
                                section.classList.add('hidden');
                            }
                        }
                    });
                    emptyStates.forEach(emptyState => {
                        if (status === 'all') {
                            emptyState.classList.add('hidden');
                        } else if (emptyState.dataset.status === status) {
                            emptyState.classList.remove('hidden');
                        } else {
                            emptyState.classList.add('hidden');
                        }
                    });
                });
            });

            document.querySelector('.tab-link[data-status="all"]').click();

            document.querySelectorAll('.star-rating').forEach(starContainer => {
                const buttons = starContainer.querySelectorAll('.star-btn');
                const input = starContainer.closest('form').querySelector('.rating-input');

                buttons.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const rating = btn.dataset.value;
                        input.value = rating;
                        starContainer.dataset.rating = rating;

                        buttons.forEach((b, idx) => {
                            if (idx < rating) {
                                b.classList.remove('text-gray-300');
                                b.classList.add('text-yellow-400');
                            } else {
                                b.classList.remove('text-yellow-400');
                                b.classList.add('text-gray-300');
                            }
                        });
                    });

                    // Hover effect
                    btn.addEventListener('mouseenter', () => {
                        const hoverRating = btn.dataset.value;
                        buttons.forEach((b, idx) => {
                            if (idx < hoverRating) {
                                b.classList.add('text-yellow-400');
                                b.classList.remove('text-gray-300');
                            } else {
                                b.classList.remove('text-yellow-400');
                                b.classList.add('text-gray-300');
                            }
                        });
                    });
                });

                starContainer.addEventListener('mouseleave', () => {
                    const currentRating = input.value;
                    buttons.forEach((b, idx) => {
                        if (idx < currentRating) {
                            b.classList.add('text-yellow-400');
                            b.classList.remove('text-gray-300');
                        } else {
                            b.classList.remove('text-yellow-400');
                            b.classList.add('text-gray-300');
                        }
                    });
                });
            });
        });
    </script>
@endsection
