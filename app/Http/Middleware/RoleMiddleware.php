<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // KEMBALIKAN KE BERANDA (front.index) agar tidak terjadi infinite loop redirect
        return redirect()->route('front.index')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut!');
    }
}