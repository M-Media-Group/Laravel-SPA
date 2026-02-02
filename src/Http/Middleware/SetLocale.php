<?php

namespace Mmedia\LaravelSpa\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * We set the app locale based on the header sent by the client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $locale = $request->header('Accept-Language');
            app()->setLocale($locale);
        } catch (\Exception $e) {
            Log::warning('Failed to set locale: ' . $e->getMessage());
        }
        return $next($request);
    }
}
