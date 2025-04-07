<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $table = 'users'; // Explicitly set the table name
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'profile_picture',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id');
    }
    
    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(OrderModel::class, 'user_id');
    }
}