<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Financer
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Financer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Financer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Financer query()
 * @mixin \Eloquent
 */
class Financer extends Model
{
    use HasFactory;

    const ENIUM = 1;
    const OTHER = 2;
}
