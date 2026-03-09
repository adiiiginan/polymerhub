<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;

class SetLocaleDefaults
{
    public function handle($request, Closure $next)
    {
        $locale = $request->segment(1);

        if (in_array($locale, ['en', 'id'])) {
            URL::defaults(['locale' => $locale]);
        }

        return $next($request);
    }
}
