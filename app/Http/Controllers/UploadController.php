<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function store(Request $request) {



        if ($request->hasFile($request->file_source)) {
            $id = uniqid();
            $file = $request ->file($request->file_source);
            // Регулярное выражение для удаления символов Unicode U+0306
            $pattern = '/\p{Mn}/u';
            $filename = preg_replace($pattern, '', $file->getClientOriginalName());
            $folder = 'filepond_temp/' . $request->file_source . '/' . $id . '-' . now()->timestamp;
            $file_path = $folder . '/' . $filename;
            $file->storeAs($folder, $filename, 'public');
            return $file_path;
        }
        else {
            return '';
        }

    }
}
