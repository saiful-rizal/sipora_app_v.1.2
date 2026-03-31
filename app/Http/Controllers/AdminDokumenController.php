<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Dokumen;

class AdminDokumenController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LIST DOKUMEN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $dokumens = Dokumen::with([
                'status',
                'tema',
                'jurusan',
                'prodi',
                'divisi',
                'year',
                'uploader'
            ])
            ->orderBy('dokumen_id', 'desc')
            ->get();

        return view('admin.documents', [
            'dokumens' => $dokumens,
            'activeMenu' => 'documents'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL AJAX
    |--------------------------------------------------------------------------
    */

    public function detail($id)
    {
        $dokumen = Dokumen::with([
                'status',
                'tema',
                'jurusan',
                'prodi',
                'divisi',
                'year',
                'uploader'
            ])
            ->findOrFail($id);

        return response()->json($dokumen);
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        $idApproved = DB::table('status_dokumen')
            ->whereIn('nama_status', [
                'Diterbitkan',
                'Approved',
                'approved',
                'Disetujui',
            ])
            ->value('status_id') ?? 2;

        $dokumen->update([
            'status_id' => $idApproved
        ]);

        return back()->with(
            'success',
            "Dokumen \"{$dokumen->judul}\" berhasil di-approve."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string|max:1000',
        ]);

        $dokumen = Dokumen::findOrFail($id);

        $idRejected = DB::table('status_dokumen')
            ->whereIn('nama_status', [
                'Ditolak',
                'Rejected',
                'rejected',
            ])
            ->value('status_id') ?? 3;

        $dokumen->update([
            'status_id' => $idRejected
        ]);

        return back()->with(
            'success',
            "Dokumen \"{$dokumen->judul}\" berhasil di-reject."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REVOKE → PENDING
    |--------------------------------------------------------------------------
    */

    public function revoke($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        $idPending = DB::table('status_dokumen')
            ->whereIn('nama_status', [
                'Menunggu Review',
                'Pending',
                'pending',
                'Draft',
            ])
            ->value('status_id') ?? 1;

        $dokumen->update([
            'status_id' => $idPending
        ]);

        return back()->with(
            'success',
            "Dokumen \"{$dokumen->judul}\" dikembalikan ke pending."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE PERMANEN
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        $namaStatus = strtolower(
            $dokumen->status->nama_status ?? ''
        );

        if (!in_array($namaStatus, ['ditolak', 'rejected'])) {
            return back()->with(
                'error',
                'Hanya dokumen berstatus rejected yang bisa dihapus.'
            );
        }

        if (!empty($dokumen->file_path) && Storage::exists($dokumen->file_path)) {
            Storage::delete($dokumen->file_path);
        }

        if (!empty($dokumen->turnitin_file) && Storage::exists($dokumen->turnitin_file)) {
            Storage::delete($dokumen->turnitin_file);
        }

        $judul = $dokumen->judul;

        $dokumen->delete();

        return back()->with(
            'success',
            "Dokumen \"{$judul}\" berhasil dihapus."
        );
    }

}
