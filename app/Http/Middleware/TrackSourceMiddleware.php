<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrackSourceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('utm_source') || $request->has('source')) {
            $source = $request->input('utm_source') ?? $request->input('source');
            session(['order_source' => $source]);
        }
        
        return $next($request);
    }
}
