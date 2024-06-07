<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Storage;

trait StoreImage
{
    public function storeImage($file, $column, $size = 1280, $crop = false)
    {
        $routePublic = 'app/public/';
        //compress photo
        $image = $file->store($this->storageRoute, 'public');
        $img = Image::make(storage_path('app/public/'.$image));
        $img->encode('webp', 90);

        $w = $img->width();
        $h = $img->height();
        $compare = 0;

        if ($crop) { //resize min
            $compare = min($w, $h);
        } else { //resize max
            $compare = max($w, $h);
        }

        if ($compare > $size) {
            if ($w > $size) {
                $img->resize($size, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            if ($h > $size) {
                $img->resize(null, $size, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        if ($crop) {
            $img->crop($size, $size);
        }

        $imageName = explode('.', $image);
        $webp = $imageName[0].'.webp';
        $img->save(storage_path($routePublic.$webp));

        //make tmp file to upload
        $webp = storage_path($routePublic.$webp);
        $path_parts = pathinfo($webp);
        $newPath = $path_parts['dirname'].'/tmp-files/';
        if (! is_dir($newPath)) {
            mkdir($newPath, 0777);
        }

        $tempUrl = $newPath.$path_parts['basename'];
        copy($webp, $tempUrl);

        $imgInfo = getimagesize($tempUrl);

        $file = new UploadedFile(
            $tempUrl,
            $path_parts['basename'],
            $imgInfo['mime'],
            filesize($tempUrl),
            true,
            true
        );

        //save to s3
        $s3 = Storage::disk('s3')->put($this->storageRoute, $file, 'public');
        $this->$column = $s3;
        $this->save();

        //remove tmp file
        try {
            unlink($tempUrl);
        } catch(\Exception $e) {
            error_log($e);
        }
        try {
            unlink($webp);
        } catch(\Exception $e) {
            error_log($e);
        }
        try {
            unlink(storage_path($routePublic.$image));
        } catch(\Exception $e) {
            error_log($e);
        }

        return $s3;
    }

    public function unlink($column)
    {
        try {
            return Storage::disk('s3')->delete($this->$column);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function storeFromLocal($fileLocation, $column)
    {
        $url = public_path('images/'.$fileLocation);
        $path_parts = pathinfo($url);
        $newPath = $path_parts['dirname'].'/tmp-files/';
        if (! is_dir($newPath)) {
            mkdir($newPath, 0777);
        }

        $newUrl = $newPath.$path_parts['basename'];
        copy($url, $newUrl);
        $imgInfo = getimagesize($newUrl);

        $file = new UploadedFile(
            $newUrl,
            $path_parts['basename'],
            $imgInfo['mime'],
            filesize($newUrl),
            true,
            true
        );

        $image = $this->storeImage($file, $column);
        try {
            unlink($newUrl);
        } catch(\Exception $e) {
            error_log($e);
        }

        return $image;
    }

    public function getimageAttribute()
    {
        try {
            return $this->image_url ? Storage::disk('s3')->url($this->image_url) : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
