@extends('admin.layout')

@section('title', 'Profil Admin')
@section('page_label', 'Profile')

@section('content')


<style>
/* ── ROOT SCOPE ─────────────────────────────────────────────── */
#pf-root { max-width: 980px; }

/* ── GRID ──────────────────────────────────────────────────── */
#pf-root .pf-grid {
    display: grid;
    grid-template-columns: 230px 1fr;
    gap: 1.5rem;
    align-items: start;
}
@media(max-width:768px){
    #pf-root .pf-grid { grid-template-columns: 1fr; }
}

/* ── AVATAR CARD ───────────────────────────────────────────── */
#pf-root .pf-side {
    background: #ffffff !important;
    border: 1px solid #e8eaf0 !important;
    border-radius: 16px !important;
    padding: 1.75rem 1.25rem 1.25rem !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    gap: .875rem !important;
    text-align: center !important;
    box-shadow: 0 2px 12px rgba(0,0,0,.05) !important;
    transform: none !important;
}

#pf-root .pf-avatar-ring {
    position: relative;
    width: 80px;
    height: 80px;
    flex-shrink: 0;
}

#pf-root .pf-avatar-circle {
    width: 80px !important;
    height: 80px !important;
    border-radius: 50% !important;
    background: #EEEDFE !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 30px !important;
    font-weight: 600 !important;
    color: #3C3489 !important;
    border: none !important;
    box-shadow: none !important;
}

#pf-root .pf-dot {
    position: absolute !important;
    bottom: 3px !important;
    right: 3px !important;
    width: 17px !important;
    height: 17px !important;
    border-radius: 50% !important;
    background: #1D9E75 !important;
    border: 2.5px solid #fff !important;
}

#pf-root .pf-name {
    font-size: 15px !important;
    font-weight: 600 !important;
    color: #111827 !important;
    margin: 0 0 2px !important;
    line-height: 1.3 !important;
}

#pf-root .pf-uname {
    font-size: 12.5px !important;
    color: #6b7280 !important;
    font-weight: 400 !important;
    margin: 0 !important;
}

#pf-root .pf-badge {
    display: inline-block !important;
    font-size: 10.5px !important;
    font-weight: 700 !important;
    padding: 3px 14px !important;
    border-radius: 20px !important;
    background: #EEEDFE !important;
    color: #3C3489 !important;
    letter-spacing: .05em !important;
    border: none !important;
}

#pf-root .pf-meta {
    width: 100% !important;
    border-top: 1px solid #f0f0f0 !important;
    padding-top: .875rem !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 3px !important;
    background: transparent !important;
}

#pf-root .pf-meta-row {
    display: flex !important;
    align-items: center !important;
    gap: 9px !important;
    padding: 5px 6px !important;
    border-radius: 8px !important;
    background: transparent !important;
    border: none !important;
    transform: none !important;
}

#pf-root .pf-meta-row i {
    font-size: 13px !important;
    color: #7F77DD !important;
    width: 15px !important;
    flex-shrink: 0 !important;
    text-align: center !important;
}

#pf-root .pf-meta-row span {
    font-size: 12.5px !important;
    color: #6b7280 !important;
    font-weight: 400 !important;
    word-break: break-all !important;
}

#pf-root .pf-joined {
    width: 100% !important;
    background: linear-gradient(135deg,#f5f3ff,#ede9fe) !important;
    border-radius: 12px !important;
    padding: 10px 12px !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    border: 1px solid #ddd8fb !important;
}

#pf-root .pf-joined-icon {
    width: 34px !important;
    height: 34px !important;
    border-radius: 9px !important;
    background: #534AB7 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex-shrink: 0 !important;
}

#pf-root .pf-joined-icon i {
    color: #fff !important;
    font-size: 15px !important;
}

#pf-root .pf-joined-label {
    display: block !important;
    font-size: 9.5px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: .07em !important;
    color: #9591C4 !important;
    margin-bottom: 2px !important;
}

#pf-root .pf-joined-val {
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #3C3489 !important;
}

