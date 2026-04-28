<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatPencarian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'search_history';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'keyword',
        'created_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}
