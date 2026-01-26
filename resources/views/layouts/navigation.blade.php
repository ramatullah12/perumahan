<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl border-b border-slate-200/60 dark:border-slate-800/60 transition-all duration-300 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            
            <div class="flex items-center gap-10">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="relative inline-flex">
                        <img src="{{ asset('images/logo.jpg') }}" 
                             class="h-12 w-12 rounded-2xl shadow-lg border-2 border-white dark:border-slate-700 transition-all duration-500 group-hover:rotate-6 group-hover:scale-110" 
                             alt="Logo Kedaton">
                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-blue-500 border-2 border-white dark:border-slate-900"></span>
                        </span>
                    </div>

                    <div class="flex flex-col border-l-2 border-slate-100 dark:border-slate-800 pl-3">
                        <span class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-tight leading-none mb-1">
                            Kedaton <span class="text-blue-600">Group</span>
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                            Property Developer
                        </span>
                    </div>
                </a>

                <div class="hidden lg:flex items-center gap-1">
                    @php
                        $role = Auth::user()->role;
                        $menus = [
                            'owner' => [['route' => 'owner.dashboard', 'label' => 'Overview', 'icon' => 'grid']],
                            'admin' => [['route' => 'admin.dashboard', 'label' => 'Workspace', 'icon' => 'cpu']],
                            'customer' => [['route' => 'customer.dashboard', 'label' => 'Home', 'icon' => 'home']]
                        ];
                    @endphp

                    @foreach($menus[$role] ?? [] as $menu)
                        <a href="{{ route($menu['route']) }}" class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold {{ request()->routeIs($menu['route']) ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors' }}">
                            {{ $menu['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-6">
                <button class="relative p-2 text-slate-400 hover:text-blue-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-2 right-2 flex h-2 w-2 rounded-full bg-red-500 border-2 border-white dark:border-slate-900"></span>
                </button>
                
                <div class="flex items-center gap-4 pl-6 border-l border-slate-200 dark:border-slate-800">
                    <div class="flex flex-col text-right">
                        <p class="text-sm font-black text-slate-800 dark:text-white leading-none">
                            {{ Auth::user()->name }}
                        </p>
                        <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-2 py-0.5 rounded mt-1 self-end uppercase">
                            {{ $role }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex h-11 w-11 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl items-center justify-center hover:bg-red-600 dark:hover:bg-red-500 hover:text-white transition-all duration-300 shadow-lg shadow-slate-200 dark:shadow-none group">
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="flex lg:hidden">
                <button @click="open = ! open" class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-600">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="lg:hidden bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
        <div class="px-4 py-6 space-y-4">
            <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl">
                <div class="h-12 w-12 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-black text-slate-800 dark:text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-4 bg-red-50 text-red-600 font-bold rounded-2xl">Sign Out</button>
            </form>
        </div>
    </div>
</nav>