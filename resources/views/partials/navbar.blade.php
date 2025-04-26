<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            {{-- Kiri: Logo --}}
            <div class="flex items-center">
                <a href="/" class="font-bold text-xl text-indigo-600 hover:text-indigo-800">WatAPad</a>
            </div>

            {{-- Kanan: Hi, Category, Article, Logout --}}
            <div class="hidden md:flex items-center space-x-6 ml-auto">
                @if(session()->has('auth_name'))
                    {{-- Hi, {Name} --}}
                    <span class="text-gray-700">Hi, {{ session('auth_name') }}</span>

                    {{-- Dropdown Category --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="text-gray-700 hover:text-indigo-600 focus:outline-none">
                            Category
                        </button>
                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute bg-white shadow-md mt-2 rounded z-10 w-40"
                        >
                            <a href="/categories/create" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">+ Tambah Category</a>
                            <a href="/categories" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">✏️ Edit Category</a>
                        </div>
                    </div>

                    {{-- Dropdown Article --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="text-gray-700 hover:text-indigo-600 focus:outline-none">
                            Article
                        </button>
                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute bg-white shadow-md mt-2 rounded z-10 w-40"
                        >
                            <a href="/articles/create" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">+ Tambah Article</a>
                            <a href="/articles/manage" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">✏️ Edit Article</a>
                        </div>
                    </div>

                    {{-- Logout --}}
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-red-600">Logout</button>
                    </form>
                @else
                    <a href="/login" class="text-gray-700 hover:text-indigo-600">Login</a>
                    <a href="/register" class="text-gray-700 hover:text-indigo-600">Register</a>
                @endif
            </div>

            {{-- Mobile menu button --}}
            <div class="md:hidden flex items-center">
                <button id="menu-button" class="text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden px-4 pb-4 space-y-2">
        @if(session()->has('auth_name'))
            <span class="block py-2 text-gray-700">Hi, {{ session('auth_name') }}</span>

            <div>
                <span class="block text-sm font-semibold text-gray-600">Category</span>
                <a href="/categories/create" class="block px-2 py-1 text-gray-700 hover:bg-gray-100">+ Add Category</a>
                <a href="/categories" class="block px-2 py-1 text-gray-700 hover:bg-gray-100">✏️ Edit Category</a>
            </div>

            <div>
                <span class="block text-sm font-semibold text-gray-600 mt-2">Article</span>
                <a href="/articles/create" class="block px-2 py-1 text-gray-700 hover:bg-gray-100">+ Add Article</a>
                <a href="/articles/manage" class="block px-2 py-1 text-gray-700 hover:bg-gray-100">✏️ Edit Article</a>
            </div>

            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="block py-2 text-red-600">Logout</button>
            </form>
        @else
            <a href="/login" class="block py-2 text-gray-700">Login</a>
            <a href="/register" class="block py-2 text-gray-700">Register</a>
        @endif
    </div>
</nav>
