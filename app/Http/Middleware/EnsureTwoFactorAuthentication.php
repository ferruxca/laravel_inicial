<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && 
            $user->hasEnabledTwoFactorAuthentication() && 
            ! $request->session()->get('two_factor_confirmed_at') &&
            ! $request->routeIs('two-factor.*')) {
            
            return redirect()->route('two-factor.challenge');
        }

        return $next($request);
    }
}
