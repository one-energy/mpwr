<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;


class ProfilePhotoUploadController extends Controller
{
    public function __invoke()
    {
        $url      = '';
        $file     = request()->file('photo_url');
        if (!$file) {
            return response()->json(['url' => null]);
        }

        $fileName = "{user()->id}_.png";
        $uploaded = Storage::disk('public')->putFileAs('profile-avatar', $file, $fileName);

        if ($uploaded) {
            $path = str_replace('storage', 'public', user()->photo_url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $url = Storage::disk('public')->url($uploaded);
        }

        return response()->json(['url' => $url]);
    }
}
