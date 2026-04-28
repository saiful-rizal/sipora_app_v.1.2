<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KataKunciTrending extends Model
{
    use HasFactory;

    protected $table = 'trending_keywords';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'keyword',
        'search_count',
        'last_searched',
    ];

    protected $casts = [
        'last_searched' => 'datetime',
    ];
}
