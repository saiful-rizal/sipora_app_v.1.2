<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable, SoftDeletes;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    public $timestamps = false;

    protected $fillable = [
        'nama_lengkap',
        'nim',
        'email',
        'username',
        'password_hash',
        'peran',
        'status',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'dihapus_pada' => 'datetime',
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

    // ===== RELATIONSHIPS =====

    public function profil()
    {
        return $this->hasOne(ProfilPengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function dokumenDiunggah()
    {
        return $this->hasMany(Dokumen::class, 'id_pengguna_unggah', 'id_pengguna');
    }

    public function dokumenDikaji()
    {
        return $this->hasMany(Dokumen::class, 'id_pengguna_reviewer', 'id_pengguna');
    }

    public function logKajian()
    {
        return $this->hasMany(LogKajian::class, 'id_pengguna_kajian', 'id_pengguna');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_pengguna', 'id_pengguna');
    }

    public function riwayatPencarian()
    {
        return $this->hasMany(RiwayatPencarian::class, 'id_pengguna', 'id_pengguna');
    }

    public function logTurnitin()
    {
        return $this->hasMany(LogTurnitin::class, 'id_pengguna_unggah', 'id_pengguna');
    }
}
