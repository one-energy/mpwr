<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $training_page_section_id
 * @property string $title
 * @property string $video_url
 * @property string $descriptrion
 * @property-read TrainingPageSection $training_page_section_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TrainingPageContent extends Model
{
    protected $guarded = [];
  
    public function section()
    {
        return $this->belongsTo(TrainingPageSection::class, 'training_page_section_id');
    }
}
