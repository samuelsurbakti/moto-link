<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Facades\Activity;
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

        if (Auth::check()) {
            $user = Auth::user();

            // Tentukan halaman/logika yang ingin dilacak
            $routeName = $request->route()?->getName();
            $url = $request->fullUrl();

            // Kamu bisa filter di sini jika ingin exclude route tertentu
            if ($routeName && !str_contains($routeName, 'api')) {
                Activity::causedBy($user)
                    ->useLog('Akses Halaman')
                    ->withProperties([
                        'route_name' => $routeName,
                        'url' => $url,
                        'method' => $request->method(),
                    ])
                    ->log("Mengakses halaman [$routeName]");
            }
        }

        return $response;
    }
}
