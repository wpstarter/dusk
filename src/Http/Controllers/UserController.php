<?php

namespace Laravel\Dusk\Http\Controllers;

use WpStarter\Support\Facades\Auth;
use WpStarter\Support\Facades\Session;
use WpStarter\Support\Str;

class UserController
{
    /**
     * Retrieve the authenticated user identifier and class name.
     *
     * @param  string|null  $guard
     * @return array
     */
    public function user($guard = null)
    {
        $user = Auth::guard($guard)->user();

        if (! $user) {
            return [];
        }

        return [
            'id' => $user->getAuthIdentifier(),
            'className' => get_class($user),
        ];
    }

    /**
     * Login using the given user ID / email.
     *
     * @param  string  $userId
     * @param  string|null  $guard
     * @return void
     */
    public function login($userId, $guard = null)
    {
        $guard = $guard ?: ws_config('auth.defaults.guard');

        $provider = Auth::guard($guard)->getProvider();

        $user = Str::contains($userId, '@')
                    ? $provider->retrieveByCredentials(['email' => $userId])
                    : $provider->retrieveById($userId);

        Auth::guard($guard)->login($user);
    }

    /**
     * Log the user out of the application.
     *
     * @param  string|null  $guard
     * @return void
     */
    public function logout($guard = null)
    {
        $guard = $guard ?: ws_config('auth.defaults.guard');

        Auth::guard($guard)->logout();

        Session::forget('password_hash_'.$guard);
    }

    /**
     * Get the model for the given guard.
     *
     * @param  string  $guard
     * @return string
     */
    protected function modelForGuard($guard)
    {
        $provider = ws_config("auth.guards.{$guard}.provider");

        return ws_config("auth.providers.{$provider}.model");
    }
}
