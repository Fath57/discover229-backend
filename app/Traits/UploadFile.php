<?php

namespace App\Traits;


use App\Models\AppFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

trait UploadFile
{

    protected function saveFile(Request | UploadedFile $requestOrFile, $folder, $field = "file"): bool|array
    {
        try {
            $this->createFolder(public_path("storage/$folder"));

            $file =  $requestOrFile instanceof Request ? $requestOrFile->file($field) : $requestOrFile;
            $fileName = Str::random().'.'.Str::slug($file->getClientOriginalExtension());
            $fileOrgName = $file->getClientOriginalName();
            $path = "/$folder/$fileName";
            Storage::disk('public')->put( $path, $file->getContent(), 'public');

            return [
                "path" => $path,
                "name" => $fileOrgName,
                "extension" => $file->getClientOriginalExtension(),
                "url" => asset("storage/$path"),
                'size' => $file->getSize()
            ];
        }catch (\Exception $exception){
            Log::debug($exception);
            return false;
        }

    }

    protected function saveMultipleFiles($request, $folder, $field = "files"): array
    {
        $this->createFolder(public_path("storage/$folder"));
        $urls = [];
        $files = $request->file($field);
        foreach ($files as $file){
            $urls[] = $this->saveFile($file, $folder);
        }

        return $urls;
    }

    protected function createFolder($path): void
    {
        if(!file_exists($path)){
            File::makeDirectory($path);
        }
    }

    /**
     * @param $data
     * @return AppFile|Model
     */
    protected function createFile($data): Model|AppFile
    {
        Validator::validate($data, [
            "path" => ['required', 'string'],
            "name" => ['required', 'string'],
            "extension" => ['required', 'string'],
            "url" => ['required', 'string'],
        ]);

        return AppFile::query()->create($data);
    }

    protected function updateFile(array $data): int
    {
        return AppFile::query()->where("model_id",$data['model_id'])->update($data);
    }
    protected function deleteFile(int $id)
    {
        return AppFile::query()->where("id", $id)->delete();
    }
}
