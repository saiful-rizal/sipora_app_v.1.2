@php
    $authUser = Auth::user();
  $sessionUser = session('auth_user', []);

  if ($authUser) {
    $userId = (int) ($authUser->id_user ?? 0);
    $username = $authUser->username ?? $authUser->name ?? 'Guest';
  } else {
    $userId = (int) ($sessionUser['id_user'] ?? 0);
    $username = $sessionUser['username'] ?? 'Guest';
  }

    $profilePhoto = null;
    if ($userId > 0) {
        $profilePhoto = \Illuminate\Support\Facades\DB::table('user_profile')
            ->where('id_user', $userId)
            ->value('foto_profil');
    }

    $avatarUrl = $profilePhoto ? asset('uploads/profile/' . $profilePhoto) : null;

    $parts = preg_split('/\s+/', trim($username)) ?: [];
    $initials = '';
    foreach ($parts as $part) {
      if ($part !== '') {
        $initials .= mb_strtoupper(mb_substr($part, 0, 1));
      }
      if (mb_strlen($initials) >= 2) {
        break;
      }
    }
    $initials = $initials !== '' ? $initials : 'G';
@endphp
<div class="header">
  <div>
    <h3>Laporan Turnitin</h3>
    <small>Monitor dan analisis skor kemiripan dokumen</small>
  </div>
  <div id="headerAvatarContainer">
    @if($avatarUrl)
      <img src="{{ $avatarUrl }}" alt="User Avatar">
    @else
      <div class="user-initials" title="{{ $username }}">{{ $initials }}</div>
    @endif
  </div>
</div>
