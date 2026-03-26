<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenKataKunci extends Model
{
    use HasFactory;

    protected $table = 'dokumen_kata_kunci';
    protected $primaryKey = 'id_dokumen_kata_kunci';
    public $timestamps = false;

    protected $fillable = [
        'id_dokumen',
        'id_kata_kunci',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_dokumen', 'id_dokumen');
    }

    public function kataKunci()
    {
        return $this->belongsTo(KataKunci::class, 'id_kata_kunci', 'id_kata_kunci');
    }
}
