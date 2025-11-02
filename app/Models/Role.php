<?php

namespace App\Models;

use OpenApi\Attributes as OA;
use Spatie\Permission\Models\Role as SpatieRole;

#[OA\Schema(
    schema: 'Role',
    title: 'Role',
    description: 'Role model with permissions',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'admin_system'),
        new OA\Property(property: 'guard_name', type: 'string', example: 'web'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Administrateur système avec accès complet'),
        new OA\Property(
            property: 'permissions',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Permission')
        ),
        new OA\Property(property: 'permissions_count', type: 'integer', example: 10),
        new OA\Property(property: 'users_count', type: 'integer', example: 5),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00.000000Z')
    ]
)]
class Role extends SpatieRole
{
    // Using Spatie's Role model
}
