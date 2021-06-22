<?php

namespace App\Models;

use App\Enum\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lab404\Impersonate\Models\Impersonate;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $role
 * @property string|null $pay
 * @property string $timezone
 * @property bool $master
 * @property string|null $photo_url
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int $kw_achived
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $office_id
 * @property int|null $department_id
 * @property int $installs
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\DailyNumber[] $dailyNumbers
 * @property-read string $full_name
 * @property-read \App\Models\Department|null $department
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invitation[] $invitations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Office[] $managedOffices
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Region[] $managedRegions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Department[] $managedDepartments
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\Office|null $office
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Office[] $officesOnManagedRegions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $usersOnManagedOffices
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User masters()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User search($search)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use Impersonate;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'role',
        'pay',
        'timezone',
        'master',
        'photo_url',
        'remember_token',
        'kw_achived',
        'office_id',
        'department_id',
        'install',
        'department_manager_id',
        'office_manager_id',
        'region_manager_id',
    ];

    const ROLES = [
        [
            'title'       => 'Owner',
            'name'        => 'Owner',
            'description' => 'System Owner',
        ],
        [
            'title'       => 'Admin',
            'name'        => 'Admin',
            'description' => 'Allows access to the Admin functionality and Manage Users, Incentives and others (Admin Tab)',
        ],
        [
            'title'       => 'VP',
            'name'        => 'Department Manager',
            'description' => 'Allows access to Manage Users, Incentives and others',
        ],
        [
            'title'       => 'Regional Manager',
            'name'        => 'Region Manager',
            'description' => "Allows update all Region's Number Tracker",
        ],
        [
            'title'       => 'Manager',
            'name'        => 'Office Manager',
            'description' => "Allows update a Region's Number Tracker",
        ],
        [
            'title'       => 'Sales Rep',
            'name'        => 'Sales Rep',
            'description' => 'Allows read/add/edit/cancel Customer',
        ],
        [
            'title'       => 'Setter',
            'name'        => 'Setter',
            'description' => 'Allows see the dashboard and only read Customer',
        ],
    ];

    const TOPLEVEL_ROLES = [
        'Owner',
        'Admin',
        'Department Manager',
        'Region Manager',
        'Office Manager',
        'Sales Rep',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'master'            => 'boolean',
    ];

    protected $appends = [
        'full_name',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function managedOffices()
    {
        return $this->belongsToMany(Office::class, 'user_managed_offices')->withTimestamps();
    }

    public function managedRegions()
    {
        return $this->belongsToMany(Region::class, 'user_managed_regions')->withTimestamps();
    }

    public function managedDepartments()
    {
        return $this->belongsToMany(Department::class, 'user_managed_departments')->withTimestamps();
    }

    public function officesOnManagedRegions()
    {
        return $this->hasManyThrough(Office::class, Region::class, 'region_manager_id', 'region_id');
    }

    public function usersOnManagedOffices()
    {
        return $this->hasManyThrough(User::class, Office::class, 'office_manager_id', 'office_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function recruitedBy()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function recruiteds()
    {
        return $this->hasMany(User::class, 'recruiter_id');
    }

    public function officeManager()
    {
        return $this->belongsTo(User::class, 'office_manager_id');
    }

    public function usersManagedOffice()
    {
        return $this->hasMany(User::class, 'office_manager_id');
    }

    public function regionManager()
    {
        return $this->belongsTo(User::class, 'region_manager_id');
    }

    public function usersManagedRegion()
    {
        return $this->hasMany(User::class, 'region_manager_id');
    }

    public function departmentManager()
    {
        return $this->belongsTo(User::class, 'department_manager_id');
    }

    public function usersManagedDepartment()
    {
        return $this->hasMany(User::class, 'department_manager_id');
    }

    public function customersOfSetter()
    {
        return $this->hasMany(Customer::class, 'setter_id');
    }

    public function customersOfSalesReps()
    {
        return $this->hasMany(Customer::class, 'sales_rep_id');
    }

    public function customersOfSalesRepsRecruited()
    {
        return $this->hasMany(Customer::class, 'sales_rep_recruiter_id');
    }

    public function customersManagedOffice()
    {
        return $this->hasMany(Customer::class, 'office_manager_id');
    }

    public function customersManagedRegion()
    {
        return $this->hasMany(Customer::class, 'region_manager_id');
    }

    public function customersOfSalesRepsRecuited()
    {
        return $this->hasMany(Customer::class, 'sales_rep_recruiter_id');
    }

    public function customersDepartmentManager()
    {
        return $this->hasMany(Customer::class, 'department_manager_id');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function dailyNumbers()
    {
        return $this->hasMany(DailyNumber::class);
    }

    public function customersEniumPoints()
    {
        return $this->hasMany(UserCustomersEniumPoints::class, 'user_sales_rep_id');
    }

    public function level()
    {
        $eniumPoints = $this->eniumPoints();

        return UserEniumPointLevel::where('point', '>=', $eniumPoints)->first() ?? UserEniumPointLevel::find(UserEniumPointLevel::LAST_LEVEL);
    }

    public function eniumPoints()
    {
        return $this->customersEniumPoints()->whereHas('customer', function ($query) {
            $query->where('is_active', true)
                ->where('panel_sold', true);
        })
            ->inPeriod()
            ->sum('points');
    }

    public function changePassword($new)
    {
        $this->password = Hash::make($new);

        return $this;
    }

    public function isMaster()
    {
        return $this->master;
    }

    public function userLevel()
    {
        return $this->role;
    }

    public function getDepartment()
    {
        return $this->office->region->department;
    }

    public function beCastleMaster()
    {
        $this->forceFill(['master' => true]);
        $this->save();
    }

    public function revokeMastersAccess()
    {
        $this->forceFill(['master' => false]);
        $this->save();
    }

    public function canImpersonate()
    {
        return $this->hasRole('Admin');
    }

    public function getFullNameAttribute()
    {
        return sprintf('%s %s', $this->first_name, $this->last_name);
    }

    public function getPermittedUsers($departmentId = null)
    {
        if ($this->hasAnyRole([Role::ADMIN, Role::OWNER])) {
            return User::has('office')->whereDepartmentId($departmentId)->orderBy('first_name')->get();
        }

        if ($this->hasRole(Role::DEPARTMENT_MANAGER)) {
            return User::has('office')
                ->whereDepartmentId($this->department_id)
                ->where(function ($query) {
                    return $query->orWhere('users.id', $this->id)
                        ->orWhere('role', 'Region Manager')
                        ->orWhere('role', 'Office Manager')
                        ->orWhere('role', 'Sales Rep')
                        ->orWhere('role', 'Setter');
                })->orderBy('first_name')->get();
        }

        if ($this->hasRole(Role::REGION_MANAGER)) {
            $offices = Office::whereIn('region_id', $this->managedRegions->pluck('id'))->with('users')->get();
            $users   = $offices->reduce(function ($users, Office $office) {
                return $users->mergeRecursive($office->users);
            }, $users = collect([]))->unique('id');

            return $users->sortBy('first_name');
        }

        if ($this->hasRole(Role::OFFICE_MANAGER)) {
            return self::whereIn('office_id', $this->managedOffices->pluck('id'))->get();
        }

        return collect([user()]);
    }

    public static function getRolesPerUserRole()
    {
        if (user()->hasRole(Role::OWNER)) {
            return self::ROLES;
        }

        $roles = [
            [
                'title'       => 'Sales Rep',
                'name'        => 'Sales Rep',
                'description' => 'Allows read/add/edit/cancel Customer',
            ],
            [
                'title'       => 'Setter',
                'name'        => 'Setter',
                'description' => 'Allows see the dashboard and only read Customer',
            ],
        ];

        if (user()->hasRole(Role::ADMIN)) {
            $roles = array_merge($roles, [
                [
                    'title'       => 'Manager',
                    'name'        => 'Office Manager',
                    'description' => "Allows update a Region's Number Tracker",
                ],
                [
                    'title'       => 'Regional Manager',
                    'name'        => 'Region Manager',
                    'description' => "Allows update all Region's Number Tracker",
                ],
                [
                    'title'       => 'VP',
                    'name'        => 'Department Manager',
                    'description' => "Allows update all in departments and Region's Number Tracker",
                ],
                [
                    'title'       => 'Admin',
                    'name'        => 'Admin',
                    'description' => 'Allows access to the Admin functionality and Manage Users, Incentives and others (Admin Tab)',
                ],
            ]);
        }

        if (user()->hasRole(Role::DEPARTMENT_MANAGER)) {
            $roles = array_merge($roles, [
                [
                    'title'       => 'Manager',
                    'name'        => 'Office Manager',
                    'description' => "Allows update a Region's Number Tracker",
                ],
                [
                    'title'       => 'Regional Manager',
                    'name'        => 'Region Manager',
                    'description' => "Allows update all Region's Number Tracker",
                ],
            ]);
        }

        if (user()->hasRole(Role::REGION_MANAGER)) {
            $roles = array_merge($roles, [
                [
                    'title'       => 'Manager',
                    'name'        => 'Office Manager',
                    'description' => "Allows update a Region's Number Tracker",
                ],
            ]);
        }

        return array_reverse($roles);
    }

    public static function userManageOffices(User $user)
    {
        return $user->managedOffices()->count() > 0
            ? $user->managedOffices
            : false;
    }

    public static function userManageRegion(User $user)
    {
        return $user->managedRegions()->count() > 0
            ? $user->managedRegions
            : false;
    }

    public static function userManageDepartment(User $user)
    {
        return $user->managedDepartments()->count() > 0
            ? $user->managedDepartments
            : false;
    }

    public static function userCanChangeRole(User $user): array
    {
        $response = [
            'status'  => true,
            'message' => '',
        ];
        $previous = 'This user is the Manager for the';

        if ($offices = User::userManageOffices($user)) {
            $response['status']  = false;
            $previous            .= ' Offices:';
            $previous            = User::getChangeRoleMessage($previous, $offices);
            $response['message'] = $previous;
        }

        if ($regions = User::userManageRegion($user)) {
            $response['status']  = false;
            $previous            .= ' Regions:';
            $previous            = User::getChangeRoleMessage($previous, $regions);
            $response['message'] = $previous;
        }

        if ($departments = User::userManageDepartment($user)) {
            $response['status']  = false;
            $previous            .= ' Departments:';
            $previous            = User::getChangeRoleMessage($previous, $departments);
            $response['message'] = $previous;
        }

        return $response;
    }

    protected static function getChangeRoleMessage(string $previous, Collection $content): string
    {
        return sprintf('%s %s', $previous, $content->implode('name', ', '));

    }

    public function getPhoneNumberAttribute($value)
    {
        if ($value) {
            $cleaned = preg_replace('/[^[:digit:]]/', '', $value);
            preg_match('/(\d{1,5})(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
            if ($matches) {
                return "+{$matches[1]} ({$matches[2]}) {$matches[3]}-{$matches[4]}";
            }

            return $cleaned;
        }
    }

    public function scopeMasters(Builder $query)
    {
        return $query->where('master', true);
    }

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $query) use ($search) {
            $query->where(
                DB::raw('lower(first_name)'),
                'like',
                '%' . strtolower($search) . '%'
            )
                ->orWhere(
                    DB::raw('lower(last_name)'),
                    'like',
                    '%' . strtolower($search) . '%'
                )
                ->orWhere(
                    DB::raw('lower(role)'),
                    'like',
                    '%' . strtolower($search) . '%'
                )
                ->orWhere(
                    DB::raw('lower(email)'),
                    'like',
                    '%' . strtolower($search) . '%'
                );
        });
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function stockPoints()
    {
        $stockPointsOfSalesRep          = $this->getStockPointsOf($this->customersOfSalesReps());
        $stockPointsOfSetter            = $this->getStockPointsOf($this->customersOfSetter());
        $stockPointsOfSalesRepRecruited = $this->getStockPointsOf($this->customersOfSalesRepsRecuited());
        $stockPointsOfDepartment        = $this->getStockPointsOf($this->customersDepartmentManager());
        $stockPointsOfRegionManager     = $this->getStockPointsOf($this->customersManagedRegion());
        $stockPointsOfOfficeManager     = $this->getStockPointsOf($this->customersManagedOffice());

        return (object)[
            'multiplierOfYear'                                                       => MultiplierOfYear::where('year', Carbon::now()->year)->first()->multiplier,
            'personal'                                                               => $stockPointsOfSalesRep->sum(fn($customer)                                                       => $customer->stockPoint->stock_personal_sale),
            'team'                                                                   => $stockPointsOfSetter->sum(fn($customer)                                                       => $customer->stockPoint->stock_setting) +
                                  $stockPointsOfSalesRepRecruited->sum(fn($customer) => $customer->stockPoint->stock_recruiter) +
                                  $stockPointsOfDepartment->sum(fn($customer)        => $customer->stockPoint->stock_department) +
                                  $stockPointsOfRegionManager->sum(fn($customer)     => $customer->stockPoint->stock_regional) +
                                  $stockPointsOfOfficeManager->sum(fn($customer)     => $customer->stockPoint->stock_manager),
        ];
    }

    public function getStockPointsOf ($query)
    {
        return $query->whereHas('stockPoint', function ($query) {
            $query->whereYear('created_at', Carbon::now());
        })->with('stockPoint')->get();
    }

    public function hasAnyRole(array $roles): bool
    {
        return collect($roles)->some(fn ($role) => $role === $this->role);
    }

    public function notHaveRoles(array $roles): bool
    {
        return collect($roles)->every(fn ($role) => $role !== $this->role);
    }

    public static function getRoleByNames()
    {
        return collect(self::ROLES)
            ->map(fn ($role) => $role['name'])
            ->toArray();
    }
}
