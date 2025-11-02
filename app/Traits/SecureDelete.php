<?php

namespace App\Traits;

use App\Exceptions\SecureDeleteException;

trait SecureDelete
{
    /**
     * Delete only when there is no reference to other models.
     *
     * @throws SecureDeleteException
     */
    public function secureDelete(array $relations): void
    {
        $hasRelation = false;
        foreach ($relations as $relation) {
            if ($this->$relation()->count()) {
                $hasRelation = true;
                break;
            }
        }

        if ($hasRelation) {
            throw new SecureDeleteException(get_class($this));
        } else {
            $this->delete();
        }
    }
}
