@extends('layouts.main')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-4">Edit Artikel</h1>

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form id="articleForm" action="/articles/{{ $article->id }}/update" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Judul</label>
            <input type="text" name="title" value="{{ $article->title }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Overview</label>
            <textarea name="overview" class="w-full border rounded px-3 py-2" required>{{ $article->overview }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Konten</label>
            <textarea id="content" name="content" class="w-full border rounded px-3 py-2">{{ $article->content }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Kategori</label>
            <select name="category_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Pilih kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $article->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Gambar</label>
            <input type="file" name="image" class="w-full">
            <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah gambar.</p>
        </div>

        <div class="flex gap-2">
            <button id="submitButton" type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update
            </button>
            <a href="/articles/manage" class="px-4 py-2 rounded border hover:bg-gray-100">Batalkan</a>
        </div>
    </form>

    {{-- CKEditor Script --}}
    <script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@41.3.1/build/ckeditor.js"></script>
    <script>
    let editorInstance;

    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: ['bold', 'italic', 'bulletedList', 'undo', 'redo']
        })
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });

    document.getElementById('articleForm').addEventListener('submit', function(e) {
        const editorData = editorInstance.getData();

        if (editorData.trim() === '') {
            e.preventDefault();
            alert('Konten wajib diisi!');
            return;
        }

        const submitButton = document.getElementById('submitButton');
        submitButton.innerText = 'Tunggu ya...';
        submitButton.disabled = true;
    });
    </script>

</div>
@endsection
