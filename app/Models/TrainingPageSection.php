<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $parent_id
 * @property int $training_page_section_id
 * @property string $title
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TrainingPageSection extends Model
{
    //
    public function content()
    {
        return $this->hasOne(TrainingPageContent::class);
    }
}
