<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPenyaringanDokumen extends Model
{
    use HasFactory;

    protected $table = 'log_penyaringan_dokumen';
    protected $primaryKey = 'id_penyaringan';
    public $timestamps = false;

    protected $fillable = [
        'id_dokumen',
        'lulus',
        'skor',
        'hasil_pemeriksaan',
        'pesan',
        'dibuat_pada',
    ];

    protected $casts = [
        'lulus' => 'boolean',
        'hasil_pemeriksaan' => 'json',
        'dibuat_pada' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_dokumen', 'id_dokumen');
    }
}
