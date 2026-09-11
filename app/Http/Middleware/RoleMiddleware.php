<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response // ...$roles = ['admin', 'tamu'] yaitu fungsi ini untuk memfilter satu role saja atau beberapa role sekaligus 
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Jika role user saat ini ada di dalam daftar role yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak punya akses, kembalikan ke dashboard dengan pesan error
        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut!');

        // ringkasan : Auth::check() bertugas memastikan apakah penggunanya sudah login atau belum (Autentikasi). Sedangkan in_array() bertugas memastikan apakah pengguna yang sudah login tersebut punya wewenang/role yang sesuai
    }
}