<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// if (!function_exists('uploadImage')) {
//     function uploadImage(UploadedFile $file, $path,$prefix)
//     {
//         $folder = 'uploads/'.$path;
//         $filename = $prefix. '_' .time() . '.' . $file->getClientOriginalExtension();
//         $path = $file->storeAs($folder, $filename, 'public');
//         return $filename;
//     }
// }

if (!function_exists('uploadImage')) {
    function uploadImage(UploadedFile $file, $path, $prefix)
    {
        $folderPath = public_path('uploads/' . $path);

        // Create folder if not exists
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true); // 0755 is safe
        }

        // Create a unique filename
        $filename = $prefix . '_' . time() . '_'  . uniqid()  . '.' . $file->getClientOriginalExtension();

        // Move uploaded file to the folder
        $file->move($folderPath, $filename);

        return $filename;
    }
}

if (!function_exists('check_permission')) {
    function check_permission($permission): bool
    {
        //        if (auth()->user()->user_role == 'super_admin' || auth()->user()->hasAnyPermission($permission)) {
        if (auth()->user()->hasAnyPermission($permission)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('check_access')) {
    function check_access($permission)
    {
        //        if (auth()->user()->user_role != 'super_admin' && !auth()->user()->hasPermissionTo($permission)) {
        if (!auth()->user()->hasPermissionTo($permission)) {
            return false;
        }
        return true;
    }
}
