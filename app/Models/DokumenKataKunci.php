<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenKataKunci extends Model
{
    use HasFactory;

    protected $table = 'dokumen_keyword';
    protected $primaryKey = 'dokumen_keyword_id';
    public $timestamps = false;

    protected $fillable = [
        'dokumen_id',
        'keyword_id',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id', 'dokumen_id');
    }

    public function kataKunci()
    {
        return $this->belongsTo(KataKunci::class, 'keyword_id', 'keyword_id');
    }
}
