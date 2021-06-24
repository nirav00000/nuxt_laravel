<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class KongTokenMiddleware
{


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $enabled = env('LARAVEL_KONG_AUTH_TOKEN_ENABLED', false);

        if ($enabled) {
            // See if we have the required headers. If so, attempt to login the user
            if ($request->hasHeader('X-Consumer-Username') === false) {
                abort(403, 'Access denied');
            } else {
                // See if we can find the user
                $user = User::whereEmail($request->header('X-Consumer-Username'))->first();
                if ($user) {
                    Auth::login($user);
                } else {
                    abort(403, 'Access denied');
                }
            }
        }

        return $next($request);
    }
}
