<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WatAPad</title>
    @vite('resources/css/app.css')
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body class="flex flex-col min-h-screen">

<!-- Navbar -->
 @include('partials.navbar')
 
 
 <!-- Content -->
    <main class="flex-grow bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')


    <script>
    document.getElementById('menu-button').addEventListener('click', () => {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
    </script>


<!-- Toastr JS -->
<!-- jQuery harus di-load dulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Baru toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


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


    @yield('scripts') {{-- Tempat untuk script tambahan dari halaman tertentu --}}

    <script src="//unpkg.com/alpinejs" defer></script>

</body>
</html>
