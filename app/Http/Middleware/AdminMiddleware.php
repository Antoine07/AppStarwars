<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AdminMiddleware
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
//        if ($request->user()->role == 'visitor')
//            return redirect()->cart();

        if ($request->user()->role != 'administrator')
            return redirect()->home();

        return $next($request);
    }
}
