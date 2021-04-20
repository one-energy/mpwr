<?php

namespace App\Actions;

use App\Models\TrainingPageSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DestroySection
{
    public function execute(TrainingPageSection $section)
    {
        DB::transaction(function () use ($section) {
            $this->moveChildrenToParent($section);

            $section->delete();

            $this->removeDirectory($section);
        });
    }

    private function moveChildrenToParent(TrainingPageSection $section)
    {
        TrainingPageSection::query()
            ->whereParentId($section->id)
            ->get()
            ->each(function (TrainingPageSection $childSection) use ($section) {
                $childSection->update(['parent_id' => $section->parent_id]);
            });
    }

    private function removeDirectory(TrainingPageSection $section)
    {
        Storage::disk('local')->deleteDirectory(sprintf('files/%s', $section->id));
    }
}