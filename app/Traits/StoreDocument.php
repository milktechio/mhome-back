<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Storage;

trait StoreDocument
{
    public function storeDocument($file, $column)
    {
        $input_file = $file->getClientOriginalName();
        $extension = '.'.pathinfo($input_file, PATHINFO_EXTENSION);

        $file = Storage::disk('s3')->put($this->storageRoute, $file, 'public');

        $this->$column = $file;
        $this->extension = $extension;
        $this->save();

        return $file;
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

        $mime = mime_content_type($newUrl);

        $file = new UploadedFile(
            $newUrl,
            $path_parts['basename'],
            $mime,
            filesize($url),
            true,
            true
        );

        return $this->storeDocument($file, $column);
    }

    public function unlink($column)
    {
        try {
            return Storage::disk('s3')->delete($this->$column);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getfileAttribute()
    {
        try {
            return Storage::disk('s3')->url($this->file_url);
        } catch (\Exception $e) {
            return null;
        }
    }
}
