<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPenyaringanDokumen extends Model
{
    use HasFactory;

    protected $table = 'document_screenings';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'dokumen_id',
        'passed',
        'score',
        'checks_json',
        'message',
        'created_at',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'checks_json' => 'array',
        'created_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id', 'dokumen_id');
    }
}
