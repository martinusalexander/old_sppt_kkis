@extends('layout.base', ['hide_menu' => true])

@section('title', '500 Internal Server Error')

@section('extra_css')
<style>
    @media screen and (min-width: 768px) {
        #title {
            font-size: 120px;
        }
        #message {
            font-size: 36px;
        }
    }
    #title {
        padding: 20px;
    }
    #message {
        padding: 20px;
    }
    #follow-up {
        padding: 20px;
    }
    a {
        padding: 10px;
    }
    
</style>
@endsection

@section('content')
    @include('layout.message')
    <div class="row text-center">
        <h1 id='title'>500 ERROR</h1>
        <h2 id='message'>Internal Server Error</h2>
        <h5 id='follow-up'>Mohon hubungi administrator melalui email<a href="mailto:humas.intern@kkis.org?Subject=%5BSPPT%20KKIS%5D%20500%20Internal%20Server%20Error" target="_top">humas.intern@kkis.org</a></h5>
        <a class='btn btn-default btn-lg' href="/"> Kembali ke Menu Utama </a>
    </div>
@endsection