<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penulis extends Model
{
    use HasFactory;

    protected $table = 'master_author';
    protected $primaryKey = 'author_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_author',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->belongsToMany(
            Dokumen::class,
            'dokumen_author',
            'author_id',
            'dokumen_id',
            'author_id',
            'dokumen_id'
        );
    }
}
