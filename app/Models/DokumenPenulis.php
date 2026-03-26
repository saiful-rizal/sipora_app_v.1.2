<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPenulis extends Model
{
    use HasFactory;

    protected $table = 'dokumen_penulis';
    protected $primaryKey = 'id_dokumen_penulis';
    public $timestamps = false;

    protected $fillable = [
        'id_dokumen',
        'id_penulis',
        'urutan',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_dokumen', 'id_dokumen');
    }

    public function penulis()
    {
        return $this->belongsTo(Penulis::class, 'id_penulis', 'id_penulis');
    }
}
