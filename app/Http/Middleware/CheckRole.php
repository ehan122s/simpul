<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect('login');
        }

        // Cek apakah role user sesuai dengan role yang diminta di route
        if (auth()->user()->role !== $role) {
            // Jika tidak sesuai, lempar error 403 (Forbidden)
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
