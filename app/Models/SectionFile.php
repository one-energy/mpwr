<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string|null $training_type
 * @property \App\Models\TrainingPageSection|null $training_page_section_id
 */
class SectionFile extends Model
{
    public const G_SIZE = 1000000000;
    public const M_SIZE = 1000000;
    public const K_SIZE = 1000;

    use HasFactory;

    protected $fillable = [
        'name',
        'original_name',
        'type',
        'size',
        'path',
        'training_type',
        'training_page_section_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function trainingPageSection()
    {
        return $this->belongsTo(TrainingPageSection::class);
    }

    public function getAbbreviatedSizeAttribute()
    {
        if ($this->size <= self::K_SIZE) {
            return $this->size . ' B';
        }

        if ($this->size <= self::M_SIZE) {
            return number_format(($this->size / self::K_SIZE), 2) . ' KB';
        }

        if ($this->size <= self::G_SIZE) {
            return number_format(($this->size / self::M_SIZE), 2) . ' MB';
        }

        return number_format(($this->size / self::G_SIZE), 2) . ' GB';
    }
}
