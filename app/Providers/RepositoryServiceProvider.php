<?php

namespace App\Providers;

use App\Repositories\AirportRepository;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\CommissionRuleRepository;
use App\Repositories\Contracts\AirportRepositoryInterface;
use App\Repositories\Contracts\CommissionRuleRepositoryInterface;
use App\Repositories\Contracts\ImportRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\ImportRepository;
use App\Repositories\ProductRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Base binding (optional, only if you inject BaseRepositoryInterface)
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);

        // Specific repositories
        $this->app->bind(ImportRepositoryInterface::class, ImportRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }

    public function boot()
    {
        //
    }
}
