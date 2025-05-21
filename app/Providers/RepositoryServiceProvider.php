<?php

namespace App\Providers;

use App\Interfaces\HeadOfFamilyRepositoryInterface;
use App\Repositories\HeadOfFamilyRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Interfaces\UserRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(HeadOfFamilyRepositoryInterface::class, HeadOfFamilyRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