/* ── FORM CARDS ────────────────────────────────────────────── */
#pf-root .pf-card {
    background: #ffffff !important;
    border: 1px solid #e8eaf0 !important;
    border-radius: 16px !important;
    overflow: hidden !important;
    margin-bottom: 1.25rem !important;
    box-shadow: 0 2px 12px rgba(0,0,0,.04) !important;
    transform: none !important;
}

#pf-root .pf-card:hover { transform: none !important; }

#pf-root .pf-card-head {
    padding: .9rem 1.25rem !important;
    border-bottom: 1px solid #f0f0f0 !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    background: #fff !important;
}

#pf-root .pf-num {
    width: 30px !important;
    height: 30px !important;
    border-radius: 9px !important;
    background: linear-gradient(135deg,#534AB7,#7F77DD) !important;
    color: #fff !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex-shrink: 0 !important;
    border: none !important;
}

#pf-root .pf-num span {
    color: #fff !important;
    font-size: 12px !important;
    font-weight: 700 !important;
}

#pf-root .pf-card-title {
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #111827 !important;
    margin: 0 !important;
    line-height: 1.2 !important;
}

#pf-root .pf-card-sub {
    font-size: 12px !important;
    color: #9ca3af !important;
    margin: 0 !important;
    font-weight: 400 !important;
}

#pf-root .pf-card-body {
    padding: 1.25rem !important;
    background: #fff !important;
}

#pf-root .pf-card-foot {
    padding: .875rem 1.25rem !important;
    border-top: 1px solid #f0f0f0 !important;
    display: flex !important;
    justify-content: flex-end !important;
    gap: 8px !important;
    background: #fff !important;
}

/* ── FIELDS ────────────────────────────────────────────────── */
#pf-root .pf-row {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 1rem !important;
    margin-bottom: 1rem !important;
}

@media(max-width:576px){
    #pf-root .pf-row { grid-template-columns: 1fr !important; }
}

#pf-root .pf-field {
    display: flex !important;
    flex-direction: column !important;
    gap: 5px !important;
    margin-bottom: 1rem !important;
}

#pf-root .pf-field:last-child,
#pf-root .pf-row .pf-field { margin-bottom: 0 !important; }

#pf-root .pf-label {
    font-size: 10.5px !important;
    font-weight: 700 !important;
    letter-spacing: .07em !important;
    text-transform: uppercase !important;
    color: #9ca3af !important;
    display: block !important;
    margin-bottom: 0 !important;
}

#pf-root .pf-input {
    height: 40px !important;
    border-radius: 10px !important;
    border: 1px solid #d1d5db !important;
    background: #f9fafb !important;
    font-size: 13.5px !important;
    padding: 0 12px !important;
    color: #374151 !important;
    width: 100% !important;
    outline: none !important;
    box-shadow: none !important;
    filter: none !important;
    transition: border-color .15s, box-shadow .15s !important;
}

#pf-root .pf-input:focus {
    border-color: #7F77DD !important;
    box-shadow: 0 0 0 3px rgba(127,119,221,.15) !important;
    background: #fff !important;
    color: #111827 !important;
}

#pf-root .pf-input.is-invalid {
    border-color: #E24B4A !important;
}

#pf-root .pf-err {
    font-size: 11.5px !important;
    color: #dc2626 !important;
    margin-top: 2px !important;
}

/* ── EYE WRAP ──────────────────────────────────────────────── */
#pf-root .pf-eye {
    position: relative !important;
}

#pf-root .pf-eye .pf-input {
    padding-right: 42px !important;
}

#pf-root .pf-eye-btn {
    position: absolute !important;
    right: 10px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    background: none !important;
    border: none !important;
    cursor: pointer !important;
    padding: 0 !important;
    box-shadow: none !important;
    width: auto !important;
    height: auto !important;
    border-radius: 0 !important;
    display: flex !important;
    align-items: center !important;
}

#pf-root .pf-eye-btn i {
    color: #bbb !important;
    font-size: 15px !important;
}

#pf-root .pf-eye-btn:hover i { color: #534AB7 !important; }

