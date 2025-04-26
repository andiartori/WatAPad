@extends('layouts.main')

@section('content')
<div class="flex flex-col items-center justify-center">
<h1 class="text-3xl font-bold mb-6">Artikel Lengkap</h1>
<label for="category" class="block mb-2 text-m font-medium text-gray-700">Register dan Login untuk buat Kategori dan Artikel</label>
</div>



    {{-- Filter kategori --}}
    <form method="GET" action="{{ url('/articles') }}" class="mb-6">
        <label for="category" class="block mb-2 text-sm font-medium text-gray-700 mt-4">Filter Berdasarkan Kategori:</label>
        <select name="category" id="category" class="w-full md:w-1/2 p-2 border border-gray-300 rounded">
            <option value="">Semua Kategory</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="mt-2 inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Filter
        </button>
    </form>

    {{-- Artikel --}}
    <div id="articles" class="grid gap-4">
        @if ($articles->isEmpty())
            <p class="text-gray-400 italic">Tidak ada artikel ditemukan.</p>
        @else
            @foreach ($articles as $article)
                <div class="bg-white p-4 rounded shadow">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-xl font-semibold">{{ $article->title }}</h2>
                        <span class="bg-indigo-100 text-indigo-600 text-xs font-semibold px-2 py-1 rounded-full">
                            {{ $article->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>
                    <p class="text-gray-700">{{ $article->overview }}</p>

                    <p class="text-sm text-gray-500 mt-4">Tanggal Publikasi {{ $article->created_at->format('d M Y') }}</p>
                    <div class="flex flex-col justify-end items-end ">
                    <a href="{{ url('/articles/' . $article->id) }}" class="text-indigo-600 bg-blue-200 p-2 rounded-md hover:underline text-sm font-medium mt-3 inline-block">
                        Baca Artikel →
                    </a>
                    </div>

                </div>
            @endforeach
        @endif
    </div>
@endsection
