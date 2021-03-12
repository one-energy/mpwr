<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Incentive
 *
 * @property int $id
 * @property int $number_installs
 * @property string $name
 * @property int $installs_needed
 * @property int $kw_needed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $department_id
 * @property-read \App\Models\Department|null $department
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Incentive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Incentive newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Incentive onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Incentive query()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Incentive withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Incentive withoutTrashed()
 * @mixin \Eloquent
 */
class Incentive extends Model
{
    use SoftDeletes;

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
