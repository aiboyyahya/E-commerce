@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="bg-gray-50 min-h-screen py-12 px-4">
    <article class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-lg border border-gray-100">

        @if($article->category ?? false)
        <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full mb-4">
            {{ $article->category->name }}
        </span>
        @endif

        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-5">
            {{ $article->title }}
        </h1>

        <div class="flex items-start md:items-center gap-4 mb-8 flex-col md:flex-row">

            <div class="flex-shrink-0">
                @if($article->user && $article->user->avatar)
                    <img src="{{ asset('storage/' . $article->user->avatar) }}" 
                        class="w-12 h-12 rounded-full object-cover shadow">
                @else
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-lg">
                        {{ strtoupper(substr($article->user->name ?? 'U', 0, 1)) }}
                    </div>
                @endif
            </div>

            <div>
                <p class="text-gray-700 font-medium">
                    Ditulis oleh <span class="font-semibold">{{ $article->user->name ?? 'Unknown' }}</span>
                </p>
                <p class="text-gray-500 text-sm">
                    Dipublikasikan {{ \Carbon\Carbon::parse($article->created_at)->translatedFormat('d F Y') }}
                </p>
            </div>
        </div>

        @if ($article->image)
            <div class="mb-10">
                <img src="{{ asset('storage/' . $article->image) }}" 
                     alt="{{ $article->title }}"
                     class="w-full h-72 md:h-96 rounded-2xl object-cover shadow-md hover:scale-[1.01] transition-all duration-300">
            </div>
        @endif

        <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed mb-10">
            {!! $article->content !!}
        </div>

        <div class="border-t border-gray-200 pt-8 mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <span class="text-gray-600 font-medium">Bagikan artikel ini:</span>
                    <div class="flex gap-2 flex-wrap">

                        <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . route('articles.show', $article->slug)) }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                            WhatsApp
                        </a>

                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article->slug)) }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Facebook
                        </a>

                        <button onclick="copyToClipboard('{{ route('articles.show', $article->slug) }}')"
                                class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Salin Link
                        </button>
                    </div>
                </div>

                <a href="{{ route('articles.index') }}"
                    class="inline-block px-6 py-3 bg-gray-800 text-white rounded-xl font-medium hover:bg-gray-900 transition">
                    Kembali ke Daftar Artikel
                </a>
            </div>
        </div>
    </article>
</div>
@endsection

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Tersalin!';
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        button.classList.remove('bg-gray-600', 'hover:bg-gray-700');

        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-gray-600', 'hover:bg-gray-700');
        }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy: ', err);
    });
}
</script>
