@extends('layout.base')

@section('title', 'Ubah Profil')

@section('content')
    @include('layout.message')
    <div class="row">
        <div class="col xs-12 col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
            <div cla
                </div>
                <form action="/updateprofile" role="form" method="POST" class="form-vertical">
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="row form-group center-block">
                            <label for="name"> Nama: </label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control" required>
                        </div>
                        <div class="row form-group center-block">
                            <label for="organization-name"> Nama Ranting/Unit: </label>
                            <input type="text" name="organization-name" id="organization-name" value="{{ $user->organization_name }}" class="form-control" required>
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