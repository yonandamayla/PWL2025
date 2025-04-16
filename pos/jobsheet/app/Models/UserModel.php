<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'profile_picture',
        'phone',
        'address',
        'settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the display name for the user's role
     *
     * @return string
     */
    public function getRoleName()
    {
        switch ($this->role_id) {
            case 1:
                return 'Admin';
            case 2:
                return 'Kasir';
            case 3:
                return 'Customer';
            default:
                return 'Unknown';
        }
    }

    /**
     * Get all orders belonging to this user
     */
    public function orders()
    {
        return $this->hasMany(OrderModel::class);
    }

    /**
     * Check if user has admin role
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has cashier role
     *
     * @return bool
     */
    public function isCashier()
    {
        return $this->role === 'cashier';
    }

    /**
     * Check if user has customer role
     *
     * @return bool
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    /**
     * Get the role associated with this user
     */
    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id');
    }
}
