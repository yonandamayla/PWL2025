<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'm_user'; //mendefinisikan nama tabel yg digunakan oleh model ini
    protected $primaryKey = 'user_id'; //mendefiniskan pk dari tabel yg digunakan 

    // protected $fillable = ['level_id', 'username', 'nama', 'password',]; //mendefinisikan kolom yg bisa diisi oleh user
    protected $fillable = ['level_id', 'username', 'nama', 'password'];
}
