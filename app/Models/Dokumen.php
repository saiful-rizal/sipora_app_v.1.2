<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterTema;
use App\Models\MasterJurusan;
use App\Models\MasterProdi;
use App\Models\MasterDivisi;
use App\Models\MasterTahun;
use App\Models\MasterStatusDokumen;
use App\Models\User;

class Dokumen extends Model
{
    use HasFactory;

    protected $table      = 'dokumen';
    protected $primaryKey = 'dokumen_id';
    public $timestamps    = false;

    protected $fillable = [
        'judul',
        'abstrak',
        'turnitin',
        'turnitin_file',
        'kata_kunci',
        'file_path',
        'tgl_unggah',
        'uploader_id',
        'id_tema',
        'id_jurusan',
        'id_prodi',
        'id_divisi',
        'year_id',
        'status_id',
    ];

    protected $casts = [
        'tgl_unggah' => 'datetime',
    ];

    // ================= RELATION =================

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id', 'id_user');
    }

    public function tema()
    {
        return $this->belongsTo(MasterTema::class, 'id_tema', 'id_tema');
    }

    public function jurusan()
    {
        return $this->belongsTo(MasterJurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function prodi()
    {
        return $this->belongsTo(MasterProdi::class, 'id_prodi', 'id_prodi');
    }

    public function divisi()
    {
        return $this->belongsTo(MasterDivisi::class, 'id_divisi', 'id_divisi');
    }

    public function year()
    {
        return $this->belongsTo(MasterTahun::class, 'year_id', 'year_id');
    }

    public function status()
    {
        return $this->belongsTo(MasterStatusDokumen::class, 'status_id', 'status_id');
    }
}
