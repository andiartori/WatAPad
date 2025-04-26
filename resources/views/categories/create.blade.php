@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Tambahkan Kategori Baru</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/categories">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nama Kategori</label>
            <input type="text" name="name" id="name" class="mt-1 w-full border-black border rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700">Deskripsi Kategori</label>
            <textarea name="description" id="description" class="mt-1 w-full border-black border rounded-md shadow-sm"></textarea>
        </div>

        <div class="flex justify-between items-center">
            <a href="/" class="text-gray-600 hover:text-indigo-600">← Kembali Ke Home</a>

            <button
                type="submit"
                onclick="this.innerText='Submitting...'; this.disabled=true; this.form.submit();"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
            >
                Buat Kategori
            </button>
        </div>
    </form>
</div>
@endsection
