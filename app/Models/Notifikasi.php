<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notif';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'status',
        'waktu',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function penerima()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    // ===== SCOPES =====

    public function scopeBelumDibaca($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeSudahDibaca($query)
    {
        return $query->where('status', 'read');
    }
}
