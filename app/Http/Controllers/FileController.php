<?php

namespace App\Http\Controllers;

use App\Models\TrainingPageSection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Throwable;

class FileController extends Controller
{
    public function uploadSectionFile(TrainingPageSection $section)
    {
        $this->authorize('uploadSectionFile', TrainingPageSection::class);
        
        $request = request()->all();

        try {
            return collect($request['files'])->map(function ($file) use ($section) {
                $path = $file->store("files/{$section->department_id}", 'local');
                $section->files()->create([
                    'name'          => $file->hashName(),
                    'original_name' => Str::of($file->getClientOriginalName())->beforeLast('.'),
                    'size'          => $file->getSize(),
                    'type'          => $file->extension(),
                    'path'          => $path,
                    'training_type' => request()->meta['training_type'],
                ]);
                
    
                return $file->getClientOriginalName();
            });
        } catch (Throwable $e) {    
            abort(400, $e->getMessage());
        }

       
    }

    public function downloadSectionFile()
    {
        return Storage::disk('local')->download(request()->path);
    }
}
