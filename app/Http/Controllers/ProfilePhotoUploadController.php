<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;


class ProfilePhotoUploadController extends Controller
{
    public function __invoke(User $user)
    {
        $url      = '';
        $file     = request()->file('photo');
        if (!$file) {
            return response()->json(['url' => null]);
        }
        $fileName = "{user()->id}_" . time() .'.png';
        $uploaded = Storage::disk('public')->putFileAs('profiles', $file, $fileName);

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
