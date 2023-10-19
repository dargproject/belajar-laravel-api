<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->is_admin == 0) {
            return response()->json([
                'status' => false,
                'error' => 'Halaman Tidak dapat Diakses',
                'data' => [],
            ], Response::HTTP_FORBIDDEN);

        }

        return $next($request);
    }
}
