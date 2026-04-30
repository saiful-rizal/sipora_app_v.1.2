<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Dokumen;
use App\Mail\DokumenApproved;
use App\Mail\DokumenRejected;
use App\Exports\DokumenReportExport;

class AdminDokumenController extends Controller
{
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

    public function detail($id)
    {
        $dokumen = Dokumen::with(['status','tema','jurusan','prodi','divisi','year','uploader'])
            ->findOrFail($id);

        $dokumen->file_dokumen_url  = $dokumen->file_path
            ? asset('uploads/documents/' . $dokumen->file_path) : null;
        $dokumen->turnitin_file_url = $dokumen->turnitin_file
            ? asset('uploads/turnitin/' . $dokumen->turnitin_file) : null;

        return response()->json($dokumen);
    }

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

    public function revoke($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        $idPending = DB::table('master_status_dokumen')
            ->whereIn('nama_status', ['Menunggu Review','Pending','pending','Draft'])
            ->value('status_id') ?? 1;

        $dokumen->update(['status_id' => $idPending]);

        return back()->with('success', "Dokumen \"{$dokumen->judul}\" dikembalikan ke pending.");
    }

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

    public function report(Request $request)
{
    $query = Dokumen::with(['status','tema','jurusan','prodi','divisi','year','uploader'])
        ->orderBy('tgl_unggah', 'desc');

    // Filter status
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

    // Filter tanggal
    if ($request->filled('tgl_dari')) {
        $query->whereDate('tgl_unggah', '>=', $request->tgl_dari);
    }

    if ($request->filled('tgl_sampai')) {
        $query->whereDate('tgl_unggah', '<=', $request->tgl_sampai);
    }

    // Filter jurusan
    if ($request->filled('id_jurusan')) {
        $query->where('id_jurusan', $request->id_jurusan);
    }

    $dokumens = $query->get();
    $jurusans = DB::table('master_jurusan')->orderBy('nama_jurusan')->get();

    // Summary data
    $summary = [
        'total'    => $dokumens->count(),
        'approved' => $dokumens->filter(fn($d) =>
            in_array(strtolower($d->status->nama_status ?? ''), ['diterbitkan','approved','disetujui'])
        )->count(),
        'pending'  => $dokumens->filter(fn($d) =>
            in_array(strtolower($d->status->nama_status ?? ''), ['menunggu review','pending','draft'])
        )->count(),
        'rejected' => $dokumens->filter(fn($d) =>
            in_array(strtolower($d->status->nama_status ?? ''), ['ditolak','rejected'])
        )->count(),
    ];

    // EXPORT EXCEL (SUDAH DIPERBAIKI)
    if ($request->filled('export') && $request->export === 'excel') {
        $filename = 'laporan-dokumen-' . now()->format('Ymd-His') . '.xlsx';

        // Label filter status
        $statusLabel = match($request->status ?? '') {
            'approved' => 'Disetujui',
            'pending'  => 'Menunggu Review',
            'rejected' => 'Ditolak',
            default    => 'Semua Status',
        };

        // Format tanggal
        $tglDari = $request->filled('tgl_dari')
            ? \Carbon\Carbon::parse($request->tgl_dari)->format('d M Y')
            : 'Awal';

        $tglSampai = $request->filled('tgl_sampai')
            ? \Carbon\Carbon::parse($request->tgl_sampai)->format('d M Y')
            : 'Sekarang';

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
