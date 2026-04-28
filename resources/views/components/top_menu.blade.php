<div class="top-menu-container">
  <div class="top-menu">
    <a href="{{ url('/upload') }}" class="menu-item {{ request()->is('upload*') ? 'active' : '' }}">
      <i class="bi bi-cloud-upload"></i>
      <span>Unggah Dokumen Baru</span>
    </a>
    <a href="{{ url('/documents/my') }}" class="menu-item {{ request()->is('documents/my*') ? 'active' : '' }}">
      <i class="bi bi-shield-check"></i>
      <span>Dokumen Saya</span>
    </a>
    <a href="{{ url('/documents/history') }}" class="menu-item {{ request()->is('documents/history*') ? 'active' : '' }}">
      <i class="bi bi-clock-history"></i>
      <span>Riwayat Upload</span>
    </a>
    <a href="{{ url('/documents/turnitin') }}" class="menu-item {{ request()->is('documents/turnitin*') ? 'active' : '' }}">
      <i class="bi bi-file-earmark-check"></i>
      <span>Laporan Turnitin</span>
    </a>
  </div>
</div>
