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

    public function storeSection(TrainingPageSection $section)
    {
        $validated = $this->validate(
            request(),
            [
                'title'     => 'required|string|min:5|max:255',
            ],
        );        
        $trainingPageSection = new TrainingPageSection();
        $trainingPageSection->title = $validated['title'];
        $trainingPageSection->parent_id = $section->id;

        $trainingPageSection->save();

        alert()
            ->withTitle(__('Section created!'))
            ->send();

        return redirect(route('trainings.index', $section));
    }

    public function storeContent(TrainingPageSection $section)
    {
        $validated = $this->validate(
            request(),
            [
                'title'           => 'required|string|min:5|max:255',
                'video_url'       => 'required|string|min:5|max:255',
                'description'     => 'required|string',
            ],
        );    

        $trainingPageContent = new TrainingPageContent();
        $trainingPageContent->title       = $validated['title'];
        $trainingPageContent->description = $validated['description'];
        $trainingPageContent->video_url   = $validated['video_url'];
        $trainingPageContent->training_page_section_id   = $section->id;

        $trainingPageContent->save();

        alert()
            ->withTitle(__('Content created!'))
            ->send();

        return redirect(route('trainings.index', $section));
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
