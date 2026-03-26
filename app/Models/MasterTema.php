<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTema extends Model
{
    use HasFactory;

    protected $table = 'master_tema';
    protected $primaryKey = 'id_tema';
    public $timestamps = false;

    protected $fillable = [
        'nama_tema',
    ];
}
