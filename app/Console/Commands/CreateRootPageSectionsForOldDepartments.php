<?php

namespace App\Console\Commands;

use App\Models\Department;
use Illuminate\Console\Command;

class CreateRootPageSectionsForOldDepartments extends Command
{
    protected $signature = 'page-sections:create';

    protected $description = "This will create a root training page section for all departments that don't have one.";

    public function handle()
    {
        Department::query()
            ->whereDoesntHave('trainingPageSections')
            ->get()
            ->each(fn (Department $department) => $this->createRootSection($department));

        return 0;
    }

    private function createRootSection(Department $department)
    {
        $department->trainingPageSections()->create(['title' => 'Training Page']);
    }
}
