<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\TrainingPageSection
 *
 * @property int $id
 * @property string $title
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $department_id
 * @property int|null $region_id
 * @property bool|null $department_folder
 * @property-read \App\Models\TrainingPageContent|null $contents
 * @property-read \App\Models\Department|null $department
 * @property-read \App\Models\TrainingPageSection|null $parent
 * @property-read \App\Models\Region|null $region
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TrainingPageSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TrainingPageSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TrainingPageSection query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TrainingPageSection search($search)
 * @mixin \Eloquent
 */
class TrainingPageSection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'parent_id',
        'department_id',
        'region_id',
        'department_folder',
    ];

    protected $casts = [
        'department_folder' => 'boolean',
    ];

    public function contents()
    {
        return $this->hasMany(TrainingPageContent::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function parent()
    {
        return $this->belongsTo(TrainingPageSection::class);
    }

    public function files()
    {
        return $this->hasMany(SectionFile::class, 'training_page_section_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
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

    public function isDepartmentSection()
    {
        return $this->department_folder;
    }
}
