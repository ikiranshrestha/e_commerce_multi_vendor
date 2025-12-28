<?php

namespace App\Repositories;

use App\Models\Import;
use App\Repositories\Contracts\ImportRepositoryInterface;

class ImportRepository extends BaseRepository implements ImportRepositoryInterface
{
    public function __construct(Import $model)
    {
        parent::__construct($model);
    }

    // Override return type to be Import
    public function create(array $data): Import
    {
        return parent::create($data);
    }

    public function find(int $id): Import
    {
        return parent::find($id);
    }

    public function update(int $id, array $data): Import
    {
        return parent::update($id, $data);
    }
}
