<?php

namespace App\Models;

use App\Traits\SecureDelete;
use App\Traits\HasFile;
use Astrotomic\Translatable\Translatable;
use Baro\PipelineQueryCollection\Concerns\Filterable;
use Baro\PipelineQueryCollection\Contracts\CanFilterContract;
use Baro\PipelineQueryCollection\ScopeFilter;
use Baro\PipelineQueryCollection\Sort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OurService extends Model implements CanFilterContract
{
    use Filterable, Translatable, HasFile, SecureDelete;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
    ];

    public $translatedAttributes = ['name', 'description'];


    public function getFilters(): array
    {
        return [
            new ScopeFilter('search'),
            new Sort(),
        ];
    }

    public function scopeSearch(Builder $query, string $keyword)
    {
        return $query->where(function (Builder $query) use ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        });
    }

    public static function secureDeleteRelations(): array
    {
        return [];
    }
}
