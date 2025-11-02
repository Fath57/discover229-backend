<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'description' => $this->description,
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'permissions_count' => $this->when(
                $this->relationLoaded('permissions'),
                fn() => $this->permissions->count()
            ),
            'users_count' => $this->when(
                $this->relationLoaded('users'),
                fn() => $this->users->count()
            ),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
