<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogPageView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users and successful responses
        if (Auth::check() && $response->isSuccessful()) {
            try {
                $route = $request->route();
                $routeName = $route ? $route->getName() : 'unknown';
                $method = $request->method();

                // Skip logging for certain routes (like API calls, assets, etc.)
                $skipRoutes = ['livewire.update', 'livewire.message'];

                if (! in_array($routeName, $skipRoutes) && ! $request->ajax() && $method === 'GET') {
                    Log::create([
                        'user_id' => Auth::id(),
                        'type' => 'page_view',
                        'action' => 'view.'.$routeName,
                        'message' => "Viewed page: {$routeName}",
                        'metadata' => [
                            'route' => $routeName,
                            'url' => $request->fullUrl(),
                            'method' => $method,
                            'referer' => $request->header('referer'),
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'created_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                // Silent fail - don't break the app if logging fails
                logger()->error('Failed to log page view: '.$e->getMessage());
            }
        }

        return $response;
    }
}
