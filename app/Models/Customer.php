<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Customer
 *
 * @property int $id
 * @property int|null $financing_id
 * @property int|null $financer_id
 * @property int|null $term_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $system_size
 * @property string $bill
 * @property string|null $pay
 * @property \App\Models\Financing|null $financing
 * @property string|null $adders
 * @property string|null $epc
 * @property float|null $commission
 * @property string|null $setter_fee
 * @property bool $is_active
 * @property bool $panel_sold
 * @property int|null $setter_id
 * @property int $opened_by_id
 * @property float $margin
 * @property \datetime $date_of_sale
 * @property int $sales_rep_comission
 * @property int|null $enium_points
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $rate_id
 * @property string|null $sales_rep_fee
 * @property int|null $sales_rep_id
 * @property-read \App\Models\Financer|null $financer
 * @property-read mixed $opened_by
 * @property-read mixed $setter
 * @property-read \App\Models\Term|null $term
 * @property-read \App\Models\User $userOpenedBy
 * @property-read \App\Models\User|null $userSalesRep
 * @property-read \App\Models\User|null $userSetter
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer installed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer query()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Customer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Customer withoutTrashed()
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'bill', 'financing_id', 'financer_id', 'term_id', 'opened_by_id', 'system_size', 'adders', 'epc', 'setter_id', 'setter_fee', 'sales_rep_id', 'sales_rep_fee', 'sales_rep_comission', 'commission', 'created_at', 'updated_at', 'is_active'];

    protected $casts = [
        'panel_sold'   => 'boolean',
        'is_active'    => 'boolean',
        'date_of_sale' => 'datetime:Y-m-d',
    ];

    const RANGE_DATES = [
        ['title' => 'Today', 'value' => 'today'],
        ['title' => 'Week to Date', 'value' => 'week_to_date'],
        ['title' => 'Last Week', 'value' => 'last_week'],
        ['title' => 'Month to Date', 'value' => 'month_to_date'],
        ['title' => 'Last Month', 'value' => 'last_month'],
        ['title' => 'Quarter to Date', 'value' => 'quarter_to_date'],
        ['title' => 'Last Quarter', 'value' => 'last_quarter'],
        ['title' => 'Year to Date', 'value' => 'year_to_date'],
        ['title' => 'Last Year', 'value' => 'last_year'],
        ['title' => 'Custom', 'value' => 'custom'],
    ];

    const STATUS = [
        'pending'   => 'Pending Customers',
        'installed' => 'Installed Customers',
        'canceled'  => 'Canceled',
    ];

    const BILLS = [
        'Domestic',
        'CARE',
        'FERA',
    ];

    const FINANCINGS = [
        'Purchase',
        'PPA',
        'PACE',
    ];

    public function scopeInstalled($query)
    {
        return $query->where('opened_by_id', '=', user()->id)
            ->where('panel_sold', '=', true)
            ->where('is_active', '=', true);
    }

    public function userOpenedBy()
    {
        return $this->belongsTo(User::class, 'opened_by_id');
    }

    public function userSalesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    public function userSetter()
    {
        return $this->belongsTo(User::class, 'setter_id');
    }

    public function financer()
    {
        return $this->belongsTo(Financer::class);
    }

    public function financingType()
    {
        return $this->belongsTo(Financing::class, 'financing_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function recruiterOfSalesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_recruiter_id');
    }

    public function officeManager()
    {
        return $this->belongsTo(User::class, 'office_manager_id');
    }

    public function regionManager()
    {
        return $this->belongsTo(User::class, 'region_manager_id');
    }

    public function departmentManager()
    {
        return $this->belongsTo(User::class, 'department_manager_id');
    }

    public function userEniumPoint()
    {
        return $this->hasOne(UserCustomersEniumPoints::class);
    }

    public function getOpenedByAttribute()
    {
        return User::find($this->opened_by_id);
    }

    public function getSetterAttribute()
    {
        return User::find($this->setter_id);
    }

    public function calcComission()
    {
        if ($this->epc >= 0 && $this->sales_rep_fee >= 0 && $this->setter_fee >= 0 && $this->system_size && $this->adders >= 0) {
            // dd((float)((floatval($this->epc) - floatval($this->sales_rep_fee) - floatval($this->setter_fee)) * (intval($this->system_size) * 1000)) - floatval($this->adders));
            $this->sales_rep_comission = ((floatval($this->epc) - floatval($this->sales_rep_fee) - floatval($this->setter_fee)) * (floatval($this->system_size) * 1000)) - floatval($this->adders);
        } else {
            // dd('test');
            $this->sales_rep_comission = 0;
        }
    }

    public function calcMargin()
    {
        if ($this->epc) {
            $this->margin = floatval($this->epc) - floatval($this->setter_fee);
        } else {
            $this->margin = 0;
        }
    }

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhereRaw("CONCAT(customers.first_name, ' ', customers.last_name) LIKE ?", ['%' . $search . '%'])
                ->orWhereHas('userSetter', function ($query) use ($search) {
                    $query->whereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ['%' . $search . '%']);
                })
                ->orWhereHas('userSalesRep', function ($query) use ($search) {
                    $query->whereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ['%' . $search . '%']);
                })
                ->when(user()->role != 'Setter', function ($query) use ($search) {
                    $query->orWhereHas('financingType', function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('financer', function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->when(user()->role != 'Sales Rep', function ($query) use ($search) {
                        $query->orWhereHas('recruiterOfSalesRep', function ($query) use ($search) {
                            $query->whereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ['%' . $search . '%']);
                        })
                        ->orWhereHas('officeManager', function ($query) use ($search) {
                            $query->whereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ['%' . $search . '%']);
                        })
                        ->when(user()->role != 'Office Manager', function ($query) use ($search) {
                            $query->orWhereHas('regionManager', function ($query) use ($search) {
                                $query->whereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ['%' . $search . '%']);
                            })
                            ->when(user()->role != 'Region Manager', function ($query) use ($search) {
                                $query->orWhereHas('departmentManager', function ($query) use ($search) {
                                    $query->whereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ['%' . $search . '%']);
                                })
                                ->when(user()->role != 'Department Manager', function ($query) use ($search) {
                                    $query->orWhere('customers.payee_one', 'LIKE', '%' . $search . '%')
                                        ->orWhere('customers.payee_two', 'LIKE', '%' . $search . '%');
                                });
                            });
                        });
                    });
                });
            });
        });
    }
    public function scopeJoinInEachRelation(Builder $query)
    {
        return $query->select('customers.*')
            ->leftJoin('users as setter', function ($join) {
                $join->on('setter.id', '=', 'customers.setter_id');
            })
            ->leftJoin('users as salesRep', function ($join) {
                $join->on('salesRep.id', '=', 'customers.sales_rep_id');
            })
            ->leftJoin('users as manager', function ($join) {
                $join->on('manager.id', '=', 'customers.office_manager_id');
            })
            ->leftJoin('users as regionManager', function ($join) {
                $join->on('regionManager.id', '=', 'customers.region_manager_id');
            })
            ->leftJoin('users as departmentManager', function ($join) {
                $join->on('departmentManager.id', '=', 'customers.department_manager_id');
            })
            ->leftJoin('users as recruiter', function ($join) {
                $join->on('recruiter.id', '=', 'customers.sales_rep_recruiter_id');
            })
            ->leftJoin('terms', function ($join) {
                $join->on('terms.id', '=', 'term_id');
            })
            ->leftJoin('financers', function ($join) {
                $join->on('financers.id', '=', 'financer_id');
            })
            ->leftJoin('financings', function ($join) {
                $join->on('financings.id', '=', 'financing_id');
            });
    }
}
