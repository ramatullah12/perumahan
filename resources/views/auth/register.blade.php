<x-guest-layout>
    <div class="py-6">
        <div class="text-center mb-8">
            <a href="/" class="inline-block transition-transform hover:scale-105">
                <img src="{{ asset('images/logo.jpg') }}" 
                     class="w-24 h-24 rounded-full shadow-lg border-4 border-white mx-auto mb-4" 
                     alt="Logo Kedaton">
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">KEDATON GROUP</h2>
                <p class="text-slate-500 text-sm font-medium mt-1">PT Kedaton Sejahtera Abadi</p>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-2xl shadow-slate-200/60 border border-slate-100">
            <div class="mb-8">
                <h3 class="text-xl font-bold text-slate-800">Daftar Akun Baru</h3>
                <p class="text-slate-500 text-sm">Wujudkan hunian impian Anda bersama kami hari ini.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="relative">
                    <label for="name" class="block font-bold text-xs uppercase tracking-widest text-slate-500 mb-2 ms-1">Nama Lengkap</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-user text-sm"></i>
                        </span>
                        <input id="name" 
                               class="block w-full pl-10 pr-4 py-3 bg-slate-50 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl transition-all duration-200 placeholder:text-slate-400" 
                               type="text" 
                               name="name" 
                               :value="old('name')" 
                               required 
                               autofocus 
                               placeholder="Nama lengkap Anda"
                               autocomplete="name" />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div class="mt-4 relative">
                    <label for="email" class="block font-bold text-xs uppercase tracking-widest text-slate-500 mb-2 ms-1">Alamat Email</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-envelope text-sm"></i>
                        </span>
                        <input id="email" 
                               class="block w-full pl-10 pr-4 py-3 bg-slate-50 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl transition-all duration-200 placeholder:text-slate-400" 
                               type="email" 
                               name="email" 
                               :value="old('email')" 
                               required 
                               placeholder="nama@email.com"
                               autocomplete="username" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div class="mt-4">
                    <label for="password" class="block font-bold text-xs uppercase tracking-widest text-slate-500 mb-2 ms-1">Kata Sandi</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input id="password" 
                               class="block w-full pl-10 pr-4 py-3 bg-slate-50 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl transition-all duration-200 placeholder:text-slate-400"
                               type="password"
                               name="password"
                               required 
                               placeholder="Min. 8 karakter"
                               autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="mt-4">
                    <label for="password_confirmation" class="block font-bold text-xs uppercase tracking-widest text-slate-500 mb-2 ms-1">Konfirmasi Kata Sandi</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-check-double text-sm"></i>
                        </span>
                        <input id="password_confirmation" 
                               class="block w-full pl-10 pr-4 py-3 bg-slate-50 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl transition-all duration-200 placeholder:text-slate-400"
                               type="password"
                               name="password_confirmation" 
                               required 
                               placeholder="Ulangi kata sandi"
                               autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 transition-all duration-200 flex items-center justify-center gap-2 group">
                        <span>DAFTAR SEKARANG</span>
                        <i class="fas fa-user-plus text-sm group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-500">
                        Sudah memiliki akun? 
                        <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk di sini</a>
                    </p>
                </div>
            </form>
        </div>

        <p class="mt-8 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">
            &copy; {{ date('Y') }} Kedaton Sejahtera Abadi
        </p>
    </div>
</x-guest-layout>