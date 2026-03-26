<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisi';
    protected $primaryKey = 'id_divisi';
    public $timestamps = false;

    protected $fillable = [
        'nama_divisi',
        'deskripsi',
        'dibuat_pada',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_divisi', 'id_divisi');
    }

    public function logTurnitin()
    {
        return $this->hasMany(LogTurnitin::class, 'id_divisi', 'id_divisi');
    }
}
