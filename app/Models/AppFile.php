<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppFile extends Model
{
    use HasFactory;

    protected $appends = ['file_url'];

    protected $guarded = [];

    public function model()
    {
        return $this->morphTo('model');
    }

    public function getFileUrlAttribute(): string
    {
        return asset($this->path);
    }
}
