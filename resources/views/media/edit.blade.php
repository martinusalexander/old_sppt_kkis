@extends('layout.base')

@section('title', 'Ubah Media')

@section('extra_css')
<style>
#is-online-ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}
</style>
@endsection

@section('content')
    @include('layout.message')
    <div class="row">
        <div class="col xs-12 col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Ubah Media</strong>
                </div>
                <form action="/media/edit/" role="form" method="POST" class="form-vertical">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{ $media->id }}">
                    <div class="panel-body">
                        <div class="row form-group center-block">
                            <label for="name"> Nama Media: </label>
                            <input type="text" name="name" id="name" value="{{ $media->name }}" class="form-control">
                        </div>
                        <div class="row form-group center-block">
                            <label for="organization-name"> Jenis Media: </label>
                            <ul id="is-online-ul">
                                <li class="form-group"><label for="is-online-yes" class="radio-inline"><input type="radio" name="is-online" id="is-online-yes" value="yes" @if ($media->is_online) checked @endif> Online </label></li>
                                <li class="form-group"><label for="is-online-no" class="radio-inline"><input type="radio" name="is-online" id="is-online-no" value="no" @if (!$media->is_online) checked @endif> Offline </label></li>
                            </ul>
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