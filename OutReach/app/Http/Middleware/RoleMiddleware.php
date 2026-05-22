<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * RoleMiddleware
 *
 * Ensures the authenticated user has the required role to access a route.
 * Usage: Route::middleware('role:business') or Route::middleware('role:customer')
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles - Allowed roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        foreach ($roles as $role) {
            if ($request->user()->role === $role) {
                return $next($request);
            }
            // Allow 'customer' role to also match 'new_customer'
            if ($role === 'customer' && $request->user()->role === 'new_customer') {
                return $next($request);
            }
        }

        return redirect()->route('home')->with('error', 'You do not have permission to access this page.');
    }
}
