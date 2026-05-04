<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Dokumen;
use App\Mail\DokumenApproved;
use App\Mail\DokumenRejected;
use App\Mail\DokumenPublished;          // ← tambah Mailable baru untuk notif publish
use App\Exports\DokumenReportExport;
use Carbon\Carbon;

class AdminDokumenController extends Controller
{
    /* ============================================================
     | INDEX
     ============================================================ */
    public function index()
    {
        $dokumens = Dokumen::with(['status','tema','jurusan','prodi','divisi','year','uploader'])
            ->orderBy('dokumen_id', 'desc')
            ->get();

        return view('admin.documents', [
            'dokumens'   => $dokumens,
            'activeMenu' => 'documents',
        ]);
    }

    /* ============================================================
     | DETAIL (AJAX)
     ============================================================ */
    public function detail($id)
    {
        $dokumen = Dokumen::with(['status','tema','jurusan','prodi','divisi','year','uploader'])
            ->findOrFail($id);

        $dokumen->file_dokumen_url  = $dokumen->file_path
            ? asset('uploads/documents/' . $dokumen->file_path) : null;
        $dokumen->turnitin_file_url = $dokumen->turnitin_file
            ? asset('uploads/turnitin/' . $dokumen->turnitin_file) : null;

        // Pastikan is_published selalu boolean bersih untuk JSON
        $dokumen->is_published = (bool) $dokumen->is_published;

        return response()->json($dokumen);
    }

    /* ============================================================
     | APPROVE
     ============================================================ */
    public function approve($id)
    {
        $dokumen = Dokumen::with('uploader')->findOrFail($id);

        $idApproved = DB::table('master_status_dokumen')
            ->whereIn('nama_status', ['Diterbitkan','Approved','approved','Disetujui'])
            ->value('status_id') ?? 2;

        $dokumen->update(['status_id' => $idApproved]);

        if ($dokumen->uploader && $dokumen->uploader->email) {
            try {
                Mail::to($dokumen->uploader->email)
                    ->send(new DokumenApproved(
                        namaPengirim: $dokumen->uploader->nama_lengkap,
                        judulDokumen: $dokumen->judul,
                    ));
            } catch (\Throwable $e) {}
        }

        return back()->with('success', "Dokumen \"{$dokumen->judul}\" berhasil di-approve.");
    }

