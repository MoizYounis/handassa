<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

/**
 * Image upload using Intervention
 */
trait ImageUpload
{
    public function upload($media, $path = 'images/', $width = 350, $height = 350, $is_binary = false)
    {
        if ($is_binary) {
            $imageName = uniqid() . '.' . 'png';
            $imagePath = storage_path($path) . $imageName;
            File::put($imagePath, base64_decode($media));
            return explode('public/', $imagePath)[1];
        } else {
            $image = time() . '.' . $media->getClientOriginalExtension();

            $img = Image::make($media->getRealPath());

            // $img->resize($width, $height, function ($constraint) {
            //     $constraint->aspectRatio();
            // });

            $img->stream(); // <-- Key point
            $imageWithPath = $path . $image;
            Storage::disk('public')->put($imageWithPath, $img);
            // return explode('public/', $imageWithPath)[1];
            return $imageWithPath;
        }
    }
}
