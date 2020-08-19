<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;

    const ROLES = [
        ['name' => 'Owner',          'description' => 'System Owner'],
        ['name' => 'Admin',          'description' => 'Allows access to the Admin functionality and Manage Users, Incentives and others (Admin Tab)'],
        ['name' => 'Region Manager', 'description' => 'Allows update all Regon\'s Number Traker'],
        ['name' => 'Office Manager', 'description' => 'Allows update a Region\'s Number Tracker'],
        ['name' => 'Sales Rep',      'description' => 'Allows read/add/edit/cancel Customer'],
        ['name' => 'Setter',         'description' => 'Allows see the dashboard and only read Customer'],
    ];

    const TOPLEVEL_ROLES = [
        'Owner',
        'Admin',
        'Region Manager',
        'Office Manager',
        'Sales Rep',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'master'            => 'boolean',
    ];

    public function regions()
    {
        return $this->belongsToMany(Region::class)->withPivot('role')->withTimestamps();
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
            )
            ->orWhere(
                DB::raw('lower(office)'),
                'like',
                '%' . strtolower($search) . '%'
            );
        });
    }
}
