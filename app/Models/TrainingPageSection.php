<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $query) use ($search) {
            $query->where(
                DB::raw('lower(trining_page_sections.title)'),
                'like',
                '%' . strtolower($search) . '%'
            );
        });
    }
}
