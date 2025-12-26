<?php

namespace App\Services;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    protected BaseRepositoryInterface $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    protected function repo(): BaseRepositoryInterface
    {
        return $this->repository;
    }

    public function all()
    {
        return $this->repo()->all();
    }

    public function find(int $id): Model
    {
        return $this->repo()->find($id);
    }

    public function create(array $data): Model
    {
        return $this->repo()->create($data);
    }

    public function update(int $id, array $data): Model
    {
        return $this->repo()->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repo()->delete($id);
    }
}
