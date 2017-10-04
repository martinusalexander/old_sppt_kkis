@extends('layout.base')

@section('title', 'Lihat Seluruh Pengumuman')

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
                    <strong>Seluruh Distribusi</strong>
                </div>
                <div class="panel-body">
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class='hr'>
                                <span class='hr-title'> Distribusi Online </span>
                            </div>
                        </div>
                    </div>
                    @foreach ($online_distributions as $distribution)
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <a class="btn btn-default btn-block" href="/announcementdistribution/view/{{ $distribution->id }}">
                                {{ $distribution->description }}
                            </a>
                        </div>
                    </div>
                    @endforeach
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class='hr'>
                                <span class='hr-title'> Distribusi Offline </span>
                            </div>
                        </div>
                    </div>
                    @foreach ($offline_distributions as $distribution)
                    <div class="row form-group center-block">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <a class="btn btn-default btn-block" href="/announcementdistribution/view/{{ $distribution->id }}">
                                {{ $distribution->description }} <br> ({{ $distribution->date_time }})
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection