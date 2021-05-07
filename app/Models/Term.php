<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Term
 *
 * @property int $id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Term newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Term newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Term query()
 * @mixin \Eloquent
 */
class Term extends Model
{
    use HasFactory;

    const STANDARD_ONE = 4;
    const STANDARD_TWO = 3;
    const FORMONTH_ONE = 2;
    const FORMONTH_TWO = 1;

    protected $fillable = ['noble_pay_dealer_fee', 'amount', 'rep_residual', 'noble_pay_dealer_fee'];
    //
}
