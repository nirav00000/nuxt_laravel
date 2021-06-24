<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class GoogleAuthentication
{
    /**
     * Match $request->email with verified google email (received in token)
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    public function handle($request, Closure $next)
    {
        // App in stagging or production
        if (env('APP_ENV') !== 'local' && env('APP_ENV') !== 'testing') {
            // Read OAuth token
            $access_token = $request->header('x-access-token');

            if (!$access_token) {
                return response([
                    'success' => false,
                    'message' => 'Invalid access token!'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Get user profile from OAuth token
            $client = new \GuzzleHttp\Client(['base_uri' => env('GOOGLE_OAUTH_URL', 'https://www.googleapis.com/oauth2/v3/')]);
            $res = $client->request('POST', 'userinfo', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ]);
            $res = json_decode($res->getBody()->getContents());

            // Request email and OAuth email not match
            if ($request->email != $res->email) {
                return response([
                    'success' => false,
                    'message' => 'Invalid email sent!'
                ], Response::HTTP_BAD_REQUEST);
            }
            return $next($request);
        } else {
            return $next($request);
        }
    }
}
