<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>@yield('title') - Sistem Pengelolaan Pengumuman Terpadu KKIS</title>
        
        <!-- CSS -->
        <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/bootstrap-datetimepicker.min.css') }}">
        <!-- Optional CSS -->
        <link rel="stylesheet" href="{{ URL::asset('css/bootstrap-theme.min.css') }}">
        <!-- CSS for all pages -->
        <style>
            .btn { 
                white-space: normal;
            }
        </style>
        @yield('extra_css')
        
        <!-- JS -->
        <script type="text/javascript" src="{{ URL::asset('js/jquery-3.2.1.min.js') }}"></script>    
        <script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/moment.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
        @yield('extra_js')
    </head>
    <body>
        @include('layout.title')
        @if(!isset($hide_menu))
            @include('public.sidebarmenu')
        @endif
        <div id="main" class="container">
            @yield('content')
        </div>
    </body>
</html>
