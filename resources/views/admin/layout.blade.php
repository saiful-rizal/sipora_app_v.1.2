<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIPORA | @yield('title', 'Admin')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom -->
    <link href="{{ asset('assets/css/admin-ux.css') }}" rel="stylesheet">

</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="brand d-flex gap-2 mb-4 align-items-center">

            <img src="{{ asset('assets/logo.png') }}" style="height:40px;width:auto;object-fit:contain">



        </div>


        <a href="{{ route('admin.dashboard') }}" class="{{ $activeMenu == 'dashboard' ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Beranda
        </a>


        <!-- MENU AKADEMIKA -->
<div class="menu-group">

    <button class="menu-toggle {{ str_contains($activeMenu, 'akademika') ? 'active' : '' }}"
        data-bs-toggle="collapse" data-bs-target="#menuAkademika">

        <i class="bi bi-mortarboard"></i> Manajemen Akademika
        <i class="bi bi-chevron-down ms-auto"></i>

    </button>

    <div id="menuAkademika" class="collapse {{ str_contains($activeMenu, 'akademika') ? 'show' : '' }}">

        <a href="{{ route('admin.jurusan.index') }}"
            class="submenu {{ $activeMenu == 'jurusan' ? 'active' : '' }}">
            Jurusan dan Prodi
        </a>

    
        <a href="{{ route('admin.tema.index') }}"
            class="submenu {{ $activeMenu == 'tema' ? 'active' : '' }}">
            Tema dan Rumpun
        </a>

    
    </div>

</div>


        <!-- MENU USER -->
        <div class="menu-group">

            <button class="menu-toggle {{ str_contains($activeMenu, 'users') ? 'active' : '' }}" data-bs-toggle="collapse"
                data-bs-target="#menuUser">

                <i class="bi bi-people"></i> Pengguna
                <i class="bi bi-chevron-down ms-auto"></i>

            </button>

            <div id="menuUser" class="collapse {{ str_contains($activeMenu, 'users') ? 'show' : '' }}">

                <a href="{{ route('admin.users.index') }}" class="submenu {{ $activeMenu == 'users' ? 'active' : '' }}">
                    Manajemen Data
                </a>

                <a href="{{ route('admin.users.report') }}"
                    class="submenu {{ $activeMenu == 'users_report' ? 'active' : '' }}">
                    Laporan
                </a>

            </div>

        </div>
  <!-- MENU DOKUMEN -->
        <div class="menu-group">

            <button class="menu-toggle {{ str_contains($activeMenu, 'documents') ? 'active' : '' }}"
                data-bs-toggle="collapse" data-bs-target="#menuDokumen">

                <i class="bi bi-file-earmark-text"></i> Dokumen
                <i class="bi bi-chevron-down ms-auto"></i>

            </button>

            <div id="menuDokumen" class="collapse {{ str_contains($activeMenu, 'documents') ? 'show' : '' }}">

                <a href="{{ route('admin.documents.index') }}"
                    class="submenu {{ $activeMenu == 'documents' ? 'active' : '' }}">
                    Manajemen Data
                </a>

                <a href="{{ route('admin.documents.report') }}"
                    class="submenu {{ $activeMenu == 'documents_report' ? 'active' : '' }}">
                    Laporan
                </a>

            </div>

        </div>

     

    </div>


    <!-- MAIN -->
    <div class="main">


        <!-- TOPBAR -->
        <div class="topbar">

            <h5>@yield('page_label')</h5>

            <div class="d-flex gap-3 align-items-center">

                <!-- SEARCH -->
                <div class="search-topbar">

                    <i class="bi bi-search"></i>

                    <input type="text" id="globalSearch" placeholder="Cari data...">

                </div>


    <!-- DROPDOWN -->
    <!-- GANTI BAGIAN DROPDOWN NOTIF YANG LAMA MENJADI INI -->
<div class="dropdown position-relative">

    <i class="bi bi-bell fs-5 dropdown-toggle notif-bell-icon"
       id="notifBell"
       data-bs-toggle="dropdown"
       style="cursor:pointer;"></i>

    <span id="notifCount"
        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notif-badge"
        style="font-size:10px; display:none;">
        0
    </span>

    <!-- DROPDOWN NOTIF -->
    <div class="dropdown-menu dropdown-menu-end notif-dropdown shadow border-0">

        <!-- HEADER -->
        <div class="notif-header">
            <div>
                <h6 class="mb-0">Notifikasi</h6>
                <small class="text-muted">Riwayat pemberitahuan terbaru</small>
            </div>

            <button class="btn btn-sm btn-light rounded-pill px-3"
                onclick="markAllRead()">
                Tandai Dibaca
            </button>
        </div>

        <!-- LIST -->
        <div id="notifList" class="notif-list-scroll">
            <div class="text-center text-muted small py-4">
                Tidak ada notifikasi
            </div>
        </div>

    </div>

