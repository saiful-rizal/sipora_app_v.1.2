<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'dokumen';
    protected $primaryKey = 'dokumen_id';
    public $timestamps = false;

    protected $fillable = [
        'judul',
        'jenis_dokumen',
        'abstrak',
        'kata_kunci',
        'uploader_id',
        'file_path',
        'view_count',
        'file_size',
        'status_id',
        'id_jurusan',
        'id_prodi',
        'id_tema',
        'year_id',
        'id_divisi',
        'turnitin',
        'turnitin_file',
        'tgl_unggah',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id', 'id_user');
    }

    public function jurusan()
    {
        return $this->belongsTo(MasterJurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function prodi()
    {
        return $this->belongsTo(MasterProdi::class, 'id_prodi', 'id_prodi');
    }

    public function tema()
    {
        return $this->belongsTo(MasterTema::class, 'id_tema', 'id_tema');
    }

    public function tahun()
    {
        return $this->belongsTo(MasterTahun::class, 'year_id', 'year_id');
    }
}
