@extends('layout.base', ['hide_menu' => true])

@section('title', 'Atur Ulang (Reset) Password')

@section('extra_js')
<script>
    function validate_password() {
        if ($('#password').val() !== $('#password-confirmation').val()) {
            alert("Password tidak cocok.");
            $('#password').focus();
            return false;
        } 
    }
</script>
@endsection

@section('content')
    @include('layout.message')
    <div class="row">
        <div class="col xs-12 col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3><b>Atur Ulang (Reset) Password</b></h3>
                </div>
                <form action="/resetpassword" role="form" method="POST" class="form-vertical" onsubmit="return validate_password()">
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="row form-group center-block" >
                            <label for="email"> Email: </label>
                            <input type="text" name="email" id="email" value="{{ $user->email }}" readonly class="form-control">
                        </div>
                        <div class="row form-group center-block">
                            <label for="password"> Password baru: </label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="row form-group center-block">
                            <label for="password-confirmation"> Ulangi password baru: </label>
                            <input type="password" name="password-confirmation" id="password-confirmation" class="form-control" required>
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