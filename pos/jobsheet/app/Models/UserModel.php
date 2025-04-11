<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id');
    }

    public function orders()
    {
        return $this->hasMany(OrderModel::class, 'user_id');
    }

    public function findForLogin($username)
    {
        // If the username contains @, treat it as a full email
        if (strpos($username, '@') !== false) {
            return $this->where('email', $username)->first();
        }

        // Otherwise, append your domain
        return $this->where('email', $username . '@example.com')->first();
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        if (is_numeric($role)) {
            return $this->role_id === (int)$role;
        }

        $roles = [
            'admin' => 1,
            'kasir' => 2,
            'customer' => 3
        ];

        return $this->role_id === ($roles[strtolower($role)] ?? 0);
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles)
    {
        if (!is_array($roles)) {
            $roles = explode(',', $roles);
        }

        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get user role name
     */
    public function getRoleName()
    {
        $roles = [
            1 => 'admin',
            2 => 'kasir',
            3 => 'customer'
        ];

        return ucfirst($roles[$this->role_id] ?? 'unknown');
    }
}
