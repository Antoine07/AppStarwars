<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Cache;

class CacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('get') && $request->route('cat')) {
            $url = 'route_' . str_slug($request->url());
            if (Cache::has($url)) {
                return response(Cache::get($url));
            } else {
                $response = $next($request);
                $expiresAt = Carbon::now()->addMinutes(10);
                Cache::put($url, $response->getContent(), $expiresAt);


                return $response;

            }

            return $next($request);

        }


    }
}
