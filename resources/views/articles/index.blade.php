@extends('layouts.app')

@section('title', 'Artikel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Artikel</h1>

    @if($articles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($articles as $article)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-2">
                            <a href="{{ route('articles.show', $article->slug) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $article->title }}
                            </a>
                        </h2>
                        <p class="text-gray-600 text-sm mb-2">
                            {{ \Carbon\Carbon::parse($article->created_at)->format('d M Y') }}
                             </p>
                             <p class="text-gray-500 text-sm mb-4">
                               Ditulis oleh {{ $article->user->name }}
                             </p>
                       
                        <p class="text-gray-700">
                            {{ Str::limit(strip_tags($article->content), 150) }}
                        </p>
                        <a href="{{ route('articles.show', $article->slug) }}" class="text-blue-600 hover:text-blue-800 mt-4 inline-block">
                            Baca Selengkapnya 
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Belum ada artikel yang dipublikasikan.</p>
        </div>
    @endif
</div>
@endsection
