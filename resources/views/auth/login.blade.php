@extends('layouts.main')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Login Akun WatAPad</h2>
    <form method="POST" action="{{ route('login.submit') }}" class="bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" class="mt-1 block w-full border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input type="password" name="password" class="mt-1 block w-full border rounded" required>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 w-full">
            Login
        </button>
    </form>

    <div class="mt-4 text-center">
        <p class="text-sm text-gray-600">Sudah Punya Akun?</p>
        <a href="{{ route('register') }}" class="mt-2 inline-flex items-center px-4 py-2 border border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded transition">
            Ke Halaman Registrasi
            <svg class="h-4 w-4 ml-2" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if($errors->any())
            toastr.error("{{ $errors->first() }}");
        @endif
    });
</script>
@endsection
