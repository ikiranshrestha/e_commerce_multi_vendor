<?php

namespace App\Repositories\Contracts;

use App\Models\Import;

interface ImportRepositoryInterface
{
    public function create(array $data): Import;
    public function find(int $id): Import;
    public function update(int $id, array $data): Import;
    public function delete(int $id): bool;
}
