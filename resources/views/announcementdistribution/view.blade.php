@extends('layout.base')

@section('title', 'Lihat Seluruh Pengumuman')

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
                    <h3><b>Lihat Pengumuman</b></h3>
                </div>
                <div class="panel-body">
                    <div><h4><b>Deskripsi Distribusi: </b></h4></div>
                    <div><h5><b> {{ $distribution->description }} </b></h5></div>
                    <hr>
                    <div><h4><b>Media: </b></h4></div>
                    <div>{{ $distribution->media_name }}</div>
                    <hr>
                    @if (!$distribution->is_online)
                    <div><h4><b>Waktu: </b></h4></div>
                    <div>{{ $distribution->date_time }}</div>
                    <hr>
                    <div><h4><b>Status: </b></h4></div>
                    <div>
                        @if (@$distribution->status === 'FINAL')
                        <span class="label label-danger">
                        @elseif (@$distribution->status === 'MENERIMA PENGUMUMAN')
                        <span class="label label-success">
                        @else
                        <span class="label label-warning">
                        @endif
                            {{ $distribution->status }}
                        </span>
                    </div>
                    <hr>
                    @endif
                    <div><h4><b>Pengumuman: </b></h4></div>
                    @if (count($announcements) !== 0)
                    <div class="panel-group" id="announcement-per-media">
                        @foreach ($announcements as $announcement)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                {{ $announcement->title }} 
                                <span class="pull-right">
                                    <a class="btn btn-info btn-xs" data-toggle="collapse" data-parent="#announcement-per-media" href="#announcement-{{ $loop->index }}"> Lihat </a>
                                </span>
                            </div>
                            <div id="announcement-{{ $loop->index }}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <pre>{{ $announcement->description }}</pre>
                                    @if ($announcement->image_path !== null)
                                    <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div>Belum ada pengumuman</div>
                    @endif
                    @if (!$distribution->is_online)
                    <hr>
                    <div><h2>Pengumuman yang Tidak Dapat Ditampilkan: </h2></div>
                    @if (count($rejected_announcements) !== 0)
                    <div class="panel-group" id="rejected-announcement-per-media">
                        @foreach ($rejected_announcements as $announcement)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                {{ $announcement->title }} 
                                <span class="pull-right">
                                    <a class="btn btn-info btn-xs" data-toggle="collapse" data-parent="#rejected-announcement-per-media" href="#rejected-announcement-{{ $loop->index }}"> Lihat </a>
                                </span>
                            </div>
                            <div id="rejected-announcement-{{ $loop->index }}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <pre>{{ $announcement->description }}</pre>
                                    @if ($announcement->image_path !== null)
                                    <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div>Belum ada pengumuman yang ditolak</div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection