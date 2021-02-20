<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property int $number_installs
 * @property int $installs_achieved
 * @property int $installs_needed
 * @property int $kw_achieved
 * @property int $kw_needed
 */
class Incentive extends Model
{
    use SoftDeletes;

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
