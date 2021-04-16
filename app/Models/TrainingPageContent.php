<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TrainingPageContent
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $training_page_section_id
 * @property string $video_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\TrainingPageSection $section
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TrainingPageContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TrainingPageContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TrainingPageContent query()
 * @mixin \Eloquent
 */
class TrainingPageContent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function section()
    {
        return $this->belongsTo(TrainingPageSection::class, 'training_page_section_id');
    }

    public function getDecodedDescriptionAttribute()
    {
        if (!$this->description) {
            return '';
        }

        $description = json_decode($this->description);

        return strip_tags($description->ops[0]->insert);
    }
}
