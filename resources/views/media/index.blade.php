@extends('layout.base')

@section('title', 'Daftar Media')

@section('extra_css')
<style>
    th[name='manage-col'] {
        white-space: nowrap;
        width: 20%;
    }
    th[name='no-col'] {
        width: 15%;
    }
    th[name='title-col'] {
        width: 50%;
    }
    th[name='online-col'] {
        width: 15%;
    }
    
    pre {
        border: 0;
        background-color: transparent;
        padding: 0;
        margin: 0;
    }
</style>
@endsection

@section('content')
    @include('layout.message')
    <div class="row">
        <div class="col xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
            <a href="/media/create/" class="btn btn-primary" role="button">Buat Media Baru</a>
            <table id="media-table" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th name="no-col"> No.</th>
                        <th name="title-col"> Nama </th>
                        <th name="online-col"> Media Online </th>
                        <th name="manage-col"> Kelola </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($media as $medium)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $medium->name }}</td>
                        <td>@if ($medium->is_online) &#x2714; @else &#x2718; @endif</td>
                        <td>
                            <div class="list-group">
                                <a class="list-group-item list-group-item-warning" href="/media/edit/{{ $medium->id }}"> Ubah </a>
                                <a class="list-group-item list-group-item-danger" href="/media/delete/{{ $medium->id }}" onclick="return confirm('Apakah Anda yakin menghapus media ini?\nPenghapusan ini tidak dapat dibatalkan.');"> Hapus </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection