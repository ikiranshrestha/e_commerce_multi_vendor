<?php

namespace App\Providers;

use App\Repositories\AirportRepository;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\CollectionRepositoryInterface;
use App\Repositories\Contracts\CommissionRuleRepositoryInterface;
use App\Repositories\Contracts\ImportRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\CollectionRepository;
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
        $this->app->bind(CollectionRepositoryInterface::class, CollectionRepository::class);
    }

    public function boot()
    {
        //
    }
}
