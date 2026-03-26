<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';
    protected $primaryKey = 'id_jurusan';
    public $timestamps = false;

    protected $fillable = [
        'id_rumpun',
        'nama_jurusan',
        'kode_jurusan',
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

    public function programStudi()
    {
        return $this->hasMany(ProgramStudi::class, 'id_jurusan', 'id_jurusan');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_jurusan', 'id_jurusan');
    }
}
