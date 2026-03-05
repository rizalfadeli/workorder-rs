<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Cek apakah user memiliki role yang sesuai
     * Usage: ->middleware('role:admin') atau ->middleware('role:user')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role?->name, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            abort(403, 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}