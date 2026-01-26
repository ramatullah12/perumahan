<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Ambil data user terbaru langsung dari Database
        // Ini kunci agar perubahan role oleh Owner terdeteksi seketika.
        $user = User::find(Auth::id());

        // 3. Cek apakah role user tidak cocok
        if (!$user || $user->role !== $role) {
            
            // PAKSA LOGOUT: Jika role tidak sesuai, bersihkan sesi browser mereka
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Berikan pesan error atau arahkan ke login dengan pesan peringatan
            abort(403, 'Akses Ditolak: Otoritas Anda telah dicabut. Silakan hubungi Owner.');
        }

        return $next($request);
    }
}