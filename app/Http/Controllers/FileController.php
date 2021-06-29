<?php

namespace App\Http\Controllers;

use App\Models\TrainingPageSection;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class FileController extends Controller
{
    public function uploadSectionFile(TrainingPageSection $section)
    {
        $this->authorize('uploadSectionFile', [TrainingPageSection::class, $section]);


        $files = request()->validate([
            'files'   => 'required|array',
            'files.*' => 'mimes:jpg,png,bmp,odp,otp,pptx,ppt,pps,pot',
            'training_type' => 'required|'
        ]);

        try {
            return collect($files)->map(function (UploadedFile $file) use ($section) {
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
            return response()->json(['data' => ['message' => $e->getMessage()]], Response::HTTP_BAD_REQUEST);
        }
    }

    public function downloadSectionFile()
    {
        return Storage::disk('local')->download(request()->path);
    }
}
