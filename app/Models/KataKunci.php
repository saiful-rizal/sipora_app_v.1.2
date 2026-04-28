<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KataKunci extends Model
{
    use HasFactory;

    protected $table = 'master_keyword';
    protected $primaryKey = 'keyword_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_keyword',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->belongsToMany(
            Dokumen::class,
            'dokumen_keyword',
            'keyword_id',
            'dokumen_id',
            'keyword_id',
            'dokumen_id'
        );
    }
}
