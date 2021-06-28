<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ProfilePhotoUploadController extends Controller
{
    public function __invoke()
    {
        request()->validate(['photo' => 'nullable|image']);

        $file = request()->file('photo');

        if (!$file) {
            return response()->json(['url' => null]);
        }

        $fileName = sprintf('avatar_%s.png', user()->id);
        $disk     = config('filesystems.default');
        $uploaded = Storage::disk($disk)->putFileAs('profiles', $file, $fileName);

        return response()->json(['url' => $uploaded ? Storage::disk($disk)->url($uploaded) : null]);
    }
}
