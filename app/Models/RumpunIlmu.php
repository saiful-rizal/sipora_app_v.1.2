<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RumpunIlmu extends Model
{
    use HasFactory;

    protected $table = 'master_rumpun';
    protected $primaryKey = 'id_rumpun';
    public $timestamps = false;

    protected $fillable = [
        'nama_rumpun',
    ];

    // ===== RELATIONSHIPS =====

    public function jurusan()
    {
        return $this->hasMany(Jurusan::class, 'id_rumpun', 'id_rumpun');
    }

    public function tema()
    {
        return $this->hasMany(Tema::class, 'id_rumpun', 'id_rumpun');
    }
}
