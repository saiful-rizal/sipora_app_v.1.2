<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPenulis extends Model
{
    use HasFactory;

    protected $table = 'dokumen_author';
    protected $primaryKey = 'dokumen_author_id';
    public $timestamps = false;

    protected $fillable = [
        'dokumen_id',
        'author_id',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id', 'dokumen_id');
    }

    public function penulis()
    {
        return $this->belongsTo(Penulis::class, 'author_id', 'author_id');
    }
}
