<?php

namespace App\Console\Commands;

use App\Models\Region;
use App\Models\TrainingPageSection;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CreateSectionForRegions extends Command
{
    protected $signature = 'create:section-for-regions';

    protected $description = 'Will create a section (folder) for each Region registered in the database';

    public function handle()
    {
        /** @var Collection $regions */
        $regions = Region::all();

        $bar = $this->output->createProgressBar($regions->count());


        $regions->each(function (Region $region) use ($bar) {
            $rootParent = $this->getRootParent($region);

            $region->trainingPageSections()->create([
                'title'             => ucwords($region->name),
                'parent_id'         => $rootParent->id ?? null,
                'department_id'     => $region->department_id,
                'department_folder' => false,
            ]);

            $bar->advance();
        });

        $bar->finish();

        $this->newLine();

        $this->info('Sections created successfully.');
    }

    private function getRootParent(Region $region)
    {
        return TrainingPageSection::query()
            ->where(function (Builder $query) use ($region) {
                $query->where('department_id', $region->department_id)
                    ->whereNull('parent_id');
            })
            ->first();
    }
}
