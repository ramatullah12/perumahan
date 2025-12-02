<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.jpg') }}" class="block h-8 w-auto">

                <div class="flex flex-col leading-tight">
                    <span class="font-semibold text-blue-600">Selamat Datang</span>
                    <span class="text-xs text-gray-600 dark:text-gray-300">PT Kedaton Sejahtera Abadi</span>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-6">
                <a href="#"
                    class="flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-600 text-sm rounded-md hover:bg-blue-200 transition">
                    💬 Chat
                </a>
                <div class="text-right">
                    <div class="font-medium text-gray-800 dark:text-gray-200">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ Auth::user()->email }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        class="flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
