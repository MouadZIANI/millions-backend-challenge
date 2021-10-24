<?php

namespace App\Providers;

use App\Repositories\Posts\EloquentPostRepository;
use App\Repositories\Posts\PostRepository;
use App\Repositories\Users\EloquentUserRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Support\ServiceProvider;

class RegisterModelRepositoriesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserRepository::class,
            EloquentUserRepository::class
        );

        $this->app->bind(
            PostRepository::class,
            EloquentPostRepository::class
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
