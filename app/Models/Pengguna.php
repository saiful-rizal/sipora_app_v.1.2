<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nama_lengkap',
        'nim',
        'email',
        'username',
        'password_hash',
        'role',
        'status',
        'created_at',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Accessor untuk kompatibilitas auth
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getAuthIdentifierName()
    {
        return $this->primaryKey;
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value): void
    {
    }

    public function getRememberTokenName()
    {
        return null;
    }

    // ===== RELATIONSHIPS =====

    public function profil()
    {
        return $this->hasOne(ProfilPengguna::class, 'id_user', 'id_user');
    }

    public function dokumenDiunggah()
    {
        return $this->hasMany(Dokumen::class, 'uploader_id', 'id_user');
    }

    public function dokumenDikaji()
    {
        return $this->hasMany(LogKajian::class, 'reviewer_id', 'id_user');
    }

    public function logKajian()
    {
        return $this->hasMany(LogKajian::class, 'reviewer_id', 'id_user');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'user_id', 'id_user');
    }

    public function riwayatPencarian()
    {
        return $this->hasMany(RiwayatPencarian::class, 'user_id', 'id_user');
    }

    public function logTurnitin()
    {
        return $this->hasMany(LogTurnitin::class, 'uploader_id', 'id_user');
    }
}
