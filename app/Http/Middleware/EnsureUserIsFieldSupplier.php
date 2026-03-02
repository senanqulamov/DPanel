<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsFieldSupplier
{
    /**
     * Handle an incoming request.
     * Allows supplier_worker role (field supplier) users.
     * Also allows admins.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            abort(403, 'Access denied. You must be logged in.');
        }

        // Admin can access field supplier area
        if ($request->user()->hasRole('admin') || $request->user()->isAdmin()) {
            return $next($request);
        }

        if (! $request->user()->isSupplierWorker()) {
            abort(403, 'Access denied. This area is for field suppliers only.');
        }

        return $next($request);
    }
}
