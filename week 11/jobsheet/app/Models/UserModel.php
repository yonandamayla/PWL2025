<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class UserModel extends Authenticatable implements JWTSubject
{
    public function getJWTIdentifier(): string|int
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    use HasFactory;
    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['username', 'nama', 'password', 'level_id', 'created_at', 'updated_at', 'foto_profile'];
    protected $hidden = ['password']; // Jangan ditampilkan saat select
    protected $casts = ['password' => 'hashed']; // Casting password agar otomatis di-hash

    /**
     * Relasi ke tabel level
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    /**
     * Mendapatkan nama role
     */
    public function getRoleName() : string
    {
        return $this->level->level_nama;
    }

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole(string $role) : bool
    {
        return $this->level->level_kode === $role;
    }

    /**
     * Mendapatkan kode role
     */
    public function getRole() : string
    {
        return $this->level->level_kode;
    }
}