<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property string $role
 * @property string $timezone
 * @property string $photo_url
 * @property string $remember_token
 * @property string $master
 * @property int $office_id
 * @property int $department_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Department $department
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;

    const ROLES = [
        ['title' => 'Owner',            'name' => 'Owner',              'description' => 'System Owner'],
        ['title' => 'Admin',            'name' => 'Admin',              'description' => 'Allows access to the Admin functionality and Manage Users, Incentives and others (Admin Tab)'],
        ['title' => 'VP',               'name' => 'Department Manager', 'description' => 'Allows access to Manage Users, Incentives and others'],
        ['title' => 'Regional Manager', 'name' => 'Region Manager',     'description' => 'Allows update all Region\'s Number Tracker'],
        ['title' => 'Manager',          'name' => 'Office Manager',     'description' => 'Allows update a Region\'s Number Tracker'],
        ['title' => 'Sales Rep',        'name' => 'Sales Rep',          'description' => 'Allows read/add/edit/cancel Customer'],
        ['title' => 'Setter',           'name' => 'Setter',             'description' => 'Allows see the dashboard and only read Customer'],
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

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
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
}
