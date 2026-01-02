<?php

namespace App\services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function Illuminate\Support\now;

class pathphoto
{
    function createPathPhoto($file, $dir)
    {
        $uniqueUrl = Str::random(32);
        $extension = $file->getClientOriginalExtension();
        $path = $file->storeAs($dir, $uniqueUrl . now() . "." . $extension, "public");
        if ($path) {
            return $path;
        }
    }

    function updatePathPhoto($path, $file, $dir)
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
        $uniqueUrl = Str::random(32);
        $extension = $file->getClientOriginalExtension();
        $path = $file->storeAs($dir, $uniqueUrl . now() . "." . $extension, "public");
        return $path;
    }
}
