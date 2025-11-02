<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Module',
    title: 'Module',
    description: 'Application module grouping permissions',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'key', type: 'string', example: 'users'),
        new OA\Property(property: 'name', type: 'string', example: 'Users'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Gestion des utilisateurs'),
        new OA\Property(
            property: 'permissions',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Permission')
        ),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'name', 'description',
    ];

    /**
     * Get the permissions for the module.
     *
     * Permissions are linked by their name, which should start with the module's key.
     * e.g., Module key 'users' -> Permissions 'users.view', 'users.create'
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        // This is a creative use of hasMany. Since there's no direct foreign key,
        // we can't use it as intended. Instead, we'll handle this logic in the repository/service.
        // For the model, we can define an accessor.
        return $this->hasMany(Permission::class, 'name', 'key');
    }
}
