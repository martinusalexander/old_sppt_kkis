@extends('layout.base')

@section('title', 'Daftar Pengumuman')

@section('extra_css')
<style>   
    th[name='manage-col'] {
        white-space: nowrap;
        width: 10%;
    }
    th[name='no-col'] {
        width: 5%;
    }
    th[name='title-col'] {
        width: 25%;
    }
    th[name='description-col'] {
        width: 60%;
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
        <div class="form-group">
            <a href="/announcement/create" class="btn btn-primary" role="button">Buat Pengumuman Baru</a>
        </div>
        <table id="announcements-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th name="no-col"> No.</th>
                    <th name="title-col"> Judul </th>
                    <th name="description-col" class="hidden-xs"> Deskripsi </th>
                    <th name="manage-col"> Kelola </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($present_announcements as $announcement)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $announcement->title }}</td>
                    <td class="hidden-xs" name="manage-col"><pre>{{ $announcement->description }}</pre></td>
                    <td>
                        <div class="list-group">
                            <a class="list-group-item list-group-item-info" href="/announcement/view/{{ $announcement->id }}"> Lihat </a>
                            <a class="list-group-item list-group-item-warning" href="/announcement/edit/{{ $announcement->id }}"> Ubah </a>
                            <a class="list-group-item list-group-item-danger" href="/announcement/delete/{{ $announcement->id }}" onclick="return confirm('Apakah Anda yakin menghapus pengumuman ini?\nPenghapusan ini tidak dapat dibatalkan.');"> Hapus </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection