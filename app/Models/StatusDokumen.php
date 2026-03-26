<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusDokumen extends Model
{
    use HasFactory;

    protected $table = 'status_dokumen';
    protected $primaryKey = 'id_status';
    public $timestamps = false;

    protected $fillable = [
        'nama_status',
        'deskripsi',
        'warna',
        'dibuat_pada',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_status', 'id_status');
    }

    public function logKajianSebelum()
    {
        return $this->hasMany(LogKajian::class, 'id_status_sebelum', 'id_status');
    }

    public function logKajianSesudah()
    {
        return $this->hasMany(LogKajian::class, 'id_status_sesudah', 'id_status');
    }
}
