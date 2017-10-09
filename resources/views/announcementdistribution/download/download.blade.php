@extends('layout.base')

@section('title', 'Unduh Pengumuman dalam Distribusi')

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
        <div class="col xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3><b>Unduh Pengumuman dalam Distribusi</b></h3>
                </div>
                <div class="panel-body">
                    @foreach ($distributions as $distribution)
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
                            <a class="btn btn-default btn-block" href="/announcementdistribution/download/{{ $distribution->id }}" target="_blank">
                                {{ $distribution->description }} <br> 
                                ({{ $distribution->date_time }})
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection