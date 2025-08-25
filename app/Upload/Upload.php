<?php

namespace App\Upload;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use File;

class Upload
{
    public static function uploadImage($image, $sectionName, $name){
        $fileName = Str::slug($name) . '.' . 'jpg';
        $fileName2 = Str::slug($name) . '.' . 'png';
        $image->storePubliclyAs("/public/${sectionName}/", $fileName);
        $image->move(public_path()."/storage/main/${sectionName}/", $img = $fileName);
        $from_path = public_path()."/storage/main/${sectionName}/".$fileName;
        $to_path = public_path()."/storage/main/${sectionName}/".$fileName2;
        File::copy($from_path, $to_path);
        return $fileName;
    }
}