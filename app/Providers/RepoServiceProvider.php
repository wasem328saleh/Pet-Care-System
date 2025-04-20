<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repository\UserRepositoryInterface', 'App\Repository\UserRepository');
        $this->app->bind('App\Repository\AdminRepositoryInterface', 'App\Repository\AdminRepository');
        $this->app->bind('App\Repository\SystemRepositoryInterface', 'App\Repository\SystemRepository');

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
