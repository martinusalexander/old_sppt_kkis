<style>
    @media screen and (max-width: 768px) {
        body {
            transition: background-color .5s;
        }
    }
    #sidenav {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #111;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
    }
    #sidenav a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 15px;
        color: #818181;
        display: block;
        transition: 0.3s;
    }
    #sidenav a:hover {
        color: #f1f1f1;
    }
    #sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }
    #main {
        transition: margin-left .5s;
        padding: 16px;
    }
</style>

<script>
    function openNav() {
        document.getElementById("sidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("sidenav").style.width = "0";
    }
</script>

<div id="sidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="/">Menu Utama</a>
    <hr>
    <a href="/announcement">Buat/Ubah/Hapus Pengumuman</a>
    <a href="/announcementdistribution">Lihat Seluruh Pengumuman</a>
    @if (Auth::user()->is_distributor)
    <hr>
    <a href="/distribution">Buat/Ubah/Hapus Distribusi</a>
    <a href="/announcementdistribution/manage">Kelola Pengumuman dalam Distribusi</a>
    <a href="/announcementdistribution/download">Unduh Pengumuman dalam Distribusi</a>
    @endif
    @if (Auth::user()->is_manager)
    <hr>
    <a href="/announcement/approve">Setujui (dan Revisi) Pengumuman</a>
    @endif
    @if (Auth::user()->is_admin)
    <hr>
    <a href="/media">Buat/Ubah/Hapus Media</a>
    <a href="/accountmanagement">Kelola Akun</a>
    @endif
    <hr>
    <a href="/updateprofile">Ubah Profil</a>
    <a href="/changepassword">Ubah Password</a>
    <a href="/logout">Logout</a>
</div>

<div class="container">
  <span class="btn btn-default" style="font-size:20px; cursor:pointer;" onclick="openNav()">&#9776; Menu</span>
</div>
 
