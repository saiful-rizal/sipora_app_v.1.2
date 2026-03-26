<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penulis extends Model
{
    use HasFactory;

    protected $table = 'penulis';
    protected $primaryKey = 'id_penulis';
    public $timestamps = false;

    protected $fillable = [
        'nama_penulis',
        'email',
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
            'dokumen_penulis',
            'id_penulis',
            'id_dokumen',
            'id_penulis',
            'id_dokumen'
        )->withPivot('urutan');
    }
}
