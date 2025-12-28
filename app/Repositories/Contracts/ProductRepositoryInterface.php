<?php

namespace App\Repositories\Contracts;

use App\Models\Product;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function updateOrCreate(array $attributes, array $values): Product;
}
