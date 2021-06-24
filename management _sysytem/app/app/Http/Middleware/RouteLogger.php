<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class RouteLogger
{


    public function handle(\Illuminate\Http\Request $request, Closure $next)
    {
        $response = $next($request);

        $path = Route::currentRouteName();
        if ($path === false) {
            $path = $request->decodedPath();
        }

        // Not logging home, docs and metrics
        if ($path !== 'home' && $path !== 'metrics' && $path !== 'l5-swagger.docs' && $path !== 'docs' && $path !== 'healthcheck') {
            // $request->route()->getName();
            $data = [
                'method'           => $request->method(),
                'path'             => $path,
                'request'          => $request->all(),
                'response'         => (string) $response,
                'http_status_code' => $response->getStatusCode(),
            ];

            log_debug('RouteLogger', ['data' => $data]);
        }

        return $response;
    }
}
