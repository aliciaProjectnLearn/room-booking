<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuruMiddleware
{
    /**
     * Cek apakah user yang mengakses adalah guru.
     * Jika bukan, lempar ke halaman utama.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isGuru()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk guru.');
        }

        return $next($request);
    }
}
