<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\\Contracts\\GroupInterface',
            'App\\Repositories\\GroupRepository',
        );
        $this->app->bind(
            'App\Contracts\AdminInterface',
            'App\Repositories\AdminRepository',
        );
        $this->app->bind(
            'App\Contracts\UserInterface',
            'App\Repositories\UserRepository',
        );
        $this->app->bind(
            'App\Contracts\FileInterface',
            'App\Repositories\FileRepository',
        );
        $this->app->bind(
            'App\Contracts\FIleHistoryInterface',
            'App\Repositories\FileHistoryRepository',
        );

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
