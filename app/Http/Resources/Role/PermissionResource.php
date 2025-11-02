<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'module' => $this->getModule(),
            'action' => $this->getAction(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Get the module name from permission.
     *
     * @return string
     */
    private function getModule(): string
    {
        return explode('.', $this->name)[0] ?? 'unknown';
    }

    /**
     * Get the action from permission.
     *
     * @return string
     */
    private function getAction(): string
    {
        $parts = explode('.', $this->name);
        return $parts[1] ?? 'unknown';
    }
}
