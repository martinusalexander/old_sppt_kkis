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
                            Buat/Ubah/Hapus Pengumuman
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/announcementdistribution/">
                            Lihat Seluruh Pengumuman
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class='hr'>
                                <span class='hr-title'> Panel Distributor Pengumuman </span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/distribution/">
                            Buat/Ubah/Hapus Distribusi
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/announcementdistribution/manage">
                            Tolak Pengumuman dalam Distribusi <br> (misalkan karena kuota tidak mencukupi)
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/announcementdistribution/manage">
                            Ubah Pengumuman dalam Distribusi <br> (misalkan karena ada kesalahan informasi)
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
                                <span class='hr-title'> Panel Manajer </span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/announcement/approve">
                            Setujui (dan Revisi) Pengumuman
                            </a>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class='hr'>
                                <span class='hr-title'> Panel Administrator </span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-default btn-block" href="/media/">
                            Buat/Ubah/Hapus Media
                            </a>
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