    /* ============================================================
     | REJECT
     ============================================================ */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string|max:1000',
            'opsi_file'     => 'required|in:original,reviewed,tidak',
            'file_reviewed' => 'required_if:opsi_file,reviewed|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:10240',
        ]);

        $dokumen = Dokumen::with('uploader')->findOrFail($id);

        $idRejected = DB::table('master_status_dokumen')
            ->whereIn('nama_status', ['Ditolak','Rejected','rejected'])
            ->value('status_id') ?? 3;

        $dokumen->update(['status_id' => $idRejected]);

        if ($dokumen->uploader && $dokumen->uploader->email) {
            try {
                $filePath = null;

                if ($request->opsi_file === 'original') {
                    $filePath = $dokumen->file_path
                        ? public_path('uploads/documents/' . $dokumen->file_path)
                        : null;
                } elseif ($request->opsi_file === 'reviewed' && $request->hasFile('file_reviewed')) {
                    File::ensureDirectoryExists(public_path('uploads/reviewed'));
                    $file     = $request->file('file_reviewed');
                    $fileName = 'reviewed_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/reviewed'), $fileName);
                    $filePath = public_path('uploads/reviewed/' . $fileName);
                }

                Mail::to($dokumen->uploader->email)
                    ->send(new DokumenRejected(
                        namaPengirim: $dokumen->uploader->nama_lengkap,
                        judulDokumen: $dokumen->judul,
                        alasanReject: $request->alasan_reject,
                        filePath:     $filePath,
                    ));
            } catch (\Throwable $e) {}
        }

        return back()->with('success', "Dokumen \"{$dokumen->judul}\" berhasil di-reject.");
    }

    /* ============================================================
     | REVOKE → kembalikan ke pending
     ============================================================ */
    public function revoke($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        $idPending = DB::table('master_status_dokumen')
            ->whereIn('nama_status', ['Menunggu Review','Pending','pending','Draft'])
            ->value('status_id') ?? 1;

        $dokumen->update(['status_id' => $idPending]);

        return back()->with('success', "Dokumen \"{$dokumen->judul}\" dikembalikan ke pending.");
    }

    /* ============================================================
     | DESTROY
     ============================================================ */
    public function destroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        $namaStatus = strtolower($dokumen->status->nama_status ?? '');
        if (!in_array($namaStatus, ['ditolak','rejected'])) {
            return back()->with('error', 'Hanya dokumen berstatus rejected yang bisa dihapus.');
        }

        if (!empty($dokumen->file_path)) {
            $path = public_path('uploads/documents/' . $dokumen->file_path);
            if (file_exists($path)) @unlink($path);
        }
        if (!empty($dokumen->turnitin_file)) {
            $path = public_path('uploads/turnitin/' . $dokumen->turnitin_file);
            if (file_exists($path)) @unlink($path);
        }

        $judul = $dokumen->judul;
        $dokumen->delete();

        return back()->with('success', "Dokumen \"{$judul}\" berhasil dihapus.");
    }

    /* ============================================================
     | PUBLISH — generate nomor surat & tandai dipublikasi
     | FIXED: gunakan POST (sesuai route), kirim email ke uploader,
     |        cast is_published agar fresh() benar
     ============================================================ */
    public function publish(Request $request, $id)
    {
        $dokumen = Dokumen::with(['status','uploader','jurusan','prodi','tema'])->findOrFail($id);

        $namaStatus = strtolower($dokumen->status->nama_status ?? '');
        $isApproved = in_array($namaStatus, ['diterbitkan','approved','disetujui']);

        if (!$isApproved) {
            return back()->with('error', 'Hanya dokumen berstatus Approved yang dapat dipublikasi.');
        }

        // Cast aman: cek apakah sudah dipublikasi
        if ((bool) $dokumen->is_published) {
            return back()->with('error', 'Dokumen ini sudah dipublikasi sebelumnya.');
        }

        // Generate nomor surat: PUBL/{tahun}/{bulan}/{urutan 4 digit}
        $tahun  = now()->format('Y');
        $bulan  = now()->format('m');
        $urutan = Dokumen::whereNotNull('nomor_surat')
            ->whereYear('published_at', $tahun)
            ->whereMonth('published_at', $bulan)
            ->count() + 1;

        $nomorSurat = sprintf('PUBL/%s/%s/%04d', $tahun, $bulan, $urutan);

        $dokumen->update([
            'is_published' => true,
            'published_at' => now(),
            'nomor_surat'  => $nomorSurat,
        ]);

        // ── KIRIM EMAIL NOTIFIKASI KE UPLOADER ──────────────────────
        if ($dokumen->uploader && $dokumen->uploader->email) {
            try {
                // Jika Mailable DokumenPublished belum ada, gunakan DokumenApproved sementara
                // Buat DokumenPublished Mailable terpisah (lihat instruksi di bawah)
                if (class_exists(\App\Mail\DokumenPublished::class)) {
                    Mail::to($dokumen->uploader->email)
                        ->send(new DokumenPublished(
                            namaPengirim: $dokumen->uploader->nama_lengkap,
                            judulDokumen: $dokumen->judul,
                            nomorSurat:   $nomorSurat,
                        ));
                }
            } catch (\Throwable $e) {
                // Silent fail — jangan gagalkan proses publish karena email error
            }
        }

        return back()->with('success',
            "Dokumen \"{$dokumen->judul}\" berhasil dipublikasi. Nomor Surat: {$nomorSurat}");
    }

    /* ============================================================
     | SURAT PUBLIKASI — generate & download PDF via dompdf
     ============================================================ */
    public function suratPublikasi($id)
    {
        $dokumen = Dokumen::with(['status','tema','jurusan','prodi','divisi','year','uploader'])
            ->findOrFail($id);

        if (!(bool) $dokumen->is_published || !$dokumen->nomor_surat) {
            abort(404, 'Surat publikasi tidak tersedia. Dokumen belum dipublikasi.');
        }

        Carbon::setLocale('id');

        $pdf = Pdf::loadView('admin.surat_publikasi', compact('dokumen'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'             => 'serif',
                'isRemoteEnabled'         => false,
                'isHtml5ParserEnabled'    => true,
                'isFontSubsettingEnabled' => true,
                'dpi'                     => 150,
            ]);

        $filename = 'surat-publikasi-' . str_pad($dokumen->dokumen_id, 6, '0', STR_PAD_LEFT)
            . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /* ============================================================
     | REPORT
     ============================================================ */
    public function report(Request $request)
    {
        $query = Dokumen::with(['status','tema','jurusan','prodi','divisi','year','uploader'])
            ->orderBy('tgl_unggah', 'desc');

        if ($request->filled('status')) {
            $statusMap = [
                'approved' => ['Diterbitkan','Approved','approved','Disetujui'],
                'pending'  => ['Menunggu Review','Pending','pending','Draft'],
                'rejected' => ['Ditolak','Rejected','rejected'],
            ];
            $namaStatus = $statusMap[$request->status] ?? [];
            if ($namaStatus) {
                $query->whereHas('status', function ($q) use ($namaStatus) {
                    $q->whereIn('nama_status', $namaStatus);
                });
            }
        }

        if ($request->filled('tgl_dari'))   $query->whereDate('tgl_unggah', '>=', $request->tgl_dari);
        if ($request->filled('tgl_sampai')) $query->whereDate('tgl_unggah', '<=', $request->tgl_sampai);
        if ($request->filled('id_jurusan')) $query->where('id_jurusan', $request->id_jurusan);

        $dokumens = $query->get();
        $jurusans = DB::table('master_jurusan')->orderBy('nama_jurusan')->get();

        $summary = [
            'total'    => $dokumens->count(),
            'approved' => $dokumens->filter(fn($d) =>
                in_array(strtolower($d->status->nama_status ?? ''), ['diterbitkan','approved','disetujui']))->count(),
            'pending'  => $dokumens->filter(fn($d) =>
                in_array(strtolower($d->status->nama_status ?? ''), ['menunggu review','pending','draft']))->count(),
            'rejected' => $dokumens->filter(fn($d) =>
                in_array(strtolower($d->status->nama_status ?? ''), ['ditolak','rejected']))->count(),
        ];

        if ($request->filled('export') && $request->export === 'excel') {
            $filename    = 'laporan-dokumen-' . now()->format('Ymd-His') . '.xlsx';
            $statusLabel = match($request->status ?? '') {
                'approved' => 'Disetujui',
                'pending'  => 'Menunggu Review',
                'rejected' => 'Ditolak',
                default    => 'Semua Status',
            };
            $tglDari   = $request->filled('tgl_dari')
                ? Carbon::parse($request->tgl_dari)->format('d M Y') : 'Awal';
            $tglSampai = $request->filled('tgl_sampai')
                ? Carbon::parse($request->tgl_sampai)->format('d M Y') : 'Sekarang';

            return Excel::download(
                new DokumenReportExport($dokumens, $statusLabel, $tglDari, $tglSampai),
                $filename
            );
        }

        return view('admin.documents_report', [
            'activeMenu' => 'documents_report',
            'dokumens'   => $dokumens,
            'summary'    => $summary,
            'jurusans'   => $jurusans,
            'filters'    => $request->only(['status','tgl_dari','tgl_sampai','id_jurusan']),
        ]);
    }
}
