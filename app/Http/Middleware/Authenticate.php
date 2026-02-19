<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Cek apakah request untuk rute customer
            if (Str::startsWith($request->route()->getName(), 'en.')) {
                return route('en.customer.login');
            }

            // Cek apakah request untuk rute customer
            if (Str::startsWith($request->route()->getName(), 'id.')) {
                return route('id.customer.login');
            }

            // Fallback ke rute login default (untuk admin)
            return route('login');
        }
    }
}
