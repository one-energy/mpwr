<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PhotosSeeder extends Seeder
{
    public function run()
    {
        Storage::disk('public')->makeDirectory('profiles');

        File::copy(__DIR__ . '/photos/' . 'profile.png', storage_path('/app/public/profiles/' . 'profile.png'));
    }
}
