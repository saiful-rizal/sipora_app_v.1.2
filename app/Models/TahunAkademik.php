<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $table = 'tahun_akademik';
    protected $primaryKey = 'id_tahun';
    public $timestamps = false;

    protected $fillable = [
        'tahun',
        'semester',
        'aktif',
        'dibuat_pada',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'dibuat_pada' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_tahun', 'id_tahun');
    }
}
