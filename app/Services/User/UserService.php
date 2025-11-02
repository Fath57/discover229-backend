<?php

namespace App\Services\User;

use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    /**
     * Get the repository instance.
     *
     * @return BaseRepository
     */
    protected function getRepository(): BaseRepository
    {
        return new UserRepository();
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        $data['password'] = Hash::make($data['password']);
        return $this->repository->create($data);
    }

    /**
     * Update a user.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->repository->update($id, $data);
    }

    /**
     * Find user by email.
     *
     * @param string $email
     * @return Model|null
     */
    public function findByEmail(string $email): ?Model
    {
        return $this->repository->findByEmail($email);
    }
}
