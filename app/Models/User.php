<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
 * @property-read \App\Models\Department|null $department
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invitation[] $invitations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Office[] $managedOffices
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Region[] $managedRegions
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
    ];

    const ROLES = [
        ['title' => 'Owner', 'name' => 'Owner', 'description' => 'System Owner'],
        ['title' => 'Admin', 'name' => 'Admin', 'description' => 'Allows access to the Admin functionality and Manage Users, Incentives and others (Admin Tab)'],
        ['title' => 'VP', 'name' => 'Department Manager', 'description' => 'Allows access to Manage Users, Incentives and others'],
        ['title' => 'Regional Manager', 'name' => 'Region Manager', 'description' => 'Allows update all Region\'s Number Tracker'],
        ['title' => 'Manager', 'name' => 'Office Manager', 'description' => 'Allows update a Region\'s Number Tracker'],
        ['title' => 'Sales Rep', 'name' => 'Sales Rep', 'description' => 'Allows read/add/edit/cancel Customer'],
        ['title' => 'Setter', 'name' => 'Setter', 'description' => 'Allows see the dashboard and only read Customer'],
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
        'full_name'
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function managedOffices()
    {
        return $this->hasMany(Office::class, 'office_manager_id');
    }

    public function managedRegions()
    {
        return $this->hasMany(Region::class, 'region_manager_id');
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

    public function customersOfSalesRepsRecuited()
    {
        return $this->hasMany(Customer::class, 'sales_rep_recruiter_id');
    }

    public function customersManagedRegion()
    {
        return $this->hasMany(Customer::class, 'region_manager_id');
    }

    public function customersDepartmentManager()
    {
        return $this->belongsTo(Customer::class, 'department_manager_id');
    }

    public function customersManagedDepartment()
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
        if ($this->role == 'Admin' || $this->role == 'Owner') {
            return User::has('office')->whereDepartmentId($departmentId)->orderBy('first_name')->get();
        }

        if ($this->role == 'Department Manager') {
            return User::has('office')
                ->whereDepartmentId($this->department_id)
                ->where(function($query) {
                    return $query->orWhere('users.id', $this->id)
                        ->orWhere('role', 'Region Manager')
                        ->orWhere('role', 'Office Manager')
                        ->orWhere('role', 'Sales Rep')
                        ->orWhere('role', 'Setter');
                })->orderBy('first_name')->get();
        }

        if ($this->role == 'Region Manager') {
            $offices = $this->officesOnManagedRegions()->with('users')->get();
            $users   = $offices->reduce(function($users, Office $office) {
                return $users->mergeRecursive($office->users);
            }, $users = collect([]))->unique('id');

            return $users->sortBy('first_name');
        }

        if ($this->role == 'Office Manager') {
            return $this->usersOnManagedOffices()->get();
        }

        return collect([user()]);
    }

    public static function getRolesPerUserRole()
    {
        if (user()->role == 'Admin') {
            $roles = [
                ['title' => 'Admin', 'name' => 'Admin', 'description' => 'Allows access to the Admin functionality and Manage Users, Incentives and others (Admin Tab)'],
                ['title' => 'VP', 'name' => 'Department Manager', 'description' => 'Allows update all in departments and Region\'s Number Tracker'],
                ['title' => 'Regional Manager', 'name' => 'Region Manager', 'description' => 'Allows update all Region\'s Number Tracker'],
                ['title' => 'Manager', 'name' => 'Office Manager', 'description' => 'Allows update a Region\'s Number Tracker'],
                ['title' => 'Sales Rep', 'name' => 'Sales Rep', 'description' => 'Allows read/add/edit/cancel Customer'],
                ['title' => 'Setter', 'name' => 'Setter', 'description' => 'Allows see the dashboard and only read Customer'],
            ];
        }
        if (user()->role == 'Department Manager') {
            $roles = [
                ['title' => 'Regional Manager', 'name' => 'Region Manager', 'description' => 'Allows update all Region\'s Number Tracker'],
                ['title' => 'Manager', 'name' => 'Office Manager', 'description' => 'Allows update a Region\'s Number Tracker'],
                ['title' => 'Sales Rep', 'name' => 'Sales Rep', 'description' => 'Allows read/add/edit/cancel Customer'],
                ['title' => 'Setter', 'name' => 'Setter', 'description' => 'Allows see the dashboard and only read Customer'],
            ];
        }
        if (user()->role == 'Region Manager') {
            $roles = [
                ['title' => 'Manager', 'name' => 'Office Manager', 'description' => 'Allows update a Region\'s Number Tracker'],
                ['title' => 'Sales Rep', 'name' => 'Sales Rep', 'description' => 'Allows read/add/edit/cancel Customer'],
                ['title' => 'Setter', 'name' => 'Setter', 'description' => 'Allows see the dashboard and only read Customer'],
            ];
        }
        if (user()->role == 'Office Manager') {
            $roles = [
                ['title' => 'Sales Rep', 'name' => 'Sales Rep', 'description' => 'Allows read/add/edit/cancel Customer'],
                ['title' => 'Setter', 'name' => 'Setter', 'description' => 'Allows see the dashboard and only read Customer'],
            ];
        }

        if (user()->role == 'Owner') {
            $roles = User::ROLES;
        }

        return $roles;
    }

    public static function userManageOffices(User $user)
    {
        $offices = Office::whereOfficeManagerId($user->id)->get();

        return count($offices) > 0 ? $offices : false;
    }

    public static function userManageRegion(User $user)
    {
        $regions = Region::whereRegionManagerId($user->id)->get();

        return count($regions) > 0 ? $regions : false;
    }

    public static function userManageDepartment(User $user)
    {
        $departments = Department::whereDepartmentManagerId($user->id)->get();

        return count($departments) > 0 ? $departments : false;
    }

    public static function userCanChangeRole(User $user): array
    {
        $response = [
            'status'  => true,
            'message' => '',
        ];
        $previous = 'This user is the manager for the';

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
        $message = $previous . ' ' . $content->implode('name', ', ');

        return $message . '. Please disassociate the user from what was mentioned before continuing.';
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
