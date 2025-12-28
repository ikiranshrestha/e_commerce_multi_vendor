<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

interface BaseRepositoryInterface
{
    public function query(): Builder;
    public function all($columns = ['*']);
    public function find(int $id): Model;
    public function create(array $data): Model;
    public function update(int $id, array $data): Model;
    public function delete(int $id): bool;
    public function updateOrCreate(array $attributes, array $values): Model;
}
