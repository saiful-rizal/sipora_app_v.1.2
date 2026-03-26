<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatPencarian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'riwayat_pencarian';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'kata_kunci',
        'dibuat_pada',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
        'dihapus_pada' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
