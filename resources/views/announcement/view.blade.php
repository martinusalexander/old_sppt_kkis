@extends('layout.base')

@section('title', 'Lihat Pengumuman')

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
                    <strong>Lihat Pengumuman</strong>
                </div>
                <div class="panel-body">
                    <div><h2>Isi Pengumuman: </h2></div>
                    <div><h5><b> {{ $announcement->title }} </b></h5></div>
                    <div><pre> {{ $announcement->description }} </pre></div>
                    <hr>
                    <div><h2>Waktu: </h2></div>
                    <div>{{ $announcement->date_time }}</div>
                    @if ($announcement->image_path !== null)
                    <hr>
                    <div><h2>Gambar Pendukung: </h2></div>
                    <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                    @endif
                    <hr>
                    <div><h2>Isi Pengumuman Tiap Media: </h2></div>
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
                                    <a data-toggle="collapse" data-parent="#announcement-per-media" href="#rotating-slide">Rotating slide sebelum misa (klik untuk melihat)</a>
                                </h4>
                            </div>
                            <div id="rotating-slide" class="panel-collapse collapse">
                                @if ($announcement->image_path !== null)
                                <div class="panel-body"><img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat."></div>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if ($announcement->mass_announcement !== null)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#announcement-per-media" href="#mass-announcement">Pengumuman Misa (klik untuk melihat)</a>
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
                                    <a data-toggle="collapse" data-parent="#announcement-per-media" href="#flyer">Flyer (klik untuk melihat)</a>
                                </h4>
                            </div>
                            <div id="flyer" class="panel-collapse collapse">
                                @if ($announcement->image_path !== null)
                                <img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">
                                @endif
                            </div>
                        </div>
                        @endif
                        @if ($announcement->bulletin !== null)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#announcement-per-media" href="#bulletin">Bulletin Dombaku (klik untuk melihat)</a>
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
                                    <a data-toggle="collapse" data-parent="#announcement-per-media" href="#website">Website KKIS (klik untuk melihat)</a>
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
                                    <a data-toggle="collapse" data-parent="#announcement-per-media" href="#facebook">Facebook (klik untuk melihat)</a>
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
                                    <a data-toggle="collapse" data-parent="#announcement-per-media" href="#instagram">Instagram (klik untuk melihat)</a>
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
                </div>
            </div>
        </div>
    </div>
@endsection