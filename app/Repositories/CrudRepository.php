<?php

namespace App\Repositories;

use App\Services\AITranslationService;
use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CrudRepository
{

    use UploadFile;
    private AITranslationService $aiTranslationService;

    public function __construct(public Model|string $model)
    {
        if (is_string($this->model)) {
            $this->model = app($this->model);
        }
        $this->aiTranslationService = new AITranslationService();
    }


    public function findOrFail($id, $relations = [])
    {
        return $this->model->newQuery()->with($relations)->findOrFail($id);
    }

    public function find($id, ?array $relations = null)
    {
        return $this->model->newQuery()
            ->find($id)?->load($relations ?? []);
    }

    /**
     * @param array|null $relations
     * @return mixed
     */
    public function getAll($columns = "*", array $relations = [], $withCount = [], bool $tenant = true): mixed
    {
        $paginate = request()->boolean('paginate', true) !== false;

        $query = $this->model
            ->newQuery()
            ->filter()
            ->select($columns)
            ->with($relations)
            ->orderByDesc('created_at');

        $query = $this->filterQuery($query);
        $query->withCount($withCount);

        return $paginate ? $query->paginate(request('per_page', 15)) : $query->get();
    }

    /**
     * @param array $data
     * @param Request|null $request
     * @return Builder|Model|void
     */
    public function store(array $data, ?Request $request = null)
    {
        DB::beginTransaction();
        try {
            if ($this->model->translatedAttributes && !empty($this->model->translatedAttributes)){
                $data['fr'] = Arr::only($data, $this->model->translatedAttributes);
            }
            $model = $this->model->newQuery()->create($data);


            if ($request?->hasFile("file")) {
                $filePath = $this->saveFile($request, $this->model->getTable());
                $model->file()->create($filePath);
            }

            if ($request?->hasFile("photo")) {
                $filePath = $this->saveFile($request, $this->model->getTable(), "photo");
                $data['photo'] = $filePath;
                $model->image()->create($filePath);
            }

            if ($request?->hasFile("image")) {
                $filePath = $this->saveFile($request, $this->model->getTable(), "image");
                $model->image()->create($filePath);
            }

            if ($request?->hasFile("video")) {
                $filePath = $this->saveFile($request, $this->model->getTable(), "video");
                $model->file()->create($filePath);
            }

            if ($request?->hasFile("icon")) {
                $filePath = $this->saveFile($request, $this->model->getTable(), "icon");
                $model->file()->create($filePath);
            }


            if ($request && count($request?->allFiles()) > 0) {
                $this->saveManyFiles($request, $model);
            }

            $this->aiTranslationService->translateModel($model);

            DB::commit();
            return $model;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::debug($exception);
            abort(ResponseAlias::HTTP_INTERNAL_SERVER_ERROR, 'Oups! Une erreur est survenue');
        }
    }

    public function storeOrUpdate(array $data, array $constraint = null): Model|Builder
    {
        if ($constraint) {
            $model = $this->model::query()->updateOrCreate($constraint, $data);
        } else {
            $model = $this->model::query()->updateOrCreate($data);
        }

        return $model;
    }

    /**
     * @param Model $model
     * @param array $data
     * @param Request|null $request
     * @return Model
     */
    public function update(Model $model, array $data, ?Request $request = null): Model
    {
        DB::beginTransaction();
        try {

            if ($this->model->translatedAttributes && !empty($this->model->translatedAttributes)){
                $data['fr'] = Arr::only($data, $this->model->translatedAttributes);
            }

            if ($request?->hasFile("file")) {
                $filePath = $this->saveFile($request, $this->model->getTable());
                $data['file'] = $filePath;
            }

            if ($request && $request->hasFile("photo")) {
                $filePath = $this->saveFile($request, $this->model->getTable(), "photo");
                $data['photo'] = $filePath;
            }

            if ($request?->hasFile("image")) {
                $filePath = $this->saveFile($request, $this->model->getTable(), "image");
                $data['image'] = $filePath;
            }

            if ($request && $request->hasFile("video")) {
                $filePath = $this->saveFile($request, $this->model->getTable(), "video");
                $data['video'] = $filePath;
            }

            if ($request && $request->hasFile("icon")) {
                $filePath = $this->saveFile($request, $this->model->getTable(), "icon");
                $data['icon'] = $filePath;
            }

            $model->update($data);

            if ($request && count($request?->allFiles()) > 0) {
                $this->saveManyFiles($request, $model);
            }

            $this->aiTranslationService->translateModel($model);
            DB::commit();
            return $model->refresh();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::debug($exception);
            abort(ResponseAlias::HTTP_INTERNAL_SERVER_ERROR, 'Oups! Une erreur est survenue');
        }
    }

    /**
     * @param Model $model
     * @return Model
     */
    public function delete(Model $model): Model
    {
        $model->secureDelete($this->model->secureDeleteRelations());

        return $model;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function deleteBulk(array $ids): array
    {
        foreach ($ids as $id) {
            $model = $this->find($id);
            $model->secureDelete($this->model->secureDeleteRelations());
        }

        return $ids;
    }

    public function filterQuery(Builder $query): Builder
    {

        return $query;
    }

    /**
     * @param mixed $request
     * @param Model $model
     * @return void
     */
    public function saveManyFiles(?Request $request, Model $model): void
    {
        if ($request && ($request->has('images') || $request->has('files'))) {
            $imagePaths = $this->saveMultipleFiles($request, $this->model->getTable(), $request->has('images') ? 'images' : 'files');
            if ($imagePaths) {
                foreach ($imagePaths as $imagePath) {
                    $imagePath += [
                        "model_type" => $this->model::class,
                        "model_id" => $model->id
                    ];
                    $this->createFile($imagePath);
                }
            }
        }
    }
}
