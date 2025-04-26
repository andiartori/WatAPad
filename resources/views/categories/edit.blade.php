@extends('layouts.main')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">
    <h1 class="text-2xl font-bold mb-6">Kelola Kategori</h1>
    <span class="text-lg  mb-6 ">Note : Mohon diperhatikan kepemilikan karena hanya owner kategori yang bisa menghapus kategori</span>


    @if(count($categories) > 0)
        <table class="min-w-full bg-white border border-gray-300 rounded shadow-sm mt-4">
            <thead>
                <tr class="bg-gray-300 text-left text-sm uppercase text-gray-600">
                    <th class="py-2 px-4 border-b">Nama</th>
                    <th class="py-2 px-4 border-b">Deskripsi</th>
                    <th class="py-2 px-4 border-b">Owner</th>
                    <th class="py-2 px-4 border-b">Kelola</th>
                </tr>
            </thead>
            <tbody>
    @foreach($categories as $category)
        <tr class="text-gray-900 odd:bg-gray-100 even:bg-white">
            <td class="py-2 px-4 border-b">{{ $category['name'] }}</td>
            <td class="py-2 px-4 border-b">{{ $category['description'] }}</td>
            <td class="py-2 px-4 border-b">{{ $category['user']['name'] ?? 'Unknown' }}</td>
            <td class="py-2 px-4 border-b space-x-2">
                <div class="flex gap-2 justify-center">
                <a href="/categories/{{ $category['id'] }}/edit" class="text-blue-600 hover:underline">Edit</a>
                <form action="/categories/{{ $category['id'] }}/delete" method="POST" class="inline" onsubmit="this.querySelector('button').innerText='Tunggu ya!'; this.querySelector('button').disabled=true;">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                </form>


                </div>

            </td>
        </tr>
    @endforeach
</tbody>

        </table>
<div class="flex justify-center items-center mt-8" >
<a href="/" class="text-gray-600 hover:text-indigo-600">← Kembali Ke Home</a>

</div>
    @else
        <p class="text-gray-500">Belum ada kategori.</p>
    @endif
</div>
@endsection
