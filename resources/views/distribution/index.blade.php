@extends('layout.base')

@section('title', 'Daftar Distribusi')

@section('extra_css')
<style>
    th[name='no-col'] {
        width: 5%;
    }
    th[name='description-col'] {
        width: 20%;
    }
    th[name='datetime-col'] {
        width: 20%;
    }
    th[name='deadline-col'] {
        width: 20%;
    }
    th[name='status-col'] {
        width: 15%;
    }
    th[name='media-col'] {
        width: 10%;
    }
    th[name='manage-col'] {
        white-space: nowrap;
        width: 10%;
    }
</style>
@endsection

@section('content')
    @include('layout.message')
    <div class="row">
        <a href="/distribution/create/" class="btn btn-primary" role="button">Buat Distribusi Baru</a>
        <table id="distribution-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th name="no-col"> No.</th>
                    <th name="description-col"> Deskripsi </th>
                    <th name="datetime-col"> Waktu Distribusi </th>
                    <th name="deadline-col"> Batas Waktu (Deadline) </th>
                    <th name="status-col" class="hidden-xs"> Status </th>
                    <th name="media-col"> Jenis Media </th>
                    <th name="manage-col"> Kelola </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($distributions as $distribution)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $distribution->description }}</td>
                    <td>{{ $distribution->date_time }} </td>
                    <td>{{ $distribution->deadline }} </td>
                    <td class="hidden-xs">{{ $distribution->status }}</td>
                    <td>{{ $distribution->media_name }}</td>
                    <td>
                        <div class="list-group">
                            <a class="list-group-item list-group-item-info" href="/distribution/view/{{ $distribution->id }}"> Lihat </a>
                            <a class="list-group-item list-group-item-warning" href="/distribution/edit/{{ $distribution->id }}"> Ubah </a>
                            <a class="list-group-item list-group-item-danger" href="/distribution/delete/{{ $distribution->id }}" onclick="return confirm('Apakah Anda yakin menghapus distribusi ini?\nPenghapusan ini tidak dapat dibatalkan.');"> Hapus </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection