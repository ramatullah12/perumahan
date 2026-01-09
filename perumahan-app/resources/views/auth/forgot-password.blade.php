<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center space-y-3">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-[1.5rem] mb-2 shadow-inner">
                <i class="fas fa-shield-alt text-2xl text-blue-600"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tighter">Pemulihan Akun</h2>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest leading-relaxed px-4 opacity-70">
                {{ __('Masukkan email Anda untuk menerima tautan pengaturan ulang kata sandi.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 animate-fade-in">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span class="text-xs font-black text-emerald-700 uppercase tracking-tight">{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Alamat Email Terdaftar')" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                        <i class="fas fa-envelope text-sm"></i>
                    </div>
                    <x-text-input id="email" 
                        class="block w-full pl-12 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all shadow-inner placeholder:text-slate-300 placeholder:font-medium" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        placeholder="contoh@email.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px] font-black text-red-500 uppercase tracking-tight" />
            </div>

            <div class="flex flex-col gap-4 pt-2">
                <button type="submit" class="w-full bg-slate-900 text-white p-5 rounded-2xl font-black text-[10px] uppercase tracking-[0.3em] shadow-2xl hover:bg-blue-600 hover:shadow-blue-500/20 transition-all active:scale-95 flex items-center justify-center group">
                    {{ __('Kirim Tautan Pemulihan') }}
                    <i class="fas fa-paper-plane ml-3 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                </button>

                <a href="{{ route('login') }}" class="text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-blue-600 transition-colors py-2 flex items-center justify-center gap-2">
                    <i class="fas fa-chevron-left text-[8px]"></i> Kembali ke Halaman Login
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>