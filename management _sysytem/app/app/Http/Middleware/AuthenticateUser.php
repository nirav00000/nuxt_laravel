<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the token from x-auth-token
        $token = $request->header("x-auth-token");


        // If x-auth-token not found in header
        if (isset($token) === false) {
            return response(['success' => false, 'message' => 'Auth Token is not set', 'error' => []], Response::HTTP_BAD_REQUEST);
        }

        // App is in development mode
        if (env('APP_ENV') === 'local' || env('APP_ENV') === 'testing') {
            try {
                JWTAuth::setToken($token);
                $user = JWTAuth::toUser($token); // error if not found
            } catch (Exception $e) {
                return response(["success" => false,"message" => "User is not authenticated", "error" => []], Response::HTTP_UNAUTHORIZED);
            }

            $request->oauth_email = $user->email;
            $request->oauth_name = $user->name;
            if ($request->header("group")) {
                $group = $request->header("group");
                $group = explode(',', $group);
                $request->oauth_groups = [];
                foreach ($group as $g) {
                    array_push($request->oauth_groups, trim($g));
                }
            } else {
                $request->oauth_groups = [];
            }

            Auth::login($user);
            return $next($request);
        } else {
        // App is not in development, we need to verify token with dex
            $res = Cache::remember('userinfo' . $token, 3600, function () use ($token) {
                try {
                    $client = new \GuzzleHttp\Client(['base_uri' => env('DEX_OAUTH_URL', 'https://dex.improwised.dev/')]);
                    $res = $client->request('POST', 'userinfo', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $token
                        ]
                    ]);
                    return json_decode($res->getBody()->getContents());
                } catch (Exception $e) {
                    return null;
                }
            });

            if ($res == null) {
                return response(['success' => false, 'message' => 'Invalid token sent', 'error' => []], Response::HTTP_UNAUTHORIZED);
            }

            $user = User::where('email', $res->email)->first();

        // App in stagging
            if (env('APP_ENV') === 'stagging') {
                if (!$user) {
                    $user = new User();
                    $user->name = $res->preferred_username;
                    $user->email = $res->email;
                    $user->password = Str::random(24);
                    $user->save();
                }
            } else {
                // App in production
                if (!$user) {
                    return response(['success' => false, 'message' => 'Invalid user token, User is not found in the database!', 'error' => []], Response::HTTP_UNAUTHORIZED);
                }
            }

            Auth::login($user);
            $request->oauth_name = $res->preferred_username;
            $request->oauth_email = $res->email;
            if (!isset($res->groups)) {
                $request->oauth_groups = [];
            } else {
                $request->oauth_groups = [];
                if (count($res->groups) > 0) {
                    foreach ($res->groups as $g) {
                        array_push($request->oauth_groups, trim($g));
                    }
                }
            }
            return $next($request);
        }
    }
}
