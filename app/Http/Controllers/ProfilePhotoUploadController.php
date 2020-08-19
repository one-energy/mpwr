<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoUploadController extends Controller
{
    public function __invoke(User $user)
    {
        $user     = user();
        $url      = '';
        $file     = request()->file('photo');

        if (!$file) {
            return response()->json(['url' => null]);
        }

        $fileName = "avatar_{$user->id}.png";
        $disk     = config('filesystems.default');
        $uploaded = Storage::disk($disk)->putFileAs('profiles', $file, $fileName);

        if ($uploaded) {
            $path = str_replace('storage', $disk, $user->photo_url);

            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }

            $url = Storage::disk($disk)->url($uploaded);
        }

        return response()->json(['url' => $url]);
    }
}