</div>



                <!-- USER -->
                <div class="dropdown">

                    <div class="avatar dropdown-toggle" data-bs-toggle="dropdown" style="cursor:pointer">

                        {{ strtoupper(substr($displayName ?? 'A', 0, 1)) }}

                    </div>


                    <div class="dropdown-menu dropdown-menu-end p-3 shadow border-0"
                        style="width:240px;border-radius:16px">

                        <div class="mb-3">

                            <strong>{{ $displayName ?? 'Admin' }}</strong><br>

                            <small class="text-muted">
                                {{ $displayRole ?? 'Admin' }}
                            </small>

                        </div>

                        <hr class="my-2">

                        <a href="{{ route('admin.profile') }}" class="dropdown-item d-flex align-items-center gap-2">

                            <i class="bi bi-person"></i> Profile

                        </a>

                        <a href="#" 
   class="dropdown-item d-flex align-items-center gap-2"
   data-bs-toggle="modal"
   data-bs-target="#settingsModal">

   <i class="bi bi-gear"></i> Settings
</a>

                        <hr class="my-2">

<a href="#"
   class="dropdown-item d-flex align-items-center gap-2 text-danger"
   data-bs-toggle="modal"
   data-bs-target="#logoutModal">

    <i class="bi bi-box-arrow-right"></i> Logout

</a>

                    </div>

                </div>

            </div>

        </div>


        <!-- CONTENT -->
        <div class="content">

            @yield('content')

        </div>

    </div>



    <!-- MODAL LOGOUT -->
    <div class="modal fade" id="logoutModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h6 class="modal-title">
                        Konfirmasi Logout
                    </h6>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <div class="modal-body">

                    Anda akan keluar dari sistem. Lanjutkan?

                </div>


                <div class="modal-footer">

                    <button class="btn btn-secondary" data-bs-dismiss="modal">

                        Batal

                    </button>


                    <form method="POST" action="{{ route('auth.logout') }}">

                        @csrf

                        <button class="btn btn-danger">
                            Ya, Logout
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>



    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const searchInput = document.getElementById("globalSearch");

            if (!searchInput) return;

            searchInput.addEventListener("keyup", function() {

                const keyword = this.value.toLowerCase();

                const rows = document.querySelectorAll("tbody tr");

                rows.forEach(row => {

                    let text = row.innerText.toLowerCase();

                    row.querySelectorAll("input").forEach(input => {
                        text += " " + input.value.toLowerCase();
                    });

                    row.querySelectorAll("select").forEach(select => {
                        text += " " + select.options[select.selectedIndex].text
                        .toLowerCase();
                    });

                    row.style.display = text.includes(keyword) ? "" : "none";

                });

            });

        });
    </script>

<script>
let lastNotifIds = [];
let audioEnabled = false;

const notifSound = new Audio('/notif.mp3');
notifSound.preload = "auto";

// aktifkan audio setelah klik pertama
document.addEventListener("click", () => {
    audioEnabled = true;
}, { once:true });

function showThemeToast(msg){

    let div = document.createElement("div");
    div.className = "theme-toast";

    div.textContent = msg; // JANGAN innerHTML

    document.getElementById("themeToast").appendChild(div);

    setTimeout(() => {
        div.classList.add("show");
    }, 50);

    setTimeout(() => {
        div.remove();
    }, 2500);
}

/* GANTI FUNCTION renderNotifList() MENJADI INI */