/* ── ALERT (info kuning) ────────────────────────────────────── */
#pf-root .pf-alert {
    display: flex !important;
    align-items: flex-start !important;
    gap: 9px !important;
    padding: 10px 14px !important;
    border-radius: 10px !important;
    background: #fffbeb !important;
    border: 1px solid #fde68a !important;
    margin-bottom: 1rem !important;
    line-height: 1.5 !important;
    box-shadow: none !important;
    color: #92400e !important;
}

#pf-root .pf-alert i {
    font-size: 14px !important;
    color: #f59e0b !important;
    flex-shrink: 0 !important;
    margin-top: 1px !important;
}

#pf-root .pf-alert span {
    font-size: 12.5px !important;
    color: #92400e !important;
}

/* ── STRENGTH BAR ──────────────────────────────────────────── */
#pf-root .pf-bar {
    display: flex !important;
    gap: 4px !important;
    margin: 6px 0 3px !important;
}

#pf-root .pf-seg {
    height: 3px !important;
    flex: 1 !important;
    border-radius: 2px !important;
    background: #e5e7eb !important;
    transition: background .2s !important;
}

#pf-root .pf-bar-label {
    font-size: 11px !important;
    color: #9ca3af !important;
}

/* ── BUTTONS ───────────────────────────────────────────────── */
#pf-root .pf-btn-save {
    height: 38px !important;
    padding: 0 20px !important;
    border-radius: 10px !important;
    background: #534AB7 !important;
    color: #fff !important;
    border: none !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    box-shadow: 0 4px 14px rgba(83,74,183,.3) !important;
}

#pf-root .pf-btn-save i { color: #fff !important; }

#pf-root .pf-btn-save:hover {
    background: #3C3489 !important;
    box-shadow: 0 6px 18px rgba(60,52,137,.35) !important;
}

#pf-root .pf-btn-save:active { transform: scale(0.97) !important; }

#pf-root .pf-btn-dark {
    height: 38px !important;
    padding: 0 20px !important;
    border-radius: 10px !important;
    background: #1e293b !important;
    color: #fff !important;
    border: none !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    box-shadow: 0 4px 14px rgba(30,41,59,.2) !important;
}

#pf-root .pf-btn-dark i { color: #fff !important; }
#pf-root .pf-btn-dark:hover { background: #0f172a !important; }

#pf-root .pf-btn-cancel {
    height: 38px !important;
    padding: 0 16px !important;
    border-radius: 10px !important;
    background: transparent !important;
    color: #6b7280 !important;
    border: 1px solid #d1d5db !important;
    font-size: 13px !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    box-shadow: none !important;
}

#pf-root .pf-btn-cancel:hover { background: #f3f4f6 !important; }

#pf-root .pf-note {
    font-size: 12px !important;
    color: #9ca3af !important;
    margin: .75rem 0 0 !important;
}

/* ── DARK THEME ────────────────────────────────────────────── */
body.dark-theme #pf-root .pf-side,
body.dark-theme #pf-root .pf-card {
    background: #1e293b !important;
    border-color: #334155 !important;
}

body.dark-theme #pf-root .pf-card-head,
body.dark-theme #pf-root .pf-card-body,
body.dark-theme #pf-root .pf-card-foot {
    background: #1e293b !important;
    border-color: #334155 !important;
}

body.dark-theme #pf-root .pf-name,
body.dark-theme #pf-root .pf-card-title { color: #f8fafc !important; }

body.dark-theme #pf-root .pf-uname,
body.dark-theme #pf-root .pf-meta-row span,
body.dark-theme #pf-root .pf-card-sub { color: #94a3b8 !important; }

body.dark-theme #pf-root .pf-avatar-circle {
    background: #2d2a5e !important;
    color: #a5b4fc !important;
}

body.dark-theme #pf-root .pf-badge {
    background: #2d2a5e !important;
    color: #a5b4fc !important;
}

body.dark-theme #pf-root .pf-meta { border-top-color: #334155 !important; }

body.dark-theme #pf-root .pf-joined {
    background: linear-gradient(135deg,#1e293b,#162033) !important;
    border-color: #334155 !important;
}

