<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminMasterDataController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        return $this->dashboard($request);
    }

    public function dashboard(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->ensureAdmin($request)) {
            return $redirect;
        }

        $counts = [
            'jurusan' => DB::table('master_jurusan')->count(),
            'prodi' => DB::table('master_prodi')->count(),
            'tema' => DB::table('master_tema')->count(),
            'users' => DB::table('users')->count(),
        ];

        return view('admin.dashboard', [
            'counts' => $counts,
            'activeMenu' => 'dashboard',
            ...$this->getAdminContext($request),
        ]);
    }

    public function jurusanIndex(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->ensureAdmin($request)) {
            return $redirect;
        }

        $jurusan = DB::table('master_jurusan as j')
            ->leftJoin('master_rumpun as r', 'j.id_rumpun', '=', 'r.id_rumpun')
            ->select('j.id_jurusan', 'j.nama_jurusan', 'j.id_rumpun', 'r.nama_rumpun')
            ->orderBy('j.nama_jurusan')
            ->get();

        $rumpun = DB::table('master_rumpun')
            ->select('id_rumpun', 'nama_rumpun')
            ->orderBy('nama_rumpun')
            ->get();

        return view('admin.jurusan', [
            'jurusan' => $jurusan,
            'rumpun' => $rumpun,
            'activeMenu' => 'jurusan',
            ...$this->getAdminContext($request),
        ]);
    }

    public function prodiIndex(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->ensureAdmin($request)) {
            return $redirect;
        }

        $prodi = DB::table('master_prodi as p')
            ->leftJoin('master_jurusan as j', 'p.id_jurusan', '=', 'j.id_jurusan')
            ->select('p.id_prodi', 'p.nama_prodi', 'p.id_jurusan', 'j.nama_jurusan')
            ->orderBy('p.nama_prodi')
            ->get();

        $jurusan = DB::table('master_jurusan')
            ->select('id_jurusan', 'nama_jurusan')
            ->orderBy('nama_jurusan')
            ->get();

        return view('admin.prodi', [
            'prodi' => $prodi,
            'jurusan' => $jurusan,
            'activeMenu' => 'prodi',
            ...$this->getAdminContext($request),
        ]);
    }

    public function temaIndex(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->ensureAdmin($request)) {
            return $redirect;
        }

        $tema = DB::table('master_tema as t')
            ->leftJoin('master_rumpun as r', 't.id_rumpun', '=', 'r.id_rumpun')
            ->select('t.id_tema', 't.kode_tema', 't.nama_tema', 't.id_rumpun', 'r.nama_rumpun')
            ->orderBy('t.nama_tema')
            ->get();

        $rumpun = DB::table('master_rumpun')
            ->select('id_rumpun', 'nama_rumpun')
            ->orderBy('nama_rumpun')
            ->get();

        return view('admin.tema', [
            'tema' => $tema,
            'rumpun' => $rumpun,
            'activeMenu' => 'tema',
            ...$this->getAdminContext($request),
        ]);
    }

    public function usersIndex(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->ensureAdmin($request)) {
            return $redirect;
        }

        $users = DB::table('users')
            ->select('id_user', 'nama_lengkap', 'nim', 'username', 'email', 'role', 'status', 'created_at')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.users', [
            'users' => $users,
            'activeMenu' => 'users',
            ...$this->getAdminContext($request),
        ]);
    }

    public function updateUser(Request $request, int $id): RedirectResponse
    {
        if ($redirect = $this->ensureAdmin($request)) {
            return $redirect;
        }

        $actor = $request->session()->get('auth_user', []);
        $isSuperAdmin = $this->isSuperAdmin($actor['role'] ?? null);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'role' => ['nullable', 'in:superadmin,admin,mahasiswa'],
        ]);

        $target = DB::table('users')->where('id_user', $id)->first();
        if (!$target) {
            return redirect()->route('admin.users.index')->with('error', 'User tidak ditemukan.');
        }

        $updates = [
            'status' => $validated['status'],
        ];

        if ($isSuperAdmin && isset($validated['role'])) {
            if ((int) ($actor['id_user'] ?? 0) === $id && $validated['role'] !== 'superadmin') {
                return redirect()->route('admin.users.index')->with('error', 'Super Admin tidak dapat menurunkan role akun sendiri.');
            }

            $updates['role'] = $validated['role'];
        }

        if (!$isSuperAdmin && in_array((string) $target->role, ['admin', 'superadmin'], true)) {
            return redirect()->route('admin.users.index')->with('error', 'Admin biasa tidak dapat mengubah akun admin lain.');
        }

        DB::table('users')->where('id_user', $id)->update($updates);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function updateJurusan(Request $request, int $id): RedirectResponse
    {
        if ($redirect = $this->ensureAdmin($request)) {
            return $redirect;
        }

        $validated = $request->validate([
            'nama_jurusan' => ['required', 'string', 'max:100'],
            'id_rumpun' => ['nullable', 'integer', 'exists:master_rumpun,id_rumpun'],
        ]);

        DB::table('master_jurusan')
            ->where('id_jurusan', $id)
            ->update([
                'nama_jurusan' => trim($validated['nama_jurusan']),
                'id_rumpun' => $validated['id_rumpun'] ?? null,
            ]);

        return redirect()->route('admin.jurusan.index')->with('success', 'Data jurusan berhasil diperbarui.');
    }

    public function deleteJurusan(Request $request, int $id): RedirectResponse
    {
        if ($redirect = $this->ensureAdmin($request)) {
            return $redirect;
        }

        if (!$this->isSuperAdmin($request->session()->get('auth_user.role'))) {
            return redirect()->route('admin.jurusan.index')->with('error', 'Hanya Super Admin yang dapat menghapus jurusan.');
        }

        DB::transaction(function () use ($id) {
            DB::table('master_prodi')->where('id_jurusan', $id)->delete();
            DB::table('master_jurusan')->where('id_jurusan', $id)->delete();
        });

        return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil dihapus.');
    }

    public function updateProdi(Request $request, int $id): RedirectResponse
    {
        if ($redirect = $this->ensureAdmin($request)) {
            return $redirect;
        }

        $validated = $request->validate([
            'nama_prodi' => ['required', 'string', 'max:100'],
            'id_jurusan' => ['required', 'integer', 'exists:master_jurusan,id_jurusan'],
        ]);

        DB::table('master_prodi')
            ->where('id_prodi', $id)
            ->update([
                'nama_prodi' => trim($validated['nama_prodi']),
                'id_jurusan' => (int) $validated['id_jurusan'],
            ]);

        return redirect()->route('admin.prodi.index')->with('success', 'Data prodi berhasil diperbarui.');
    }

    public function deleteProdi(Request $request, int $id): RedirectResponse
    {
        if ($redirect = $this->ensureAdmin($request)) {
            return $redirect;
        }

        if (!$this->isSuperAdmin($request->session()->get('auth_user.role'))) {
            return redirect()->route('admin.prodi.index')->with('error', 'Hanya Super Admin yang dapat menghapus prodi.');
        }

        DB::table('master_prodi')->where('id_prodi', $id)->delete();

        return redirect()->route('admin.prodi.index')->with('success', 'Prodi berhasil dihapus.');
    }

    public function updateTema(Request $request, int $id): RedirectResponse
    {
        if ($redirect = $this->ensureAdmin($request)) {
            return $redirect;
        }

        $validated = $request->validate([
            'kode_tema' => ['nullable', 'string', 'max:50'],
            'nama_tema' => ['required', 'string', 'max:100'],
            'id_rumpun' => ['nullable', 'integer', 'exists:master_rumpun,id_rumpun'],
        ]);

        DB::table('master_tema')
            ->where('id_tema', $id)
            ->update([
                'kode_tema' => $validated['kode_tema'] ? trim($validated['kode_tema']) : null,
                'nama_tema' => trim($validated['nama_tema']),
                'id_rumpun' => $validated['id_rumpun'] ?? null,
            ]);

        return redirect()->route('admin.tema.index')->with('success', 'Data tema berhasil diperbarui.');
    }

    public function deleteTema(Request $request, int $id): RedirectResponse
    {
        if ($redirect = $this->ensureAdmin($request)) {
            return $redirect;
        }

        if (!$this->isSuperAdmin($request->session()->get('auth_user.role'))) {
            return redirect()->route('admin.tema.index')->with('error', 'Hanya Super Admin yang dapat menghapus tema.');
        }

        DB::table('master_tema')->where('id_tema', $id)->delete();

        return redirect()->route('admin.tema.index')->with('success', 'Tema berhasil dihapus.');
    }

    private function ensureAdmin(Request $request): ?RedirectResponse
    {
        $user = $request->session()->get('auth_user');
        $role = (string) ($user['role'] ?? '');
        $isAdmin = in_array($role, ['superadmin', 'admin', '1', 'Admin', 'SuperAdmin'], true);

        if (!$isAdmin) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Halaman ini hanya untuk admin.');
        }

        return null;
    }

    private function isSuperAdmin(string|int|null $role): bool
    {
        return in_array((string) $role, ['superadmin', 'SuperAdmin'], true);
    }

    private function getAdminContext(Request $request): array
    {
        $sessionUser = $request->session()->get('auth_user', []);

        return [
            'displayName' => $sessionUser['nama_lengkap'] ?? ($sessionUser['username'] ?? 'Admin'),
            'isSuperAdmin' => $this->isSuperAdmin($sessionUser['role'] ?? ''),
        ];
    }
}
