<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;

    protected $table = 'tema';
    protected $primaryKey = 'id_tema';
    public $timestamps = false;

    protected $fillable = [
        'id_rumpun',
        'kode_tema',
        'nama_tema',
        'deskripsi',
        'dibuat_pada',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function rumpun()
    {
        return $this->belongsTo(RumpunIlmu::class, 'id_rumpun', 'id_rumpun');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_tema', 'id_tema');
    }
}
