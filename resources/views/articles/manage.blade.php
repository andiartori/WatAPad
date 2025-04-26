@extends('layouts.main')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">
    <h1 class="text-2xl font-bold mb-6">Manage Articles</h1>

    @if(count($articles) > 0)
        <table class="min-w-full bg-white border border-gray-300 rounded shadow-sm">
            <thead>
                <tr class="bg-gray-300 text-left text-sm uppercase text-gray-600">
                    <th class="py-2 px-4 border-b">Title</th>
                    <th class="py-2 px-4 border-b">Category</th>
                    <th class="py-2 px-4 border-b">Created By</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($articles as $article)
                <tr class="text-gray-900 odd:bg-gray-100 even:bg-white">
                    <td class="py-2 px-4 border-b">{{ $article['title'] }}</td>
                    <td class="py-2 px-4 border-b">{{ $article['category']['name'] ?? 'Unknown' }}</td>
                    <td class="py-2 px-4 border-b">{{ $article['user']['name'] ?? 'Unknown' }}</td>
                    <td class="py-2 px-4 border-b space-x-2">
                        <div class="flex gap-2 justify-center">
                            <a href="/articles/{{ $article['id'] }}/edit" class="text-blue-600 hover:underline">Edit</a>
                            <!-- Delete button -->
                            <form action="/articles/{{ $article['id'] }}/delete" method="POST" class="inline" onsubmit="handleDelete(event, this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-500">Belum ada artikel.</p>
    @endif
</div>

<script>
    function handleDelete(event, form) {
        // Mengubah teks tombol dan disable
        const button = form.querySelector('button');
        button.innerText = 'Tunggu ya...';
        button.disabled = true;

        // Membiarkan form untuk disubmit setelah perubahan
        form.submit();
    }
</script>

@endsection
