<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected Model $model;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->model = $this->getModel();
    }

    /**
     * Get the model instance.
     *
     * @return Model
     */
    abstract protected function getModel(): Model;

    /**
     * Get all records.
     *
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->filter()->all($columns);
    }

    /**
     * Get paginated records.
     *
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->filter()->paginate($perPage, $columns);
    }

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @param array $columns
     * @return Model|null
     */
    public function find(int $id, array $columns = ['*']): ?Model
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Find a record by ID or fail.
     *
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOrFail(int $id, array $columns = ['*']): Model
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Find records by criteria.
     *
     * @param array $criteria
     * @param array $columns
     * @return Collection
     */
    public function findBy(array $criteria, array $columns = ['*']): Collection
    {
        return $this->model->where($criteria)->get($columns);
    }

    /**
     * Find a single record by criteria.
     *
     * @param array $criteria
     * @param array $columns
     * @return Model|null
     */
    public function findOneBy(array $criteria, array $columns = ['*']): ?Model
    {
        return $this->model->where($criteria)->first($columns);
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a record.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $record = $this->findOrFail($id);
        return $record->update($data);
    }

    /**
     * Delete a record.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $record = $this->findOrFail($id);
        return $record->delete();
    }

    /**
     * Count records.
     *
     * @param array $criteria
     * @return int
     */
    public function count(array $criteria = []): int
    {
        return $this->model->where($criteria)->count();
    }

    /**
     * Check if a record exists.
     *
     * @param array $criteria
     * @return bool
     */
    public function exists(array $criteria): bool
    {
        return $this->model->where($criteria)->exists();
    }
}
