<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPengguna extends Model
{
    use HasFactory;

    protected $table = 'user_profile';
    protected $primaryKey = 'id_profile';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'foto_profil',
        'updated_at',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
