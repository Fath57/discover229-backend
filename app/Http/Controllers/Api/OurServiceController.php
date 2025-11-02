<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OurService;
use App\Traits\CrudRepositoryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OurServiceController extends Controller
{
    use CrudRepositoryTrait;
    public function __construct()
    {
        $this->initRepository(OurService::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->paginatedResponse($this->repository->getAll(relations: ['image']));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge(['slug' => Str::slug($request->name)]);

        $request->validate([
            'name' => 'required|string|unique:our_service_translations,name|max:255',
            'slug' => 'required|string|max:255|unique:our_services,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        return $this->successResponse($this->repository->store($request->only(['name', 'description', 'slug']), $request));
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OurService $ourService)
    {
        $request->merge(['slug' => Str::slug($request->name)]);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:our_services,slug,' . $ourService->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        return $this->successResponse($this->repository->update($ourService, $request->only(['name', 'description', 'slug']), $request));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OurService $ourService)
    {
        return $this->successResponse($this->repository->delete($ourService));
    }
}
