<?php

namespace App\Traits;

use App\Repositories\CrudRepository;

trait CrudRepositoryTrait
{
    private CrudRepository $repository;

    protected function repository(): CrudRepository
    {
        return $this->repository;
    }

    protected function initRepository(string $model): void
    {
        $this->repository = new CrudRepository(new $model);
    }

    protected function getRepository(string $model): CrudRepository
    {
        return new CrudRepository(new $model);
    }
}
