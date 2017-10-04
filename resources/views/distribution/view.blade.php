@extends('layout.base')

@section('title', 'Lihat Distribusi')

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

@section('extra_js')
<script>
    $(document).ready(function() {
        
    }
</script>
@endsection

@section('content')
    @include('layout.message')
    <div class="row">
        <div class="col xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Lihat Distribusi</strong>
                </div>
                <div class="panel-body">
                    <div><h2>Deskripsi Distribusi: </h2></div>
                    <div><h5> {{ $distribution->description }} </h5></div>
                    <hr>
                    <div><h2>Media: </h2></div>
                    <div>{{ $distribution->media_name }}</div>
                    <hr>
                    @if (!$distribution->is_online)
                    <div><h2>Waktu: </h2></div>
                    <div>{{ $distribution->date_time }}</div>
                    <hr>
                    <div><h2>Deadline: </h2></div>
                    <div>{{ $distribution->deadline }}</div>
                    <hr>
                    <div><h2>Status: </h2></div>
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
                    <div><h2>Pengumuman: </h2></div>
                    @if (count($announcements) !== 0)
                    <ol>
                        @foreach ($announcements as $announcement)
                        <li>
                            <h4>{{ $announcement->title }} </h4>
                            <pre>{{ $announcement->description }}</pre>
                            @if ($announcement->image_path !== null)
                            <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                            @endif
                        </li>
                        @endforeach
                    </ol>
                    @else
                    <div>Belum ada pengumuman</div>
                    @endif
                    @if (!$distribution->is_online)
                    <hr>
                    <div><h2>Pengumuman yang Tidak Dapat Ditampilkan: </h2></div>
                    @if (count($rejected_announcements) !== 0)
                    <ol>
                        @foreach ($rejected_announcements as $announcement)
                        <li>
                            <h4>{{ $announcement->title }} </h4>
                            <pre>{{ $announcement->description }}</pre>
                            @if ($announcement->image_path !== null)
                            <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                            @endif
                        </li>
                        @endforeach
                    </ol>
                    @else
                    <div>Belum ada pengumuman yang ditolak</div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection