<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use App\Models\File;
use Illuminate\Support\Str;
use App\Models\TrainingPageSection;
use Illuminate\Http\UploadedFile;

class FileController extends Controller
{

    public Collection $files;

    public function uploadSectionFile(TrainingPageSection $section)
    {
        $this->files = collect([]);
        $request = request()->all();

        return collect($request['files'])->map(function ($file) use ($section) {
            $path = $file->store('files/department', 'local');
            $file = $section->files()->create([
                'name'          => $file->hashName(),
                'original_name' => Str::of($file->getClientOriginalName())->beforeLast('.'),
                'size'          => $file->getSize(),
                'type'          => $file->extension(),
                'path'          => $path,
            ]);
            return $file->name;
        });
    }
}