body.dark-theme #pf-root .pf-joined-label { color: #7877b8 !important; }
body.dark-theme #pf-root .pf-joined-val   { color: #a5b4fc !important; }
body.dark-theme #pf-root .pf-dot          { border-color: #1e293b !important; }
body.dark-theme #pf-root .pf-label        { color: #64748b !important; }

body.dark-theme #pf-root .pf-input {
    background: #0f172a !important;
    border-color: #334155 !important;
    color: #f1f5f9 !important;
}

body.dark-theme #pf-root .pf-input:focus {
    background: #1e293b !important;
    border-color: #7F77DD !important;
}

/* ── ORANGE THEME ──────────────────────────────────────────── */
body.orange-theme #pf-root .pf-side,
body.orange-theme #pf-root .pf-card {
    background: #ffedd5 !important;
    border-color: #fdba74 !important;
}

body.orange-theme #pf-root .pf-card-head,
body.orange-theme #pf-root .pf-card-body,
body.orange-theme #pf-root .pf-card-foot {
    background: #ffedd5 !important;
    border-color: #fdba74 !important;
}

body.orange-theme #pf-root .pf-avatar-circle {
    background: #fed7aa !important;
    color: #9a3412 !important;
}

body.orange-theme #pf-root .pf-badge {
    background: #fed7aa !important;
    color: #9a3412 !important;
}

body.orange-theme #pf-root .pf-num {
    background: linear-gradient(135deg,#f97316,#ea580c) !important;
}

body.orange-theme #pf-root .pf-joined {
    background: linear-gradient(135deg,#fff7ed,#ffedd5) !important;
    border-color: #fdba74 !important;
}

body.orange-theme #pf-root .pf-joined-icon  { background: #f97316 !important; }
body.orange-theme #pf-root .pf-joined-label { color: #c2410c !important; }
body.orange-theme #pf-root .pf-joined-val   { color: #9a3412 !important; }
body.orange-theme #pf-root .pf-name         { color: #9a3412 !important; }
body.orange-theme #pf-root .pf-uname        { color: #c2410c !important; }
body.orange-theme #pf-root .pf-meta-row span{ color: #c2410c !important; }
body.orange-theme #pf-root .pf-meta-row i   { color: #f97316 !important; }
body.orange-theme #pf-root .pf-meta         { border-top-color: #fdba74 !important; }
body.orange-theme #pf-root .pf-card-title   { color: #9a3412 !important; }

body.orange-theme #pf-root .pf-input {
    background: #fff7ed !important;
    border-color: #fdba74 !important;
    color: #9a3412 !important;
}

body.orange-theme #pf-root .pf-input:focus {
    border-color: #f97316 !important;
    box-shadow: 0 0 0 3px rgba(249,115,22,.15) !important;
}

body.orange-theme #pf-root .pf-btn-save {
    background: #f97316 !important;
}
</style>

