<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterStatusDokumen;

class LogKajian extends Model
{
    use HasFactory;

    protected $table = 'log_review';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'dokumen_id',
        'reviewer_id',
        'tgl_review',
        'catatan_review',
        'status_sebelum',
        'status_sesudah',
    ];

    protected $casts = [
        'tgl_review' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id', 'dokumen_id');
    }

    public function pengkajiDokumen()
    {
        return $this->belongsTo(User::class, 'reviewer_id', 'id_user');
    }

    public function statusSebelum()
    {
        return $this->belongsTo(MasterStatusDokumen::class, 'status_sebelum', 'status_id');
    }

    public function statusSesudah()
    {
        return $this->belongsTo(MasterStatusDokumen::class, 'status_sesudah', 'status_id');
    }
}
