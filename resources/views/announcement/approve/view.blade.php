@extends('layout.base')

@section('title', 'Lihat Pengumuman Sebelum Persetujuan')

@section('extra_css')
<style>
    pre {
        border: 0;
        background-color: transparent;
        padding: 0;
        margin: 0;
    }
    img {
        display: block;
        margin: auto;
        width: 50%; 
    }
</style>
@endsection

@section('content')
    @include('layout.message')
    <div class="row">
        <div class="col xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3><b>Setujui Pengumuman</b></h3>
                </div>
                <div class="panel-body">
                    <div><h4><b>Pembuat Pengumuman: </b></h4></div>
                    <div><h5> {{ $announcement->creator_name }} </h5></div>
                    <hr>
                    <div><h4><b>Isi Pengumuman: </b></h4></div>
                    <div><h5><b> {{ $announcement->title }} </b></h5></div>
                    <div><pre> {{ $announcement->description }} </pre></div>
                    <hr>
                    <div><h4><b>Waktu: </b></h4></div>
                    <div>{{ $announcement->date_time }}</div>
                    @if ($announcement->image_path !== null)
                    <hr>
                    <div><h4><b>Gambar Pendukung: </b></h4></div>
                    <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                    @endif
                    <hr>
                    <div><h4><b> Isi Pengumuman Tiap Media: </b></h4></div>
                    @if ($announcement->rotating_slide !== null ||
                         $announcement->mass_announcement !== null ||
                         $announcement->flyer !== null ||
                         $announcement->bulletin !== null ||
                         $announcement->website !== null ||
                         $announcement->facebook !== null ||
                         $announcement->instagram !== null)
                    <div class="panel-group" id="announcement-per-media">
                        @if ($announcement->rotating_slide == true)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    Rotating slide sebelum misa
                                    <span class="pull-right">
                                        <a class="btn btn-info btn-xs" data-toggle="collapse" data-parent="#announcement-per-media" href="#rotating-slide"> Lihat </a>
                                    </span>
                                </h4>
                            </div>
                            <div id="rotating-slide" class="panel-collapse collapse">
                                <div class="panel-body">
                                @if ($announcement->image_path !== null)
                                <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                                @else
                                Tidak ada gambar.
                                @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($announcement->mass_announcement !== null)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    Pengumuman Misa
                                    <span class="pull-right">
                                        <a class="btn btn-info btn-xs" data-toggle="collapse" data-parent="#announcement-per-media" href="#mass-announcement"> Lihat </a>
                                    </span>
                                </h4>
                            </div>
                            <div id="mass-announcement" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <pre>{{ $announcement->mass_announcement }}</pre>
                                    @if ($announcement->image_path !== null)
                                    <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($announcement->flyer == true)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    Flyer
                                    <span class="pull-right">
                                        <a class="btn btn-info btn-xs" data-toggle="collapse" data-parent="#announcement-per-media" href="#flyer"> Lihat </a>
                                    </span>
                                </h4>
                            </div>
                            <div id="flyer" class="panel-collapse collapse">
                                <div class="panel-body">
                                @if ($announcement->image_path !== null)
                                <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                                @else
                                Tidak ada gambar.
                                @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($announcement->bulletin !== null)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    Bulletin Dombaku
                                    <span class="pull-right">
                                        <a class="btn btn-info btn-xs" data-toggle="collapse" data-parent="#announcement-per-media" href="#bulletin"> Lihat </a>
                                    </span>
                                </h4>
                            </div>
                            <div id="bulletin" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <pre>{{ $announcement->bulletin }}</pre>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($announcement->website !== null)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    Website KKIS
                                    <span class="pull-right">
                                        <a class="btn btn-info btn-xs" data-toggle="collapse" data-parent="#announcement-per-media" href="#website"> Lihat </a>
                                    </span>
                                </h4>
                            </div>
                            <div id="website" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <pre>{{ $announcement->website }}</pre>
                                    @if ($announcement->image_path !== null)
                                    <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($announcement->facebook !== null)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    Facebook
                                    <span class="pull-right">
                                        <a class="btn btn-info btn-xs" data-toggle="collapse" data-parent="#announcement-per-media" href="#facebook"> Lihat </a>
                                    </span>
                                </h4>
                            </div>
                            <div id="facebook" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <pre>{{ $announcement->facebook }}</pre>
                                    @if ($announcement->image_path !== null)
                                    <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($announcement->instagram !== null)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    Instagram
                                    <span class="pull-right">
                                        <a class="btn btn-info btn-xs" data-toggle="collapse" data-parent="#announcement-per-media" href="#instagram"> Lihat </a>
                                    </span>
                                </h4>
                            </div>
                            <div id="instagram" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <pre>{{ $announcement->instagram }}</pre>
                                    @if ($announcement->image_path !== null)
                                    <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div> 
                    @else
                    <div>Tidak ada media yang digunakan untuk mendistribusikan pengumuman.</div>
                    @endif
                    <hr>
                    <a href="/announcement/approve/confirm/{{ $announcement->id }}" class="btn btn-default" onclick="return confirm('Apakah Anda yakin menyetujui pengumuman ini?\nPersetujuan ini tidak dapat dibatalkan.');">Setujui (tanpa revisi)</a>
                    <a href="/announcement/approve/edit/{{ $announcement->id }}" class="btn btn-default">Setujui (dengan revisi)</a>
                </div>
            </div>
        </div>
    </div>
@endsection