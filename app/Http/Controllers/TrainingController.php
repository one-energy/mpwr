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
        $actualSection = $section ?? TrainingPageSection::whereId(1)->first();
        
        if($content){
            $videoId = explode('v=', $content->video_url);
        }

        $path = $this->getPath($actualSection);

        return view('training.index', [
            'sections'      => $this->getParentSections($section),
            'content'       => $content,
            'videoId'       => $videoId[1] ?? null,
            'actualSection' => $actualSection,
            'path'          => $path
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

    public function updateSection(TrainingPageSection $section)
    {
        $validated = $this->validate(
            request(),
            [
                'title'     => 'required|string|min:5|max:255',
            ],
        );    

        $trainingPageContent = TrainingPageContent::query()->whereId($section->id)->first();
        $trainingPageContent->title = $validated['title'];

        $trainingPageContent->update();

        alert()
            ->withTitle(__('Section saved!'))
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

    public function updateContent(TrainingPageContent $content)
    {
        $validated = $this->validate(
            request(),
            [
                'title'           => 'required|string|min:5|max:255',
                'video_url'       => 'required|string|min:5|max:255',
                'description'     => 'required|string',
            ],
        );    

        $trainingPageContent = TrainingPageContent::query()->whereId($content->id)->first();
        $trainingPageContent->title = $validated['title'];
        $trainingPageContent->video_url = $validated['video_url'];
        $trainingPageContent->description = $validated['description'];

        $trainingPageContent->update();

        alert()
            ->withTitle(__('Content saved!'))
            ->send();

        return redirect(route('trainings.index', $content->training_page_section_id));
    }

    public function getPath($section)
    {
        $path = [$section];
        $trainingPageSection = $section;
        do {
            if($trainingPageSection->parent_id){
                $trainingPageSection = TrainingPageSection::query()->whereId($trainingPageSection->parent_id)->first();
                array_push($path, $trainingPageSection);
            }
        } while ($trainingPageSection->parent_id);
        
        return array_reverse($path);
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
