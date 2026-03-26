<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTurnitin extends Model
{
    use HasFactory;

    protected $table = 'log_turnitin';
    protected $primaryKey = 'id_turnitin';
    public $timestamps = false;

    protected $fillable = [
        'id_divisi',
        'id_dokumen',
        'skor_turnitin',
        'tautan_turnitin',
        'file_turnitin',
        'id_pengguna_unggah',
        'dibuat_pada',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi');
    }

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_dokumen', 'id_dokumen');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna_unggah', 'id_pengguna');
    }
}
