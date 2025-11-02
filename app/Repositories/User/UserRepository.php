<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository
{
    /**
     * Get the model instance.
     *
     * @return Model
     */
    protected function getModel(): Model
    {
        return new User();
    }

    /**
     * Find user by email.
     *
     * @param string $email
     * @return Model|null
     */
    public function findByEmail(string $email): ?Model
    {
        return $this->findOneBy(['email' => $email]);
    }
}
