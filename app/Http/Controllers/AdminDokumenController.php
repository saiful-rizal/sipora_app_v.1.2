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
    private function storeNotification(?int $userId, ?int $actorId, ?int $docId, string $type, string $title, string $message, string $iconType, string $iconClass): void
    {
        $payload = [
            'user_id' => $userId,
            'actor_id' => $actorId,
            'doc_id' => $docId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon_type' => $iconType,
            'icon_class' => $iconClass,
            'is_read' => 0,
            'created_at' => now(),
        ];

        DB::table('notifications')->insert($payload);

        if ($userId) {
            DB::table('notifikasi')->insert([
                'user_id' => $userId,
                'judul' => $title,
                'isi' => strip_tags($message),
                'status' => 'unread',
                'waktu' => now(),
            ]);
        }
    }

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
        $oldStatus = (int) ($dokumen->status_id ?? 0);

        $idApproved = DB::table('master_status_dokumen')
            ->whereIn('nama_status', ['Diterbitkan','Approved','approved','Disetujui'])
            ->value('status_id') ?? 2;

        $dokumen->update(['status_id' => $idApproved]);

        $this->storeNotification(
            $dokumen->uploader?->id_user,
            null,
            $dokumen->dokumen_id,
            'document_approved',
            'Dokumen Disetujui',
            '<strong>' . e($dokumen->judul) . '</strong> telah disetujui oleh admin.',
            'success',
            'bi-check-circle-fill'
        );

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
            'file_reviewed' => 'required_if:opsi_file,reviewed|file|mimes:pdf,docx|max:10240',
        ]);

        $dokumen = Dokumen::with('uploader')->findOrFail($id);

        $idRejected = DB::table('master_status_dokumen')
            ->whereIn('nama_status', ['Ditolak','Rejected','rejected'])
            ->value('status_id') ?? 3;

        $dokumen->update(['status_id' => $idRejected]);

        $this->storeNotification(
            $dokumen->uploader?->id_user,
            null,
            $dokumen->dokumen_id,
            'document_rejected',
            'Dokumen Ditolak',
            '<strong>' . e($dokumen->judul) . '</strong> ditolak oleh admin.',
            'danger',
            'bi-x-circle-fill'
        );

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

        $this->storeNotification(
            $dokumen->uploader?->id_user ?? null,
            null,
            $dokumen->dokumen_id,
            'document_revoke',
            'Dokumen Dikembalikan',
            '<strong>' . e($dokumen->judul) . '</strong> dikembalikan ke status menunggu review.',
            'warning',
            'bi-arrow-counterclockwise'
        );

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

        $this->storeNotification(
            $dokumen->uploader?->id_user ?? null,
            null,
            $dokumen->dokumen_id,
            'document_deleted',
            'Dokumen Dihapus',
            '<strong>' . e($judul) . '</strong> telah dihapus dari sistem.',
            'danger',
            'bi-trash3-fill'
        );

        return back()->with('success', "Dokumen \"{$judul}\" berhasil dihapus.");
    }

    public function report(Request $request)
{
    $query = Dokumen::with(['status','tema','jurusan','prodi','divisi','year','uploader'])
        ->orderBy('tgl_unggah', 'desc');

    $statusOptions = DB::table('master_status_dokumen')->orderBy('nama_status')->get();

    // Filter status
    if ($request->filled('status_id')) {
        $query->where('status_id', (int) $request->status_id);
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
    $statusOptions = DB::table('master_status_dokumen')->orderBy('nama_status')->get();

    // Summary data
    $summary = [
        'total'    => $dokumens->count(),
        'approved' => $dokumens->filter(fn($d) => in_array(strtolower($d->status->nama_status ?? ''), ['diterbitkan','approved','disetujui']))->count(),
        'pending'  => $dokumens->filter(fn($d) => in_array(strtolower($d->status->nama_status ?? ''), ['menunggu review','pending','draft']))->count(),
        'rejected' => $dokumens->filter(fn($d) => in_array(strtolower($d->status->nama_status ?? ''), ['ditolak','rejected']))->count(),
    ];

    // EXPORT EXCEL (SUDAH DIPERBAIKI)
    if ($request->filled('export') && $request->export === 'excel') {
        $filename = 'laporan-dokumen-' . now()->format('Ymd-His') . '.xlsx';

        // Label filter status
        $statusLabel = $request->filled('status_id')
            ? ($statusOptions->firstWhere('status_id', (int) $request->status_id)->nama_status ?? 'Semua Status')
            : 'Semua Status';

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
        'status_options' => $statusOptions,
        'filters'    => $request->only(['status_id','tgl_dari','tgl_sampai','id_jurusan']),
    ]);
}
}
