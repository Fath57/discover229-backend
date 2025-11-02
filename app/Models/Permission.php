<?php

namespace App\Models;

use OpenApi\Attributes as OA;
use Spatie\Permission\Models\Permission as SpatiePermission;

#[OA\Schema(
    schema: 'Permission',
    title: 'Permission',
    description: 'Permission model',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'users.view'),
        new OA\Property(property: 'guard_name', type: 'string', example: 'web'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Voir les utilisateurs'),
        new OA\Property(property: 'module', type: 'string', example: 'users'),
        new OA\Property(property: 'action', type: 'string', example: 'view'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00.000000Z')
    ]
)]
class Permission extends SpatiePermission
{
    // Using Spatie's Permission model
}
