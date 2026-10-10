<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    // Authorization di sisi server: dipakai di route sebagai EnsureRole::class.':admin'
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== $role) {
            abort(403, 'Forbidden: Anda tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}
