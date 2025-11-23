@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

    <section class="pt-8 pb-14 bg-white mx-6">


        <div class="relative overflow-hidden rounded-3xl shadow-xl w-full ">
            <div class="slider flex transition-transform duration-700 ease-in-out" id="slider">

                <div class="slide relative flex-shrink-0 w-full">
                    <img src="{{ asset('images/diskonhelm.png') }}"
                        class="w-full h-64 md:h-80 lg:h-96 object-cover rounded-2xl">

                    <div
                        class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center text-center text-white px-4">
                        <h3 class="text-4xl md:text-5xl font-extrabold mb-3 drop-shadow-lg">
                            Diskon 20%
                        </h3>
                        <p class="text-lg md:text-xl font-semibold opacity-90">
                            Koleksi helm terbaru minggu ini — Stok terbatas!
                        </p>
                    </div>
                </div>

                <div class="slide relative flex-shrink-0 w-full">
                    <img src="{{ asset('images/koleksihelm.jpeg') }}"
                        class="w-full h-64 md:h-80 lg:h-96 object-cover rounded-2xl">

                    <div
                        class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center text-center text-white px-4">
                        <h3 class="text-4xl md:text-5xl font-extrabold mb-3 drop-shadow-lg">
                            Koleksi Terbaru
                        </h3>
                        <p class="text-lg md:text-xl font-semibold opacity-90">
                            Helm keren, desain modern, dan kualitas top!
                        </p>
                    </div>
                </div>

                <div class="slide relative flex-shrink-0 w-full">
                    <img src="{{ asset('images/ongkir.png') }}"
                        class="w-full h-64 md:h-80 lg:h-96 object-cover rounded-2xl">

                    <div
                        class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center text-center text-white px-4">
                        <h3 class="text-4xl md:text-5xl font-extrabold mb-3 drop-shadow-lg">
                            Gratis Ongkir
                        </h3>
                        <p class="text-lg md:text-xl font-semibold opacity-90">
                            Berlaku untuk pembelian minimal Rp 700.000!
                        </p>
                    </div>
                </div>

            </div>
            <button id="btn-prev" onclick="prevSlide()"
                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white rounded-full p-3 shadow-md transition opacity-0 pointer-events-none">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button id="btn-next" onclick="nextSlide()"
                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white rounded-full p-3 shadow-md transition opacity-0 pointer-events-none">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>



            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                <button class="indicator w-3 h-3 rounded-full bg-white/50" onclick="goToSlide(0)"></button>
                <button class="indicator w-3 h-3 rounded-full bg-white/50" onclick="goToSlide(1)"></button>
                <button class="indicator w-3 h-3 rounded-full bg-white/50" onclick="goToSlide(2)"></button>
            </div>

        </div>


    </section>

    <section class="py-16 bg-white border-t border-b border-gray-200 mx-6">
        <div class="max-w-full mx-auto px-5 md:px-8 lg:px-16">

            <h2 class="text-3xl md:text-4xl font-extrabold mb-14 text-center tracking-tight">
                Produk Unggulan
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-14">
                @forelse($products as $product)
                    <div
                        class="group bg-white border border-gray-200 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1">

                        <a href="{{ route('product.show', $product->id) }}" class="relative block">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}"
                                class="w-full h-52 md:h-60 object-cover rounded-t-3xl group-hover:scale-[1.07] transition duration-500 ease-out">
                        </a>

                        <div class="p-5 flex flex-col flex-1 text-left">
                            <h4 class="text-lg md:text-xl font-bold text-gray-900 mb-1 line-clamp-1">
                                {{ $product->product_name }}
                            </h4>

                            @if ($product->ratingCount > 0)
                                <div class="flex items-center justify gap-1 mb-2 ml-1">
                                    @php $avg = round($product->avgRating); @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $avg ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.947a1 1 0 00.95.69h4.148c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.948c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.286-3.947a1 1 0 00-.364-1.118L2.037 9.374c-.783-.57-.38-1.81.588-1.81h4.148a1 1 0 00.95-.69l1.286-3.947z" />
                                        </svg>
                                    @endfor
                                    <span class="text-xs text-gray-500">({{ $product->ratingCount }})</span>
                                </div>
                            @endif

                            @if ($product->description)
                                <p class="text-sm text-gray-500 line-clamp-2 mb-3 ml-1 ">
                                    {{ $product->description }}
                                </p>
                            @endif

                            <p class="text-xl font-extrabold text-gray-900 mb-5 tracking-tight ">
                                Rp {{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}
                            </p>

                            <form action="{{ route('addToCart') }}" method="POST" class="mt-auto">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">

                                <button
                                    class="w-full bg-gray-900 text-white py-2.5 rounded-xl font-semibold transition-all duration-300 hover:bg-black hover:shadow-md active:scale-95">
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center col-span-full">Belum ada produk tersedia.</p>
                @endforelse
            </div>

        </div>
    </section>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const slider = document.getElementById('slider');
        const indicators = document.querySelectorAll('.indicator');

        const btnPrev = document.getElementById('btn-prev');
        const btnNext = document.getElementById('btn-next');

        let autoSlideInterval;
        let userInteracted = false;
        let hideButtonsTimeout;


        function showSlide(i) {
            currentSlide = (i + slides.length) % slides.length;
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;

            indicators.forEach((dot, index) => {
                dot.classList.toggle('bg-white', index === currentSlide);
                dot.classList.toggle('bg-white/50', index !== currentSlide);
            });
        }


        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function prevSlide() {
            showSlide(currentSlide - 1);
        }

        function goToSlide(i) {
            showSlide(i);
        }


        function startAutoSlide() {
            autoSlideInterval = setInterval(() => {
                if (!userInteracted) nextSlide();
            }, 3000);
        }


        function showButtons() {
            userInteracted = true;

            btnPrev.classList.remove("opacity-0", "pointer-events-none");
            btnNext.classList.remove("opacity-0", "pointer-events-none");

            clearTimeout(hideButtonsTimeout);

            hideButtonsTimeout = setTimeout(() => {
                userInteracted = false;

                btnPrev.classList.add("opacity-0", "pointer-events-none");
                btnNext.classList.add("opacity-0", "pointer-events-none");

            }, 2000);
        }


        btnNext.addEventListener("click", () => {
            showButtons();
            nextSlide();
        });
        btnPrev.addEventListener("click", () => {
            showButtons();
            prevSlide();
        });
        slider.addEventListener("click", () => showButtons());


        showSlide(0);
        startAutoSlide();
    </script>



@endsection
