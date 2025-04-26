@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6">Edit Category</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-4 mb-4 rounded">
            @foreach ($errors->all() as $error)
                <div>- {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form id="editCategoryForm" action="/categories/{{ $category['id'] }}/update" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1" for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category['name']) }}"
                class="w-full border border-gray-300 p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1" for="description">Description</label>
            <textarea id="description" name="description"
                class="w-full border border-gray-300 p-2 rounded">{{ old('description', $category['description']) }}</textarea>
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('categories.edit.page') }}"
               class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Batalkan</a>

            <button id="updateBtn" type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
                <span id="btnText">Update</span>
                <span id="loadingText" class="hidden italic text-sm">Mohon tunggu...</span>
            </button>
        </div>
    </form>

    {{-- Script langsung di sini biar pasti ke-load --}}
    <script>
        const form = document.getElementById('editCategoryForm');
        const updateBtn = document.getElementById('updateBtn');
        const btnText = document.getElementById('btnText');
        const loadingText = document.getElementById('loadingText');

        form.addEventListener('submit', function(e) {
            btnText.textContent = 'Updating...';
            loadingText.classList.remove('hidden');
            updateBtn.disabled = true;
            updateBtn.classList.add('opacity-50', 'cursor-not-allowed');

            // delay biar kamu lihat perubahannya
            e.preventDefault();
            setTimeout(() => form.submit(), 1000);
        });
    </script>
</div>
@endsection
