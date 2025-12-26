<?php

namespace App\Providers;

use App\Repositories\AirportRepository;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\CommissionRuleRepository;
use App\Repositories\Contracts\AirportRepositoryInterface;
use App\Repositories\Contracts\CommissionRuleRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\UserRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Base binding (optional, only if you inject BaseRepositoryInterface)
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);

        // Specific repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CommissionRuleRepositoryInterface::class, CommissionRuleRepository::class);
        $this->app->bind(AirportRepositoryInterface::class, AirportRepository::class);
    }

    public function boot()
    {
        //
    }
}
