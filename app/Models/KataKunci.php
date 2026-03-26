<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KataKunci extends Model
{
    use HasFactory;

    protected $table = 'kata_kunci';
    protected $primaryKey = 'id_kata_kunci';
    public $timestamps = false;

    protected $fillable = [
        'nama_kata_kunci',
        'frekuensi',
        'dibuat_pada',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->belongsToMany(
            Dokumen::class,
            'dokumen_kata_kunci',
            'id_kata_kunci',
            'id_dokumen',
            'id_kata_kunci',
            'id_dokumen'
        );
    }
}
