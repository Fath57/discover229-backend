<?php

namespace App\Traits;

use App\Models\AppFile;

trait HasFile
{
    public function image()
    {
        return $this->morphOne(AppFile::class, 'model', 'model_type');
    }

    public function images()
    {
        return $this->morphMany(AppFile::class, 'model', 'model_type');
    }

    public function files()
    {
        return $this->morphMany(AppFile::class, 'model', 'model_type');
    }


}
