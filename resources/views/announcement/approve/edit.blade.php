@extends('layout.base')

@section('title', 'Ubah Pengumuman Sebelum Persetujuan')

@section('extra_css')
<style>
    #is-routine-ul, #media-distribution-ul,
    #image-ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    #new-image-div {
        display: none;
    }
</style>
@endsection

@section('extra_js')
<script>
    $(document).ready(function() {
        // Show/hide div for image depending whether the action taken by the user
        $("input:radio[name=image]").change(function() {
            var value = $(this).val();
            if (value === "keep") {
                $("#old-image-div").show();
                $("#new-image-div").hide();
            } else if (value === "change") {
                $("#old-image-div").hide();
                $("#new-image-div").show();
            } else {
                $("#old-image-div").hide();
                $("#new-image-div").hide();
            }
        });
        // Show/hide div depending whether the media is selected
        $("#show-in-mass-announcement").change(function() {
            if ($(this).is(":checked")) {
                $("#mass-announcement-div").show();
            } else {
                $("#mass-announcement-div").hide();
            }
        });
        $("#show-in-bulletin").change(function() {
            if ($(this).is(":checked")) {
                $("#bulletin-div").show();
            } else {
                $("#bulletin-div").hide();
            }
        });
        $("#show-in-website").change(function() {
            if ($(this).is(":checked")) {
                $("#website-div").show();
            } else {
                $("#website-div").hide();
            }
        });
        $("#show-in-facebook").change(function() {
            if ($(this).is(":checked")) {
                $("#facebook-div").show();
            } else {
                $("#facebook-div").hide();
            }
        });
        $("#show-in-instagram").change(function() {
            if ($(this).is(":checked")) {
                $("#instagram-div").show();
            } else {
                $("#instagram-div").hide();
            }
        });
        // Set custom caption to readonly if customize option is selected
        $("#custom-mass-announcement").change(function() {
            if ($(this).is(":checked")) {
                $("#mass-announcement").attr("readonly", false);
            } else {
                $("#mass-announcement").attr("readonly", true);
                $("#mass-announcement").val($("#description").val());
            }
        });
        $("#custom-bulletin").change(function() {
            if ($(this).is(":checked")) {
                $("#bulletin").attr("readonly", false);
            } else {
                $("#bulletin").attr("readonly", true);
                $("#bulletin").val($("#description").val());
            }
        });
        $("#custom-website").change(function() {
            if ($(this).is(":checked")) {
                $("#website").attr("readonly", false);
            } else {
                $("#website").attr("readonly", true);
                $("#website").val($("#description").val());
            }
        });
        $("#custom-facebook").change(function() {
            if ($(this).is(":checked")) {
                $("#facebook").attr("readonly", false);
            } else {
                $("#facebook").attr("readonly", true);
                $("#facebook").val($("#description").val());
            }
        });
        $("#custom-instagram").change(function() {
            if ($(this).is(":checked")) {
                $("#instagram").attr("readonly", false);
            } else {
                $("#instagram").attr("readonly", true);
                $("#instagram").val($("#description").val());
            }
        });
        // Set the default description to the caption in each selected media
        // if the caption of that media is not customized
        $("#description").focusout(function () {
            if (!$("#custom-mass-announcement").is(":checked")) {
                $("#mass-announcement").val($("#description").val());
            }
            if (!$("#custom-bulletin").is(":checked")) {
                $("#bulletin").val($("#description").val());
            }
            if (!$("#custom-website").is(":checked")) {
                $("#website").val($("#description").val());
            }
            if (!$("#custom-facebook").is(":checked")) {
                $("#facebook").val($("#description").val());
            }
            if (!$("#custom-instagram").is(":checked")) {
                $("#instagram").val($("#description").val());
            }
        });
        // Bug fixes
        // Fill the textarea at the first time the media is selected
        $("#show-in-mass-announcement").change(function() {
            if ($("#mass-announcement").val() === "" && $("#description").val() !== "") {
                $("#mass-announcement").val($("#description").val());
            }
        });
        $("#show-in-bulletin").change(function() {
            if ($("#bulletin").val() === "" && $("#description").val() !== "") {
                $("#bulletin").val($("#description").val());
            }
        });
        $("#show-in-website").change(function() {
            if ($("#website").val() === "" && $("#description").val() !== "") {
                $("#website").val($("#description").val());
            }
        });
        $("#show-in-facebook").change(function() {
            if ($("#facebook").val() === "" && $("#description").val() !== "") {
                $("#facebook").val($("#description").val());
            }
        });
        $("#show-in-instagram").change(function() {
            if ($("#instagram").val() === "" && $("#description").val() !== "") {
                $("#instagram").val($("#description").val());
            }
        });
        $('#datetimepicker').datetimepicker({
            useCurrent: false,
            sideBySide: true,
            useStrict: true,
        });
    });
