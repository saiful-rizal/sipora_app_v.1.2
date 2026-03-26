<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterAkademikSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_rumpun')->upsert([
            ['id_rumpun' => 1, 'nama_rumpun' => 'Rumpun Matematika dan Ilmu Pengetahuan Alam (MIPA)'],
            ['id_rumpun' => 2, 'nama_rumpun' => 'Rumpun Ilmu Tanaman'],
            ['id_rumpun' => 3, 'nama_rumpun' => 'Rumpun Ilmu Hewani'],
            ['id_rumpun' => 4, 'nama_rumpun' => 'Rumpun Ilmu Kesehatan'],
            ['id_rumpun' => 5, 'nama_rumpun' => 'Rumpun Ilmu Teknik'],
            ['id_rumpun' => 6, 'nama_rumpun' => 'Rumpun Ilmu Bahasa'],
            ['id_rumpun' => 7, 'nama_rumpun' => 'Rumpun Ilmu Ekonomi dan Bisnis'],
            ['id_rumpun' => 8, 'nama_rumpun' => 'Rumpun Ilmu Sosial, Politik, dan Humaniora'],
            ['id_rumpun' => 9, 'nama_rumpun' => 'Rumpun Ilmu Agama dan Filsafat'],
            ['id_rumpun' => 10, 'nama_rumpun' => 'Rumpun Ilmu Seni, Desain, dan Media'],
            ['id_rumpun' => 11, 'nama_rumpun' => 'Rumpun Ilmu Pendidikan'],
            ['id_rumpun' => 12, 'nama_rumpun' => 'Rumpun Umum / Lainnya'],
        ], ['id_rumpun'], ['nama_rumpun']);

        DB::table('master_jurusan')->upsert([
            ['id_jurusan' => 1, 'nama_jurusan' => 'Teknologi Informasi', 'id_rumpun' => null],
            ['id_jurusan' => 2, 'nama_jurusan' => 'Produksi Pertanian', 'id_rumpun' => null],
            ['id_jurusan' => 3, 'nama_jurusan' => 'Teknologi Pertanian', 'id_rumpun' => null],
            ['id_jurusan' => 4, 'nama_jurusan' => 'Peternakan', 'id_rumpun' => null],
            ['id_jurusan' => 5, 'nama_jurusan' => 'Manajemen Agribisnis', 'id_rumpun' => null],
            ['id_jurusan' => 6, 'nama_jurusan' => 'Bahasa Komunikasi dan Pariwisata', 'id_rumpun' => null],
            ['id_jurusan' => 7, 'nama_jurusan' => 'Kesehatan', 'id_rumpun' => null],
            ['id_jurusan' => 8, 'nama_jurusan' => 'Teknik', 'id_rumpun' => null],
            ['id_jurusan' => 9, 'nama_jurusan' => 'Bisnis', 'id_rumpun' => null],
            ['id_jurusan' => 10, 'nama_jurusan' => 'Kelas Internasional', 'id_rumpun' => null],
        ], ['id_jurusan'], ['nama_jurusan', 'id_rumpun']);

        DB::table('master_prodi')->upsert([
            ['id_jurusan' => 1, 'id_prodi' => 1, 'nama_prodi' => 'Teknik Informatika'],
            ['id_jurusan' => 1, 'id_prodi' => 2, 'nama_prodi' => 'Manajemen Informatika'],
            ['id_jurusan' => 1, 'id_prodi' => 3, 'nama_prodi' => 'Teknik Komputer'],
            ['id_jurusan' => 1, 'id_prodi' => 4, 'nama_prodi' => 'Teknologi Rekayasa Komputer'],
            ['id_jurusan' => 2, 'id_prodi' => 5, 'nama_prodi' => 'Produksi Tanaman Hortikultura'],
            ['id_jurusan' => 2, 'id_prodi' => 6, 'nama_prodi' => 'Produksi Tanaman Perkebunan'],
            ['id_jurusan' => 2, 'id_prodi' => 7, 'nama_prodi' => 'Teknik Produksi Benih'],
            ['id_jurusan' => 2, 'id_prodi' => 8, 'nama_prodi' => 'Teknologi Produksi Tanaman Pangan'],
            ['id_jurusan' => 2, 'id_prodi' => 9, 'nama_prodi' => 'Budidaya Tanaman Perkebunan'],
            ['id_jurusan' => 2, 'id_prodi' => 10, 'nama_prodi' => 'Pengelolaan Perkebunan Kopi'],
            ['id_jurusan' => 3, 'id_prodi' => 11, 'nama_prodi' => 'Keteknikan Pertanian'],
            ['id_jurusan' => 3, 'id_prodi' => 12, 'nama_prodi' => 'Teknologi Industri Pangan'],
            ['id_jurusan' => 3, 'id_prodi' => 13, 'nama_prodi' => 'Teknologi Rekayasa Pangan'],
            ['id_jurusan' => 4, 'id_prodi' => 14, 'nama_prodi' => 'Produksi Ternak'],
            ['id_jurusan' => 4, 'id_prodi' => 15, 'nama_prodi' => 'Manajemen Bisnis Unggas'],
            ['id_jurusan' => 4, 'id_prodi' => 16, 'nama_prodi' => 'Teknologi Pakan Ternak'],
            ['id_jurusan' => 5, 'id_prodi' => 17, 'nama_prodi' => 'Manajemen Agribisnis'],
            ['id_jurusan' => 5, 'id_prodi' => 18, 'nama_prodi' => 'Manajemen Agroindustri'],
            ['id_jurusan' => 5, 'id_prodi' => 19, 'nama_prodi' => 'Pascasarjana Agribisnis'],
            ['id_jurusan' => 6, 'id_prodi' => 20, 'nama_prodi' => 'Bahasa Inggris'],
            ['id_jurusan' => 6, 'id_prodi' => 21, 'nama_prodi' => 'Destinasi Pariwisata'],
            ['id_jurusan' => 6, 'id_prodi' => 22, 'nama_prodi' => 'Produksi Media Kampus Bondowoso'],
            ['id_jurusan' => 7, 'id_prodi' => 23, 'nama_prodi' => 'Manajemen Informasi Kesehatan'],
            ['id_jurusan' => 7, 'id_prodi' => 24, 'nama_prodi' => 'Gizi Klinik'],
            ['id_jurusan' => 7, 'id_prodi' => 25, 'nama_prodi' => 'Promosi Kesehatan'],
            ['id_jurusan' => 8, 'id_prodi' => 26, 'nama_prodi' => 'Teknik Mesin Otomotif'],
            ['id_jurusan' => 8, 'id_prodi' => 27, 'nama_prodi' => 'Teknik Energi Terbarukan'],
            ['id_jurusan' => 8, 'id_prodi' => 28, 'nama_prodi' => 'Teknologi Rekayasa Mekatronika'],
            ['id_jurusan' => 9, 'id_prodi' => 29, 'nama_prodi' => 'Akuntansi Sektor Publik'],
            ['id_jurusan' => 9, 'id_prodi' => 30, 'nama_prodi' => 'Manajemen Pemasaran Internasional'],
            ['id_jurusan' => 9, 'id_prodi' => 31, 'nama_prodi' => 'Bisnis Digital (Kampus Bondowoso)'],
            ['id_jurusan' => 10, 'id_prodi' => 32, 'nama_prodi' => 'Manajemen Informatika'],
            ['id_jurusan' => 10, 'id_prodi' => 33, 'nama_prodi' => 'Teknik Informatika'],
            ['id_jurusan' => 10, 'id_prodi' => 34, 'nama_prodi' => 'Manajemen Agroindustri'],
        ], ['id_prodi'], ['id_jurusan', 'nama_prodi']);

        DB::table('master_tema')->upsert([
            ['id_tema' => 2, 'id_rumpun' => 1, 'kode_tema' => '111', 'nama_tema' => 'Fisika'],
            ['id_tema' => 3, 'id_rumpun' => 1, 'kode_tema' => '112', 'nama_tema' => 'Kimia'],
            ['id_tema' => 4, 'id_rumpun' => 1, 'kode_tema' => '113', 'nama_tema' => 'Biologi'],
            ['id_tema' => 5, 'id_rumpun' => 1, 'kode_tema' => '120', 'nama_tema' => 'Matematika'],
            ['id_tema' => 6, 'id_rumpun' => 1, 'kode_tema' => '123', 'nama_tema' => 'Ilmu Komputer'],
            ['id_tema' => 7, 'id_rumpun' => 2, 'kode_tema' => '150', 'nama_tema' => 'Ilmu Pertanian dan Perkebunan'],
            ['id_tema' => 8, 'id_rumpun' => 2, 'kode_tema' => '152', 'nama_tema' => 'Hortikultura'],
            ['id_tema' => 9, 'id_rumpun' => 2, 'kode_tema' => '155', 'nama_tema' => 'Perkebunan'],
            ['id_tema' => 10, 'id_rumpun' => 2, 'kode_tema' => '160', 'nama_tema' => 'Teknologi dalam Ilmu Tanaman'],
            ['id_tema' => 11, 'id_rumpun' => 2, 'kode_tema' => '165', 'nama_tema' => 'Teknologi Pangan dan Gizi'],
            ['id_tema' => 12, 'id_rumpun' => 4, 'kode_tema' => '350', 'nama_tema' => 'Ilmu Kesehatan Umum'],
            ['id_tema' => 13, 'id_rumpun' => 4, 'kode_tema' => '353', 'nama_tema' => 'Kebijakan dan Analisis Kesehatan'],
            ['id_tema' => 14, 'id_rumpun' => 4, 'kode_tema' => '354', 'nama_tema' => 'Ilmu Gizi'],
            ['id_tema' => 15, 'id_rumpun' => 4, 'kode_tema' => '357', 'nama_tema' => 'Promosi Kesehatan'],
            ['id_tema' => 16, 'id_rumpun' => 5, 'kode_tema' => '457', 'nama_tema' => 'Teknik Komputer'],
            ['id_tema' => 17, 'id_rumpun' => 5, 'kode_tema' => '458', 'nama_tema' => 'Teknik Informatika'],
            ['id_tema' => 18, 'id_rumpun' => 5, 'kode_tema' => '462', 'nama_tema' => 'Teknologi Informasi'],
            ['id_tema' => 19, 'id_rumpun' => 5, 'kode_tema' => '463', 'nama_tema' => 'Teknik Perangkat Lunak'],
            ['id_tema' => 20, 'id_rumpun' => 11, 'kode_tema' => '742', 'nama_tema' => 'Pendidikan Bahasa Inggris'],
            ['id_tema' => 21, 'id_rumpun' => 11, 'kode_tema' => '772', 'nama_tema' => 'Pendidikan Matematika'],
            ['id_tema' => 22, 'id_rumpun' => 11, 'kode_tema' => '773', 'nama_tema' => 'Pendidikan Fisika'],
        ], ['id_tema'], ['id_rumpun', 'kode_tema', 'nama_tema']);

        DB::table('master_tahun')->upsert([
            ['year_id' => 202, 'tahun' => '2025'],
            ['year_id' => 203, 'tahun' => '2024'],
            ['year_id' => 204, 'tahun' => '2023'],
            ['year_id' => 205, 'tahun' => '2022'],
            ['year_id' => 206, 'tahun' => '2021'],
            ['year_id' => 207, 'tahun' => '2020'],
            ['year_id' => 208, 'tahun' => '2019'],
            ['year_id' => 209, 'tahun' => '2018'],
            ['year_id' => 210, 'tahun' => '2017'],
            ['year_id' => 211, 'tahun' => '2016'],
            ['year_id' => 212, 'tahun' => '2015'],
            ['year_id' => 213, 'tahun' => '2014'],
            ['year_id' => 214, 'tahun' => '2013'],
            ['year_id' => 215, 'tahun' => '2012'],
            ['year_id' => 216, 'tahun' => '2011'],
            ['year_id' => 217, 'tahun' => '2010'],
            ['year_id' => 218, 'tahun' => '2004'],
            ['year_id' => 219, 'tahun' => '2002'],
            ['year_id' => 220, 'tahun' => '0202'],
            ['year_id' => 221, 'tahun' => '0201'],
            ['year_id' => 222, 'tahun' => '0031'],
            ['year_id' => 223, 'tahun' => '0030'],
            ['year_id' => 224, 'tahun' => '0028'],
            ['year_id' => 225, 'tahun' => '0027'],
            ['year_id' => 226, 'tahun' => '0026'],
            ['year_id' => 227, 'tahun' => '0025'],
            ['year_id' => 228, 'tahun' => '0024'],
            ['year_id' => 229, 'tahun' => '0023'],
            ['year_id' => 230, 'tahun' => '0022'],
            ['year_id' => 231, 'tahun' => '0021'],
            ['year_id' => 232, 'tahun' => '0020'],
            ['year_id' => 233, 'tahun' => '0019'],
            ['year_id' => 234, 'tahun' => '0015'],
            ['year_id' => 235, 'tahun' => '0011'],
            ['year_id' => 236, 'tahun' => '0006'],
            ['year_id' => 237, 'tahun' => '0005'],
            ['year_id' => 238, 'tahun' => '0004'],
            ['year_id' => 239, 'tahun' => '0002'],
            ['year_id' => 240, 'tahun' => '0001'],
            ['year_id' => 241, 'tahun' => 'Not Specified'],
        ], ['year_id'], ['tahun']);

        DB::table('master_status_dokumen')->upsert([
            ['status_id' => 1, 'nama_status' => 'Menunggu Review'],
            ['status_id' => 2, 'nama_status' => 'Diperiksa'],
            ['status_id' => 3, 'nama_status' => 'Disetujui'],
            ['status_id' => 4, 'nama_status' => 'Ditolak'],
            ['status_id' => 5, 'nama_status' => 'Publikasi'],
        ], ['status_id'], ['nama_status']);

        DB::table('master_divisi')->upsert([
            ['id_divisi' => 261, 'nama_divisi' => 'Tugas Akhir'],
            ['id_divisi' => 262, 'nama_divisi' => 'PKL'],
            ['id_divisi' => 263, 'nama_divisi' => 'Publikasi'],
            ['id_divisi' => 264, 'nama_divisi' => 'General'],
        ], ['id_divisi'], ['nama_divisi']);
    }
}
