@extends('layouts.main')

@section('content')
    <div class="max-w-3xl mx-auto mt-10">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $article->title }}</h1>
        <div class="text-sm text-gray-500 mb-6">
    Ditulis oleh <span class="font-semibold text-gray-700">{{ $article->user->name ?? 'Penulis Tidak Diketahui' }}</span> •
    {{ $article->created_at->translatedFormat('d F Y') }}
</div>


        <p class="text-lg italic text-gray-600 mb-6">{{ $article->overview }}</p>

        @if ($article->image_url)
            <div class="rounded-lg overflow-hidden mb-8">
                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full max-h-[450px] object-cover object-center">
            </div>
        @endif

        <div class="prose prose-lg max-w-none text-gray-800 mb-12">
        {!! $article->content !!}
        </div>




        <div class="text-center">
            <a href="{{ url('/') }}"
               class="inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800 underline transition">
                ← Kembali ke Halaman Utama
            </a>
        </div>
    </div>
@endsection