function renderNotifList(data)
{
    const container = document.getElementById("notifList");

    if(data.length === 0){
        container.innerHTML = `
            <div class="text-center text-muted small py-5">
                <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                Tidak ada notifikasi
            </div>
        `;
        return;
    }

    let html = "";

    data.forEach(n => {

        let unread = n.is_read == 0 ? "unread" : "";

        html += `
        <div class="notif-item ${unread}"
             data-id="${n.id}"
             data-url="${n.url}">

            <div class="notif-wrap">

                <div class="notif-icon">
                    <i class="bi ${n.icon_class}"></i>
                </div>

                <div class="notif-body">
                    <div class="notif-title">${n.title}</div>
                    <div class="notif-message">${n.message}</div>
                    <span class="notif-time">${n.created_at}</span>
                </div>

            </div>

        </div>
        `;
    });

    container.innerHTML = html;

    document.querySelectorAll(".notif-item").forEach(item => {

        item.onclick = function(){

            const id  = this.dataset.id;
            const url = this.dataset.url;

            fetch(`/admin/notifications/read/${id}`,{
                method:"POST",
                headers:{
                    "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                window.location.href = url;
            });

        };

    });
}


/* OPTIONAL */
function markAllRead(){
    fetch('/admin/notifications/read-all',{
        method:'POST',
        headers:{
            "X-CSRF-TOKEN":
            document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(()=>loadNotif());
}


// ================= LOAD =================
function loadNotif()
{
    fetch('/admin/notifications/latest')
    .then(res => res.json())
    .then(data => {

        const badge = document.getElementById("notifCount");

        const unread = data.filter(n => n.is_read == 0).length;

        if(unread > 0){
            badge.style.display = "inline-block";
            badge.innerText = unread;
        }else{
            badge.style.display = "none";
        }

        // toast notif baru
        data.forEach(n => {
            if(!lastNotifIds.includes(n.id)){
                showToast(n);
            }
        });

        lastNotifIds = data.map(n => n.id);

        renderNotifList(data);

    });
}



// pertama kali buka halaman
fetch('/admin/notifications/latest')
.then(res => res.json())
.then(data => {

    lastNotifIds = data.map(n => n.id);

    renderNotifList(data);

});


// realtime
setInterval(loadNotif, 3000);
</script>
<script>
function saveSettings(){

    let theme = document.getElementById("themeSelect").value;
    let font = document.getElementById("fontSizeSelect").value;
    let zoom = document.getElementById("zoomSelect").value;

    localStorage.setItem("theme", theme);
    localStorage.setItem("fontSize", font);
    localStorage.setItem("zoom", zoom);

    applySettings();
}

function applySettings(){

    let theme = localStorage.getItem("theme") || "light";
    let font = localStorage.getItem("fontSize") || "16";
    let zoom = localStorage.getItem("zoom") || "1";

    document.body.classList.remove(
        "dark-theme",
        "blue-theme",
        "orange-theme"
    );

    if(theme == "dark") document.body.classList.add("dark-theme");
    if(theme == "blue") document.body.classList.add("blue-theme");
    if(theme == "orange") document.body.classList.add("orange-theme");

    document.body.style.fontSize = font + "px";
    document.body.style.zoom = zoom;
}

document.addEventListener("DOMContentLoaded", applySettings);
</script>
    @stack('scripts')
<div id="toastContainer"
style="
position:fixed;
top:20px;
right:20px;
z-index:9999;
display:flex;
flex-direction:column;
gap:12px;
"></div>
<div id="themeToast"></div>
    <script>
function showToast(notif) {
    const container = document.getElementById("toastContainer");

    const toast = document.createElement("div");
    toast.className = "toast-custom";

    toast.innerHTML = `
        <div class="toast-icon">
            <i class="bi bi-bell-fill"></i>
        </div>

        <div class="toast-content">
            <div class="toast-title">Notifikasi Baru</div>
            <div class="toast-message">${notif.message}</div>
        </div>

        <button class="toast-close">&times;</button>

        <div class="toast-progress"></div>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add("show");
    }, 50);

    // auto close
    setTimeout(() => {
        removeToast(toast);
    }, 5000);

    // tombol close
    toast.querySelector(".toast-close").onclick = () => {
        removeToast(toast);
    };

    // suara
    if (audioEnabled) {
        notifSound.currentTime = 0;
        notifSound.play().catch(()=>{});
    }
}

function removeToast(toast){
    toast.classList.remove("show");
    toast.classList.add("hide");

    setTimeout(()=>{
        toast.remove();
    },300);
}
</script>
<div class="modal fade" id="settingsModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content rounded-4 border-0 shadow">

<div class="modal-header">
<h5 class="modal-title">
<i class="bi bi-sliders"></i> Pengaturan Tampilan
</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<label class="mb-2">Tema</label>
<select class="form-select mb-3" id="themeSelect">
<option value="light">Light</option>
<option value="dark">Dark</option>
<option value="blue">Blue</option>
<option value="orange">Orange</option>
</select>

<label class="mb-2">Ukuran Font</label>
<select class="form-select mb-3" id="fontSizeSelect">
<option value="14">Kecil</option>
<option value="16">Normal</option>
<option value="18">Besar</option>
<option value="20">Extra Besar</option>
</select>

<label class="mb-2">Ukuran Tampilan</label>
<select class="form-select mb-3" id="zoomSelect">
<option value="0.9">90%</option>
<option value="1">100%</option>
<option value="1.1">110%</option>
<option value="1.25">125%</option>
</select>

</div>

<div class="modal-footer">
<button class="btn btn-primary w-100" onclick="saveSettings()">
Simpan Pengaturan
</button>
</div>

</div>
</div>
</div>

</body>

</html>
