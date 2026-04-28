<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusDokumen extends Model
{
    use HasFactory;

    protected $table = 'master_status_dokumen';
    protected $primaryKey = 'status_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_status',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'status_id', 'status_id');
    }

    public function logKajianSebelum()
    {
        return $this->hasMany(LogKajian::class, 'id_status_sebelum', 'status_id');
    }

    public function logKajianSesudah()
    {
        return $this->hasMany(LogKajian::class, 'id_status_sesudah', 'status_id');
    }
}
