<?php

namespace App\Traits\Common;

use Illuminate\Support\Facades\DB;

trait DeleteFileTrait
{
    public function deleteImage($id)
    {
        return DB::table('fct_images')->where('id', $id)->delete();
    }
}
