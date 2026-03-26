<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dokumen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dokumen';
    protected $primaryKey = 'id_dokumen';
    public $timestamps = false;

    protected $fillable = [
        'id_pengguna_unggah',
        'id_tema',
        'id_jurusan',
        'id_prodi',
        'id_divisi',
        'id_tahun',
        'id_status',
        'id_pengguna_reviewer',
        'judul',
        'abstrak',
        'file_dokumen',
        'skor_turnitin',
        'file_turnitin',
        'jumlah_halaman',
        'tanggal_unggah',
        'tanggal_review',
        'catatan_review',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    protected $casts = [
        'tanggal_unggah' => 'datetime',
        'tanggal_review' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'dihapus_pada' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function pengunggah()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna_unggah', 'id_pengguna');
    }

    public function reviewer()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna_reviewer', 'id_pengguna');
    }

    public function tema()
    {
        return $this->belongsTo(Tema::class, 'id_tema', 'id_tema');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'id_tahun', 'id_tahun');
    }

    public function status()
    {
        return $this->belongsTo(StatusDokumen::class, 'id_status', 'id_status');
    }

    public function penulis()
    {
        return $this->belongsToMany(
            Penulis::class,
            'dokumen_penulis',
            'id_dokumen',
            'id_penulis',
            'id_dokumen',
            'id_penulis'
        )->withPivot('urutan')->orderBy('dokumen_penulis.urutan');
    }

    public function kataKunci()
    {
        return $this->belongsToMany(
            KataKunci::class,
            'dokumen_kata_kunci',
            'id_dokumen',
            'id_kata_kunci',
            'id_dokumen',
            'id_kata_kunci'
        );
    }

    public function logKajian()
    {
        return $this->hasMany(LogKajian::class, 'id_dokumen', 'id_dokumen');
    }

    public function penyaringan()
    {
        return $this->hasMany(LogPenyaringanDokumen::class, 'id_dokumen', 'id_dokumen');
    }

    public function logTurnitin()
    {
        return $this->hasMany(LogTurnitin::class, 'id_dokumen', 'id_dokumen');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_dokumen', 'id_dokumen');
    }

    // ===== SCOPES =====

    public function scopeDiterbitkan($query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('nama_status', 'Diterbitkan');
        });
    }

    public function scopeDraft($query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('nama_status', 'Draft');
        });
    }

    public function scopeMenungguReview($query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('nama_status', 'Menunggu Review');
        });
    }

    // ===== ACCESSORS/MUTATORS =====

    public function getNilaiTurnitinAttribute()
    {
        return $this->skor_turnitin ?? 0;
    }
}
