<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;
    
    protected $table = 'roles'; // Explicitly set the table name
    protected $fillable = ['name', 'description'];
    
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}