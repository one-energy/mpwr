<?php

namespace App\Http\Controllers;

use App\Models\TrainingPageSection;

class TrainingController extends Controller
{
    public function index(TrainingPageSection $section = null)
    {
        
        return view('training.index', [
            'sections' => $this->getParentSections($section),
            'actualSection' => $section ?? TrainingPageSection::whereId(1)->first()
        ]);
    }

    public function getParentSections($section)
    {
        return TrainingPageSection::whereParentId($section->id ?? 1)->get();
    }
}
