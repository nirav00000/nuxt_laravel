<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class AuthenticateAdmin
{
    /**
     * Match $request->email with verified google email (received in token)
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    public function handle($request, Closure $next)
    {
        // Check admin
        if (count($request->oauth_groups) > 0 && in_array(config("ldap.admin"), $request->oauth_groups)) {
            return $next($request);
        } else {
            return response(['success' => false, 'message' => 'Unauthorized to access admin routes'], Response::HTTP_FORBIDDEN);
        }
    }
}