</script>
@endsection

@section('content')
    @include('layout.message')
    <div class="row">
        <div class="col xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3><b>Ubah Pengumuman sebelum Persetujuan</b></h3>
                </div>
                <form action="/announcement/approve/confirm/" role="form" method="POST" enctype="multipart/form-data" class="form-vertical">
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <input type="hidden" name="id" id="id" value="{{ $announcement->id }}">
                        <div class="row form-group center-block" >
                            <label for="title"> Judul: </label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $announcement->title }}" required>
                        </div>
                        <div class="row form-group center-block">
                            <label for="descrption"> Deskripsi: </label>
                            <textarea name="description" id="description" class="form-control" rows="5" required>{{ $announcement->description }}</textarea>
                        </div>
                        <div class="row form-group center-block">
                            <label> Waktu: <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#date-time-modal">Info</button></label>
                            <div class='input-group date' id='datetimepicker'>
                                <input type='text' class="form-control" name="date-time" id="date-time" value="{{ $announcement->date_time }}" required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <div class="modal fade" id="date-time-modal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Waktu Kegiatan</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bagian ini diisi dengan waktu kegiatan Anda.</p>
                                            <p><b>Penting:</b> Bagian ini digunakan untuk keperluan pengelolaan oleh komputer. Anda perlu menyebutkan waktu kegiatan Anda dalam deskripsi kegiatan.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row form-group center-block">
                            <label> Gambar Pendukung (contoh: flyer kegiatan): <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#image-modal">Info</button></label>
                            <ul id="image-ul">
                                <li class="form-group">
                                    <label for="image-keep" class="radio-inline"><input type="radio" name="image" id="image-keep" value="keep" checked> Gunakan file sebelumnya </label>
                                    <div id="old-image-div">@if ($announcement->image_path !== null)<img src="{{ $announcement->image_path }}" alt="Gambar tidak dapat dimuat.">@else <p>Tidak ada gambar dalam revisi sebelumnya.</p>@endif</div>
                                </li>
                                <li class="form-group">
                                    <label for="image-change" class="radio-inline"><input type="radio" name="image" id="image-change" value="change"> Ubah file (unggah file baru) </label>
                                    <div id="new-image-div"><input type="file" name="image-path" id="image-path" class="form-control"></input></div>
                                </li>
                                <li class="form-group">
                                    <label for="image-delete" class="radio-inline"><input type="radio" name="image" id="image-delete" value="deletechange"> Hapus file sebelumnya </label>
                                </li>
                            </ul>
                            
                            <div class="modal fade" id="image-modal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Gambar Pendukung Pengumuman/Kegiatan</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bagian ini diisi dengan gambar pendukung pengumuman/kegiatan Anda.</p>
                                            <ul>
                                                <li>Gambar pendukung ini mungkin akan ditampilkan dalam berbagai media distribusi pengumuman seperti rotating slide, pengumuman misa, Facebook dan Instagram</li>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row form-group center-block">
                            <label> Pengumuman Rutin: <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#is-routine-modal">Info</button></label>
                            <ul id="is-routine-ul">
                                <li class="form-group"><label for="is-routine-yes" class="radio-inline"><input type="radio" name="is-routine" id="is-routine-yes" value="yes" @if ($announcement->is_routine) checked @endif> Ya </label></li>
                                <li class="form-group"><label for="is-routine-no" class="radio-inline"><input type="radio" name="is-routine" id="is-routine-no" value="no" @if (!$announcement->is_routine) checked @endif> Tidak </label></li>
                            </ul>
                            <div class="modal fade" id="is-routine-modal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Kegiatan Rutin</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bagian ini diisi dengan frekwensi pengumuman/kegiatan Anda (rutin atau tidaknya pengumuman/kegiatan Anda).</p>
                                            <ul>
                                                <li>Kegiatan tidak rutin (misalnya kegiatan untuk maksud tertentu (ad-hoc) atau kegiatan tahunan) akan mendapat prioritas lebih tinggi dan jangka waktu distribusi lebih panjang.</li>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row form-group center-block">
                            <label> Media Distribusi Pengumuman: <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#media-distribution-modal">Info</button></label>
                            <ul id="media-distribution-ul">
                                <li class="checkbox">
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <label class="checkbox" for="show-in-rotating-slide"><input type="checkbox" id="show-in-rotating-slide" name="show-in-rotating-slide" @if ($announcement->rotating_slide == true) checked @endif>Rotating slide (sebelum misa)</label>
                                        </div>
                                    </div>                                    
                                </li>
                                <li class="checkbox">
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <label class="checkbox" for="show-in-mass-announcement"><input type="checkbox" id="show-in-mass-announcement" name="show-in-mass-announcement" @if ($announcement->mass_announcement !== null) checked @endif>Pengumuman misa (sebelum berkat penutup)</label>
                                        </div>
                                    </div>
                                    <div class="form-group" id="mass-announcement-div" @if ($announcement->mass_announcement === null) style="display: none;" @endif>
                                        <div  class="col-xs-11 col-xs-offset-1 col-sm-11 col-md-offset-1 col-md-11 col-md-offset-1 col-lg-11 col-lg-offset-1">
                                            <div class="form-group">
                                                <label class="checkbox" for="custom-mass-announcement"><input type="checkbox" id="custom-mass-announcement" @if ($announcement->mass_announcement !== null && $announcement->mass_announcement !== $announcement->description) checked @endif> Gunakan deskripsi tersendiri untuk pengumuman misa (sebelum berkat penutup)  </label>
                                            </div>
                                            <div class="form-group">
                                                <textarea name="mass-announcement" id="mass-announcement" class="form-control" rows="5" required @if (!($announcement->mass_announcement !== null && $announcement->mass_announcement !== $announcement->description)) readonly @endif>{{ $announcement->mass_announcement }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="checkbox">
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <label class="checkbox" for="show-in-flyer"><input type="checkbox" id="show-in-flyer" name="show-in-flyer" @if ($announcement->flyer == true) checked @endif>Flyer</label>
                                        </div>
                                    </div>
                                </li>
                                <li class="checkbox">
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <label class="checkbox" for="show-in-bulletin"><input type="checkbox" id="show-in-bulletin" name="show-in-bulletin" @if ($announcement->bulletin !== null) checked @endif>Bulletin Dombaku</label>
                                        </div>
                                    </div>
                                    <div class="form-group" id="bulletin-div" @if ($announcement->bulletin === null) style="display: none;" @endif>
                                        <div  class="col-xs-11 col-xs-offset-1 col-sm-11 col-md-offset-1 col-md-11 col-md-offset-1 col-lg-11 col-lg-offset-1">
                                            <div class="form-group">
                                                <label class="checkbox" for="custom-bulletin"><input type="checkbox" id="custom-bulletin" @if ($announcement->bulletin !== null && $announcement->bulletin !== $announcement->description) checked @endif> Gunakan deskripsi tersendiri untuk bulletin Dombaku </label>
                                            </div>
                                            <div class="form-group">
                                                <textarea name="bulletin" id="bulletin" class="form-control" rows="5" required @if (!($announcement->bulletin !== null && $announcement->bulletin !== $announcement->description)) readonly @endif>{{ $announcement->bulletin }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="checkbox">
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <label class="checkbox" for="show-in-website"><input type="checkbox" id="show-in-website" name="show-in-website" @if ($announcement->website !== null) checked @endif>Website KKIS</label>
                                        </div>
                                    </div>
                                    <div class="form-group" id="website-div" @if ($announcement->website === null) style="display: none;" @endif>
                                        <div  class="col-xs-11 col-xs-offset-1 col-sm-11 col-md-offset-1 col-md-11 col-md-offset-1 col-lg-11 col-lg-offset-1">
                                            <div class="form-group">
                                                <label class="checkbox" for="custom-website"><input type="checkbox" id="custom-website" @if ($announcement->website !== null && $announcement->website !== $announcement->description) checked @endif> Gunakan deskripsi tersendiri untuk website KKIS </label>
                                            </div>
                                            <div class="form-group">
                                                <textarea name="website" id="website" class="form-control" rows="5" required @if (!($announcement->website !== null && $announcement->website !== $announcement->description)) readonly @endif>{{ $announcement->website }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="checkbox">
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <label class="checkbox" for="show-in-facebook"><input type="checkbox" id="show-in-facebook" name="show-in-facebook" @if ($announcement->facebook !== null) checked @endif>Facebook</label>
                                        </div>
                                    </div>
                                    <div class="form-group" id="facebook-div" @if ($announcement->facebook === null) style="display: none;" @endif>
                                        <div  class="col-xs-11 col-xs-offset-1 col-sm-11 col-md-offset-1 col-md-11 col-md-offset-1 col-lg-11 col-lg-offset-1">
                                            <div class="form-group">
                                                <label class="checkbox" for="custom-facebook"><input type="checkbox" id="custom-facebook" @if ($announcement->facebook !== null && $announcement->facebook !== $announcement->description) checked @endif> Gunakan deskripsi tersendiri untuk Facebook </label>
                                            </div>
                                            <div class="form-group">
                                                <textarea name="facebook" id="facebook" class="form-control" rows="5" required @if (!($announcement->facebook !== null && $announcement->facebook !== $announcement->description)) readonly @endif>{{ $announcement->facebook }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="checkbox">
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <label class="checkbox" for="show-in-instagram"><input type="checkbox" id="show-in-instagram" name="show-in-instagram" @if ($announcement->instagram !== null) checked @endif>Instagram</label>
                                        </div>
                                    </div>
                                    <div class="form-group" id="instagram-div" @if ($announcement->instagram === null) style="display: none;" @endif>
                                        <div  class="col-xs-11 col-xs-offset-1 col-sm-11 col-md-offset-1 col-md-11 col-md-offset-1 col-lg-11 col-lg-offset-1">
                                            <div class="form-group">
                                                <label class="checkbox" for="custom-instagram"><input type="checkbox" id="custom-instagram" @if ($announcement->instagram !== null && $announcement->instagram !== $announcement->description) checked @endif > Gunakan deskripsi tersendiri untuk Instagram </label>
                                            </div>
                                            <div class="form-group">
                                                <textarea name="instagram" id="instagram" class="form-control" rows="5" required @if (!($announcement->instagram !== null && $announcement->instagram !== $announcement->description)) readonly @endif>{{ $announcement->instagram }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="modal fade" id="media-distribution-modal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Media Distribusi Kegiatan</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bagian ini diisi dengan media yang akan digunakan untuk mendistribusikan pengumuman Anda.</p>
                                            <ul>
                                                <li>Gunakan media yang sesuai dengan jenis pengumuman/kegiatan Anda.</li>
                                                <li>Gunakan media secukupnya (jangan pilih semua media jika memang kurang sesuai). </li>
                                                <li>Sesuaikan isi pengumuman untuk setiap media (Jika tidak diatur, isi pengumuman bawaan (default) untuk setiap media akan diatur sesuai dengan isi kolom deskripsi di atas.</li>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group center-block"> 
                            <button type="submit" class="btn btn-default" onclick="return confirm('Apakah Anda yakin menyetujui pengumuman ini?\nPersetujuan ini tidak dapat dibatalkan.');"> Setujui </button>
                        </div>
                    </div>
                </form>    
            </div>
        </div>
    </div>
@endsection