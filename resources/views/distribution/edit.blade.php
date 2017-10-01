@extends('layout.base')

@section('title', 'Ubah Distribusi')

@section('content')
    @include('layout.message')
    <div class="row">
        <div class="col xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Ubah Distribusi</strong>
                </div>
                <form action="/distribution/edit" role="form" method="POST" enctype="multipart/form-data" class="form-vertical">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{ $distribution->id }}">
                    <div class="panel-body">
                        <div class="row form-group center-block" >
                            <label for="description"> Deskripsi: </label>
                            <input type="text" name="description" id="description" class="form-control" value="{{ $distribution->description }}">
                        </div>
                         <div class="row form-group center-block">
                            <label for="date-time"> Waktu Distribusi: </label>
                            <input type="datetime-local" name="date-time" id="date-time" class="form-control" value="{{ $distribution->date_time }}">
                        </div>
                        <div class="row form-group center-block">
                            <label for="deadline"> Batas Akhir (Deadline) Pengumpulan Pengumuman: </label>
                            <input type="datetime-local" name="deadline" id="deadline" class="form-control" value="{{ $distribution->deadline }}">
                        </div>
                        <div class="row form-group center-block">
                            <label for="media"> Jenis Media: </label>
                            <select name="media" id="media" class="form-control">
                                @foreach ($media as $medium)
                                <option value="{{ $medium->id }}" @if ($medium->id === $distribution->media_id) selected @endif>{{ $medium->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row form-group center-block"> 
                            <button type="submit" class="btn btn-default"> Ubah </button>
                        </div>
                    </div>
                </form>    
            </div>
        </div>
    </div>
@endsection