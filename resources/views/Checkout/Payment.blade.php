@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
    <section class="py-16 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-6 lg:px-16">
            <div class="bg-white rounded-2xl shadow p-8 text-center">
                <div id="payment-loading" class="mb-6">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600 mx-auto mb-4"></div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Memproses Pembayaran...</h2>
                    <p class="text-gray-600">Mohon tunggu sebentar, sedang mempersiapkan pembayaran.</p>
                </div>

                <div id="payment-ready" class="hidden">
                    <div class="text-green-600 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Pembayaran Siap</h2>
                    <p class="text-gray-600 mb-4">Modal pembayaran akan muncul dalam beberapa detik.</p>
                    <button id="manual-pay-btn"
                        class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition hidden">
                        Buka Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </section>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingDiv = document.getElementById('payment-loading');
            const readyDiv = document.getElementById('payment-ready');
            const manualBtn = document.getElementById('manual-pay-btn');

            // Show ready state after 2 seconds
            setTimeout(function() {
                loadingDiv.classList.add('hidden');
                readyDiv.classList.remove('hidden');
            }, 2000);

            // Auto trigger payment after 3 seconds
            setTimeout(function() {
                triggerPayment();
            }, 3000);

            // Manual trigger button
            manualBtn.addEventListener('click', function() {
                triggerPayment();
            });

            function triggerPayment() {
                snap.pay('{{ $transaction->snap_token }}', {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        window.location.href = '{{ route('checkout.success', $transaction->id) }}';
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        window.location.href = '{{ route('checkout.success', $transaction->id) }}';
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        alert('Pembayaran gagal. Silakan coba lagi.');
                        window.location.href = '{{ route('checkout.page') }}';
                    },
                    onClose: function() {
                        console.log('Payment modal closed');
                        // Optional: redirect or show message
                    }
                });
            }
        });
    </script>
@endsection