{{-- ================================================================
     HTML
================================================================ --}}
<div id="pf-root">

    <div class="mb-4">
        <h4 style="font-size:20px;font-weight:600;margin-bottom:2px;color:#111827 !important;">Profile saya</h4>
        <small style="color:#6b7280 !important;">Kelola informasi akun dan keamanan</small>
    </div>

    <div class="pf-grid">

        {{-- ── KIRI: avatar card ── --}}
        <div class="pf-side">

            <div class="pf-avatar-ring">
                <div class="pf-avatar-circle">
                    {{ strtoupper(substr($user['nama_lengkap'] ?? 'A', 0, 1)) }}
                </div>
                <div class="pf-dot"></div>
            </div>

            <div>
                <p class="pf-name">{{ $user['nama_lengkap'] ?? '-' }}</p>
                <p class="pf-uname">{{ '@' . ($user['username'] ?? '-') }}</p>
            </div>

            <span class="pf-badge">{{ strtoupper($user['role'] ?? 'Admin') }}</span>

            <div class="pf-meta">
                <div class="pf-meta-row">
                    <i class="bi bi-envelope"></i>
                    <span>{{ $user['email'] ?? '-' }}</span>
                </div>
                <div class="pf-meta-row">
                    <i class="bi bi-person-badge"></i>
                    <span>{{ $user['nip'] ?? ($user['nim'] ?? '—') }}</span>
                </div>
                <div class="pf-meta-row">
                    <i class="bi bi-shield-check"></i>
                    <span>{{ $user['hak_akses'] ?? 'Semua fitur' }}</span>
                </div>
            </div>

            <div class="pf-joined">
                <div class="pf-joined-icon">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div>
                    <span class="pf-joined-label">Bergabung sejak</span>
                    <span class="pf-joined-val">
                        @if(isset($user['created_at']))
                            {{ \Carbon\Carbon::parse($user['created_at'])->translatedFormat('F Y') }}
                        @else
                            Januari 2024
                        @endif
                    </span>
                </div>
            </div>

        </div>{{-- end kiri --}}

        {{-- ── KANAN: form cards ── --}}
        <div>

            {{-- CARD 01 — Informasi Akun --}}
            <div class="pf-card">
                <div class="pf-card-head">
                    <div class="pf-num"><span>01</span></div>
                    <div>
                        <p class="pf-card-title">Informasi akun</p>
                        <p class="pf-card-sub">Nama, username, dan email yang digunakan untuk login</p>
                    </div>
                </div>

                <div class="pf-card-body">
                    <form method="POST" action="{{ route('admin.profile.update') }}" id="formProfil">
                        @csrf

                        <div class="pf-row">
                            <div class="pf-field">
                                <label class="pf-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap"
                                    class="pf-input {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}"
                                    value="{{ old('nama_lengkap', $user['nama_lengkap'] ?? '') }}"
                                    placeholder="Nama lengkap">
                                @error('nama_lengkap')
                                    <span class="pf-err">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="pf-field">
                                <label class="pf-label">Username</label>
                                <input type="text" name="username"
                                    class="pf-input {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                    value="{{ old('username', $user['username'] ?? '') }}"
                                    placeholder="Username">
                                @error('username')
                                    <span class="pf-err">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="pf-field">
                            <label class="pf-label">Email Kampus</label>
                            <input type="email" name="email"
                                class="pf-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                value="{{ old('email', $user['email'] ?? '') }}"
                                placeholder="email@kampus.ac.id">
                            @error('email')
                                <span class="pf-err">{{ $message }}</span>
                            @enderror
                        </div>

                    </form>
                </div>

                <div class="pf-card-foot">
                    <button type="reset" form="formProfil" class="pf-btn-cancel">Batalkan</button>
                    <button type="submit" form="formProfil" class="pf-btn-save">
                        <i class="bi bi-floppy"></i> Simpan perubahan
                    </button>
                </div>
            </div>{{-- end card 01 --}}

            {{-- CARD 02 — Ubah Password --}}
            <div class="pf-card">
                <div class="pf-card-head">
                    <div class="pf-num"><span>02</span></div>
                    <div>
                        <p class="pf-card-title">Ubah password</p>
                        <p class="pf-card-sub">Gunakan kombinasi huruf besar, angka, dan simbol</p>
                    </div>
                </div>

                <div class="pf-card-body">
                    <form method="POST" action="{{ route('admin.profile.password') }}" id="formPassword">
                        @csrf

                        <div class="pf-alert">
                            <i class="bi bi-info-circle-fill"></i>
                            <span>Password lama diperlukan untuk memverifikasi identitas sebelum menyimpan password baru.</span>
                        </div>

                        <div class="pf-field">
                            <label class="pf-label">Password Lama</label>
                            <div class="pf-eye">
                                <input type="password" name="old_password" id="pfOldPw"
                                    class="pf-input {{ $errors->has('old_password') ? 'is-invalid' : '' }}"
                                    placeholder="Masukkan password saat ini" required>
                                <button type="button" class="pf-eye-btn" onclick="pfToggle('pfOldPw',this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('old_password')
                                <span class="pf-err">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pf-row">
                            <div class="pf-field">
                                <label class="pf-label">Password Baru</label>
                                <div class="pf-eye">
                                    <input type="password"
                                        name="new_password"
                                        id="pfNewPw"
                                        class="pf-input {{ $errors->has('new_password') ? 'is-invalid' : '' }}"
                                        placeholder="Min. 8 karakter"
                                        required
                                        oninput="pfStrength(this.value); pfCheckPassword();">
                                    <button type="button" class="pf-eye-btn" onclick="pfToggle('pfNewPw',this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <span class="pf-err">{{ $message }}</span>
                                @enderror
                                <div class="pf-bar">
                                    <div class="pf-seg" id="pfS1"></div>
                                    <div class="pf-seg" id="pfS2"></div>
                                    <div class="pf-seg" id="pfS3"></div>
                                    <div class="pf-seg" id="pfS4"></div>
                                </div>
                                <span class="pf-bar-label" id="pfSLabel">Belum diisi</span>
                            </div>

                            <div class="pf-field">
                                <label class="pf-label">Konfirmasi Password</label>
                                <div class="pf-eye">
                                    <input type="password"
                                        name="new_password_confirmation"
                                        id="pfConfPw"
                                        class="pf-input {{ $errors->has('new_password_confirmation') ? 'is-invalid' : '' }}"
                                        placeholder="Ulangi password baru"
                                        required
                                        oninput="pfCheckPassword()">
                                    <button type="button" class="pf-eye-btn" onclick="pfToggle('pfConfPw',this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <span class="pf-err" id="pfMatchErr"></span>
                                @error('new_password_confirmation')
                                    <span class="pf-err">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>{{-- end pf-row --}}

                        <p class="pf-note">Kamu akan tetap login setelah mengubah password.</p>

                    </form>
                </div>

                <div class="pf-card-foot">
                    <button type="submit" form="formPassword" class="pf-btn-dark">
                        <i class="bi bi-shield-lock"></i> Update password
                    </button>
                </div>
            </div>{{-- end card 02 --}}

        </div>{{-- end kanan --}}
    </div>{{-- end pf-grid --}}
