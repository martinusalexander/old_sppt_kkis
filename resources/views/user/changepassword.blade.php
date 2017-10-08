@extends('layout.base')

@section('title', 'Ubah Password')

@section('content')
    @include('layout.message')
    <div class="row">
        <div class="col xs-12 col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3><b>Ubah Password</b></h3>
                </div>
                <form action="/changepassword/" role="form" method="POST" class="form-vertical">
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="row form-group center-block" >
                            <label for="old-password"> Password lama: </label>
                            <input type="password" name="old-password" id="old-password" class="form-control">
                        </div>
                        <div class="row form-group center-block">
                            <label for="new-password"> Password baru: </label>
                            <input type="password" name="new-password" id="new-password" class="form-control">
                        </div>
                        <div class="row form-group center-block">
                            <label for="new-password-confirmation"> Ulangi password baru: </label>
                            <input type="password" name="new-password-confirmation" id="new-password-confirmation" class="form-control">
                        </div>
                        <div class="row form-group center-block">
                            <div class="form-group">
                                <button type="submit" class="btn btn-default"> Ubah </button>
                            </div>
                        </div>
                    </div>
                </form>    
            </div>
        </div>
    </div> 
@endsection