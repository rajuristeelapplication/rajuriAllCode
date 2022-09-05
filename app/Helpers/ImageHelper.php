<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
     /**
     * Upload Image
     *
     * @param $image
     * @param $folderName
     * @return string
     */
    public static function uploadImage($image, $folderName)
    {
        $S3Local = config('constant.S3Local');

        $type = $folderName;
        $imageShortName = $type . '_' . time() . '_' . strtolower(\Str::random(6)) . '.' . $image->getClientOriginalExtension();
        $imageName = 'images/' . $type . '/' . $imageShortName;

        $putFile = Storage::disk($S3Local)->put($imageName, file_get_contents($image));
        $imageName = Storage::disk($S3Local)->url($imageName);


        return $imageShortName;

    }


}
