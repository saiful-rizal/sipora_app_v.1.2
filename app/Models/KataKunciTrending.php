<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KataKunciTrending extends Model
{
    use HasFactory;

    protected $table = 'kata_kunci_trending';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'kata_kunci',
        'jumlah_pencarian',
        'pencarian_terakhir',
    ];

    protected $casts = [
        'pencarian_terakhir' => 'datetime',
    ];
}
