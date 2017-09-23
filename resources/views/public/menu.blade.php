@extends('layout.base')

@section('title', 'Menu Utama')

@section('extra_css')
<style>
    div.hr {
      margin-top: 20px;
      margin-bottom: 20px;
      border: 0;
      border-top: 1px solid #eee;
      text-align: center;
      height: 0px;
      line-height: 0px;
    }

    .hr-title {
      background-color: #fff;
    }
</style>
@endsection

@section('content')
    @include('layout.message')
    <div class="row">
        <div class="col xs-12 col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Menu Utama</strong>
                </div>
                <div class="panel-body">
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/announcement/">
                            Kelola Pengumuman <br> (Buat/Ubah/Hapus)
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/announcementdistribution/view">
                            Lihat Seluruh Pengumuman
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class='hr'>
                                <span class='hr-title'> Panel Staff </span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/distribution/">
                            Kelola Distribusi <br> (Buat/Ubah/Hapus)
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/announcementdistribution/">
                                Kelola Pengumuman per Distribusi <br> (Kapabilitas Lanjutan)
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/announcementdistribution/download">
                            Unduh Pengumuman per Distribusi
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class='hr'>
                                <span class='hr-title'> Panel Admin </span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/accountmanagement/">
                            Kelola Akun
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class='hr'>
                                <span class='hr-title'> Login sebagai: <b>{{ $user->name }}</b> </span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/updateprofile/">
                            Ubah Profil
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/changepassword/">
                            Ubah Password
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/logout/">
                            Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
@endsection