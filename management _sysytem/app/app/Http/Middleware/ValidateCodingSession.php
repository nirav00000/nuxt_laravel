<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\CodingSession;

class ValidateCodingSession
{
    /**
     * Validate sesion is supplied and session is associated with user id
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    public function handle($request, Closure $next)
    {
        // Session is supplied
        $session = $request->session_id;

        if (!$session) {
            return response(array(
                'success' => false,
                'message' => 'please supply session id'
            ), Response::HTTP_BAD_REQUEST);
        }


        // Get the coding session infomation
        $session = CodingSession::where('key', $session)->first();

        if (!$session) {
            return response(array(
                'success' => false,
                'is_valid' => false,
                'message' => 'Invalid session'
            ), Response::HTTP_BAD_REQUEST);
        }


        // Session is exist
        $candidate = $session->candidacy->candidate;

        // Read x-auth-token from header
        $accessToken = $request->header('x-access-token');

        // If not access token supplied
        if (env('APP_ENV') !== 'testing') {
            if (!$accessToken) {
                return response(array(
                    'success' => false,
                    'is_valid' => false,
                    'message' => 'Invalid session token'
                ), Response::HTTP_BAD_REQUEST);
            }
        }

        // App is in production or stagging ; Hence we need to verify x-auth-token
        if (env('APP_ENV') !== 'local' && env('APP_ENV') !== 'testing') {
            try {
                // Get user information
                $client = new \GuzzleHttp\Client(['base_uri' => env('GOOGLE_OAUTH_URL', 'https://www.googleapis.com/oauth2/v3/')]);
                $res = $client->request('POST', 'userinfo', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken
                    ]
                ]);
                $res = json_decode($res->getBody()->getContents());
            } catch (\Exception $e) {
                return response(['success' => false, 'message' => 'Invalid token sent', 'error' => []], Response::HTTP_UNAUTHORIZED);
            }

            // Match candidate ID taken from session with User ID
            if ($res->email != $candidate->email) {
                return response(array(
                    'success' => false,
                    'is_valid' => false,
                    'message' => 'Invalid session'
                ), Response::HTTP_BAD_REQUEST);
            }
        }

        return $next($request);
    }
}
