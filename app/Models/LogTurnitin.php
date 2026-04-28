<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTurnitin extends Model
{
    use HasFactory;

    protected $table = 'turnitin';
    protected $primaryKey = 'id_turnitin';
    public $timestamps = false;

    protected $fillable = [
        'id_divisi',
        'turnitin_score',
        'turnitin_link',
        'file_turnitin',
        'uploader_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'uploader_id', 'id_user');
    }
}
