<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SectionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'original_name',
        'type',
        'size',
        'path'
    ];

    public function trainingPageSection()
    {
        return $this->belongsTo(TrainingPageSection::class);
    }
}
