<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Tambahkan ini

class UserController extends Controller
{
    public function index()
    {
        // Mengambil semua user kecuali owner
        $users = User::where('role', '!=', 'owner')->get();
        return view('users.owner.index', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        // Tentukan role baru
        $newRole = ($user->role === 'admin') ? 'customer' : 'admin';
        
        // Update database
        $user->update(['role' => $newRole]);

        // JIKA MENCABUT ADMIN: Paksa logout melalui penghapusan sesi database
        if ($newRole === 'customer') {
            // Cek apakah tabel sessions ada (untuk menghindari error jika driver bukan database)
            if (Schema::hasTable('sessions')) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }
        }

        $message = $newRole === 'admin' 
            ? "Otoritas Diperbarui: {$user->name} sekarang menjabat sebagai Admin." 
            : "Otoritas Dicabut: Akses administrator {$user->name} telah dinonaktifkan.";

        return back()->with('success', $message);
    }
}