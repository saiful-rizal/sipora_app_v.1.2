<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPengguna extends Model
{
    use HasFactory;

    protected $table = 'profil_pengguna';
    protected $primaryKey = 'id_profil';
    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'foto_profil',
        'tentang_saya',
        'diperbarui_pada',
    ];

    protected $casts = [
        'diperbarui_pada' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
