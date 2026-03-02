<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSupplier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            abort(403, 'Access denied. You must be logged in.');
        }

        // Admin can access supplier area
        if ($request->user()->hasRole('admin') || $request->user()->isAdmin()) {
            return $next($request);
        }

        // supplier_worker should use the field supplier routes, not the main supplier routes
        if ($request->user()->isSupplierWorker()) {
            return redirect()->route('supplier.field.dashboard');
        }

        if (!$request->user()->hasRole('supplier')) {
            abort(403, 'Access denied. This area is for suppliers only.');
        }

        return $next($request);
    }
}
