@extends('layouts.main')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Registrasi Akun WatAPad</h2>
    <form method="POST" action="{{ route('register.submit') }}" class="bg-white p-6 rounded shadow relative">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700">Nama</label>
            <input type="text" name="name" class="mt-1 block w-full border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" class="mt-1 block w-full border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input type="password" name="password" class="mt-1 block w-full border rounded" required>
        </div>
        
        <div class="flex justify-between items-center">
            <button id="registerBtn" type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 flex items-center gap-2">
                <span id="btnText">Daftar</span>
                <svg id="btnSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </button>
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
    Ke Halaman Login
    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 5l7 7-7 7"></path>
    </svg>
</a>

        </div>
    </form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const btn = document.getElementById('registerBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');

        form.addEventListener('submit', function () {
            btn.disabled = true;
            btnText.textContent = 'Registering...';
            btnSpinner.classList.remove('hidden');
        });
    });
</script>
@endsection
