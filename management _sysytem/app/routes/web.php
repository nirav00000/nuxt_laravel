<?php

/**
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a Group which
    | contains the "web" middleware Group. Now create something great!
    |
 */

Route::get(
    '/',
    function () {
        return view('welcome');
    }
);

Route::get(
    '/docs',
    function () {
        return view('docs');
    }
)->name('docs');

Route::get(
    '/mailable',
    function () {
        $user = App\User::find(1);

        return new App\Mail\WelcomeEmail($user->toArray());
    }
);
Route::get('/healthz', 'HealthCheckController@index')->name('healthcheck');
Route::get('/healthz/api', 'HealthCheckController@api')->name('healthcheck.api');
