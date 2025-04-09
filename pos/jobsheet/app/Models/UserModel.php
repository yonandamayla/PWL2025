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

    // Add this method to UserModel.php
    public function findForLogin($username)
    {
        // If the username contains @, treat it as a full email
        if (strpos($username, '@') !== false) {
            return $this->where('email', $username)->first();
        }

        // Otherwise, append your domain
        return $this->where('email', $username . '@example.com')->first();
    }
}
