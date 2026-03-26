<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogKajian extends Model
{
    use HasFactory;

    protected $table = 'log_kajian';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = [
        'id_dokumen',
        'id_pengguna_kajian',
        'tanggal_kajian',
        'catatan_kajian',
        'id_status_sebelum',
        'id_status_sesudah',
        'alasan_perubahan',
    ];

    protected $casts = [
        'tanggal_kajian' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_dokumen', 'id_dokumen');
    }

    public function pengkajiDokumen()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna_kajian', 'id_pengguna');
    }

    public function statusSebelum()
    {
        return $this->belongsTo(StatusDokumen::class, 'id_status_sebelum', 'id_status');
    }

    public function statusSesudah()
    {
        return $this->belongsTo(StatusDokumen::class, 'id_status_sesudah', 'id_status');
    }
}
