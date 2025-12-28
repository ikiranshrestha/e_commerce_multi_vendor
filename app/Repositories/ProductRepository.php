<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function updateOrCreate(array $attributes, array $values): Product
    {
        return $this->model->updateOrCreate($attributes, $values);
    }
}
