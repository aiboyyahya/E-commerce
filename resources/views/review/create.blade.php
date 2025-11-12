@extends('layouts.app')

@section('title', 'Kirim Ulasan')

@section('content')
    <section class="py-20 bg-white min-h-screen">
        <div class="max-w-3xl mx-auto px-6 lg:px-12">
            <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Kirim Ulasan</h1>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                @if ($product)
                    <div class="flex items-center gap-4 mb-4">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/80' }}"
                            class="w-20 h-20 object-cover rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $product->product_name }}</p>
                            <p class="text-sm text-gray-500">Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('ratings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product ? $product->id : old('product_id') }}">
                    <input type="hidden" name="transaction_id" value="{{ $transaction_id ?? old('transaction_id') }}">

                    <div class="flex items-center gap-3">
                        <label class="text-sm font-medium text-gray-700">Beri rating:</label>
                        <div class="flex gap-1 star-rating" data-rating="0">
                            @for ($s = 1; $s <= 5; $s++)
                                <button type="button" class="star-btn text-gray-300 hover:text-yellow-400 transition"
                                    data-value="{{ $s }}">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.947a1 1 0 00.95.69h4.148c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.948c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.286-3.947a1 1 0 00-.364-1.118L2.037 9.374c-.783-.57-.38-1.81.588-1.81h4.148a1 1 0 00.95-.69l1.286-3.947z" />
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" class="rating-input" value="0">
                    </div>

                    <div>
                        <textarea name="comment" rows="4"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="Tulis ulasan Anda..."></textarea>
                    </div>
                    <div class="flex gap-2 justify-between items-center">
                        <a href="{{ route('orders', ['status' => 'done']) }}"
                            class="text-sm text-gray-600 hover:underline">Kembali ke Pesanan</a>
                        <button type="submit"
                            class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-sm font-medium transition">Kirim
                            Ulasan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
      
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.star-rating').forEach(starContainer => {
                const buttons = starContainer.querySelectorAll('.star-btn');
                const input = starContainer.closest('form').querySelector('.rating-input');

                buttons.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const rating = btn.dataset.value;
                        input.value = rating;
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
