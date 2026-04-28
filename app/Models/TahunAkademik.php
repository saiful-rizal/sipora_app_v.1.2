<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $table = 'master_tahun';
    protected $primaryKey = 'year_id';
    public $timestamps = false;

    protected $fillable = [
        'tahun',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'year_id', 'year_id');
    }
}
