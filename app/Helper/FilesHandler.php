<?php

//namespace App\Helper\FilesHandler;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

if (!function_exists('deleteFile')) {
    function deleteFile($path)
    {
        Storage::disk('public')->delete($path);
    }
}

if (!function_exists('upload')) {
    function upload(UploadedFile $file, $filePath)
    {
        $fileName = $file->getClientOriginalName() . '_' . microtime();
        return url('/') . '/storage/' . $file->storeAs($filePath, $fileName, 'public');
    }
}




