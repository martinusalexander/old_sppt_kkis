@extends('layout.base')

@section('title', 'Atur Ulang (Reset) Password')

@section('content')
    @include('layout.message')
    <div class="row">
        <div class="col xs-12 col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Atur Ulang (Reset) Password</strong>
                </div>
                <form action="." role="form" method="POST" class="form-vertical">
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="row form-group center-block" >
                            <label for="email"> Email: </label>
                            <input type="text" name="email" id="email" value="{{ $user->email }}" readonly class="form-control">
                        </div>
                        <div class="row form-group center-block">
                            <label for="password"> Password baru: </label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="row form-group center-block">
                            <label for="password-confirmation"> Ulangi password baru: </label>
                            <input type="password" name="password-confirmation" id="password-confirmation" class="form-control">
                        </div>
                        <div class="row form-group center-block">
                            <div class="form-group">
                                <button type="submit" class="btn btn-default"> Atur Ulang (Reset) </button>
                            </div>
                        </div>
                    </div>
                </form>    
            </div>
        </div>
    </div> 
@endsection