<?php

namespace App\Http\Controllers;

use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;

class TrainingController extends Controller
{
    public function index(TrainingPageSection $section = null)
    {
        $videoId = null;
        $content = $this->getContent($section);
        if($content){
            $videoId = explode('v=', $content->video_url);
        }
        // dd($videoId);
        return view('training.index', [
            'sections'      => $this->getParentSections($section),
            'content'       => $content,
            'videoId'       => $videoId[1] ?? null,
            'actualSection' => $section ?? TrainingPageSection::whereId(1)->first()
        ]);
    }

    public function getContent($section)
    {
        return TrainingPageContent::whereTrainingPageSectionId($section->id ?? 1)->first();
    }

    public function getParentSections($section)
    {
        return TrainingPageSection::whereParentId($section->id ?? 1)->get();
    }
}
