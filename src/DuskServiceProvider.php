<?php

namespace Laravel\Dusk;

use WpStarter\Support\Facades\Route;
use WpStarter\Support\ServiceProvider;

class DuskServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->environment('production')) {
            Route::group(array_filter([
                'prefix' => ws_config('dusk.path', '_dusk'),
                'domain' => ws_config('dusk.domain', null),
                'middleware' => ws_config('dusk.middleware', 'web'),
            ]), function () {
                Route::get('/login/{userId}/{guard?}', [
                    'uses' => 'Laravel\Dusk\Http\Controllers\UserController@login',
                    'as' => 'dusk.login',
                ]);

                Route::get('/logout/{guard?}', [
                    'uses' => 'Laravel\Dusk\Http\Controllers\UserController@logout',
                    'as' => 'dusk.logout',
                ]);

                Route::get('/user/{guard?}', [
                    'uses' => 'Laravel\Dusk\Http\Controllers\UserController@user',
                    'as' => 'dusk.user',
                ]);
            });
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
                Console\DuskCommand::class,
                Console\DuskFailsCommand::class,
                Console\MakeCommand::class,
                Console\PageCommand::class,
                Console\PurgeCommand::class,
                Console\ComponentCommand::class,
                Console\ChromeDriverCommand::class,
            ]);
        }
    }
}
