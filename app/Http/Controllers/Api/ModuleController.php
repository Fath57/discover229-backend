<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ModuleController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $modules = Module::all();
        $permissions = \App\Models\Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        foreach ($modules as $module) {
            $module->setRelation('permissions', $permissions->get($module->key, collect()));
        }

        return $this->successResponse(
            ModuleResource::collection($modules),
            'Modules retrieved successfully'
        );
    }
}