</div>{{-- end pf-root --}}

@endsection

@push('scripts')
<script>
// ── TOGGLE PASSWORD VISIBILITY ───────────────────────────────
function pfToggle(id, btn) {
    const el = document.getElementById(id);
    const ic = btn.querySelector('i');
    if (el.type === 'password') {
        el.type = 'text';
        ic.className = 'bi bi-eye-slash';
    } else {
        el.type = 'password';
        ic.className = 'bi bi-eye';
    }
}

// ── PASSWORD STRENGTH ────────────────────────────────────────
function pfStrength(val) {
    const segs   = ['pfS1','pfS2','pfS3','pfS4'].map(id => document.getElementById(id));
    const label  = document.getElementById('pfSLabel');
    const colors = ['#E24B4A','#BA7517','#1D9E75','#0F6E56'];
    const labels = ['Lemah','Cukup','Kuat','Sangat kuat'];
    let s = 0;
    if (val.length >= 8)           s++;
    if (/[A-Z]/.test(val))         s++;
    if (/[0-9]/.test(val))         s++;
    if (/[^A-Za-z0-9]/.test(val))  s++;
    segs.forEach((seg, i) => seg.style.background = i < s ? colors[s - 1] : '#e5e7eb');
    label.textContent = val.length ? labels[s - 1] : 'Belum diisi';
    label.style.color = val.length ? colors[s - 1] : '#9ca3af';
}

// ── CONFIRM PASSWORD MATCH ───────────────────────────────────
function pfCheckPassword() {
    const newPw  = document.getElementById('pfNewPw');
    const confPw = document.getElementById('pfConfPw');
    const err    = document.getElementById('pfMatchErr');
    if (confPw.value === '') {
        confPw.classList.remove('is-invalid');
        err.textContent = '';
        return;
    }
    if (newPw.value !== confPw.value) {
        confPw.classList.add('is-invalid');
        err.textContent = 'Konfirmasi password tidak cocok';
    } else {
        confPw.classList.remove('is-invalid');
        err.textContent = '';
    }
}
// ── AUTO SCROLL KE ERROR PASSWORD ───────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    @if($errors->has('old_password') || $errors->has('new_password') || $errors->has('new_password_confirmation'))
        document.getElementById('formPassword')
            ?.closest('.pf-card')
            ?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    @endif
});
</script>
@endpush