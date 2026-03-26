<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notif';
    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'id_pengguna_aktor',
        'id_dokumen',
        'jenis',
        'judul',
        'isi',
        'ikon_jenis',
        'ikon_kelas',
        'status',
        'dibuat_pada',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function penerima()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function aktor()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna_aktor', 'id_pengguna');
    }

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_dokumen', 'id_dokumen');
    }

    // ===== SCOPES =====

    public function scopeBelumDibaca($query)
    {
        return $query->where('status', 'belum_dibaca');
    }

    public function scopeSudahDibaca($query)
    {
        return $query->where('status', 'sudah_dibaca');
    }
}
