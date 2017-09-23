{{-- For message provided by HTTPResponse --}}
@if(isset($success_message))
    <div class="alert alert-success">
        <strong>Success! </strong> {{ $success_message }}
    </div>
@endif
@if(isset($warning_message))
    <div class="alert alert-warning">
        <strong>Warning! </strong> {{ $warning_message }}
    </div>
@endif
@if(isset($error_message))
    <div class="alert alert-danger">
        <strong>Error! </strong> {{ $error_message }}
    </div>
@endif

{{-- For message provided by HTTPRedirect --}}
@if(session('success_message') !== null)
    <div class="alert alert-success">
        <strong>Success! </strong> {{ session('success_message') }}
    </div>
@endif
@if(session('warning_message') !== null)
    <div class="alert alert-warning">
        <strong>Warning! </strong> {{ session('warning_message') }}
    </div>
@endif
@if(session('error_message') !== null)
    <div class="alert alert-danger">
        <strong>Error! </strong> {{ session('error_message') }}
    </div>
@endif