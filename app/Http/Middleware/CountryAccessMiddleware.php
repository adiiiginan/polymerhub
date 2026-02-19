<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CountryAccessMiddleware
{
    public function handle($request, Closure $next)
    {


        $user = Auth::guard('customer')->user();

        if (!$user) {
            return $next($request);
        }

        $locale = $request->segment(1);
        $path   = $request->path();

        // Indonesia
        if ($user->detail->idcountry == 1) {
            if ($locale !== 'id') {
                return redirect('/id/' . ltrim(str_replace('en/', '', $path), '/'));
            }
        }

        // Selain Indonesia → EN
        if ($user->detail->idcountry != 1) {
            if ($locale !== 'en') {
                return redirect('/en/' . ltrim(str_replace('id/', '', $path), '/'));
            }
        }

        return $next($request);
    }
}
