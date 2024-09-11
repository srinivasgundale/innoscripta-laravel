<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // If the request does not expect JSON (i.e., it is a web request), redirect to the login page
        if (!$request->expectsJson()) {
            return route('login');
        }

        // For API requests, return null to prevent redirection and instead trigger a 401 Unauthorized response
        return null;
    }
}
