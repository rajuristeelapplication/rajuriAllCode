<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UploadFile;
use Illuminate\Support\Facades\Storage;

class CommonController extends Controller
{
    /**
     * User able to upload files of different modules
     *
     * @param  UploadFile $request
     * @return json
     */
    public function uploadFile(UploadFile $request)
    {

        $S3Local = config('constant.S3Local');

        $type = strtolower($request->type);
        $imageShortName = $type . '_' . time() . '_' . strtolower(\Str::random(6)) . '.' . $request->file->getClientOriginalExtension();

        $imageName = 'images/' . $type . '/' . $imageShortName;
        $file = $request->file('file');

        $putFile = Storage::disk($S3Local)->put($imageName, file_get_contents($file));

        $imageName = Storage::disk($S3Local)->url($imageName);


        return $this->toJson([
            'imageUrl' => url('storage/images/'.$type.'/'.$imageShortName),
            'imageName' => $imageShortName,
        ]);
    }
}

