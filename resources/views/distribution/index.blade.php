@extends('layout.base')

@section('title', 'Daftar Distribusi')

@section('extra_css')
<style>
    th[name='offline-no-col'] {
        width: 5%;
    }
    th[name='offline-description-col'] {
        width: 20%;
    }
    th[name='offline-datetime-col'] {
        width: 20%;
    }
    th[name='offline-deadline-col'] {
        width: 20%;
    }
    th[name='offline-status-col'] {
        width: 15%;
    }
    th[name='offline-media-col'] {
        width: 10%;
    }
    th[name='offline-manage-col'] {
        white-space: nowrap;
        width: 10%;
    }
    th[name='online-no-col'] {
        width: 5%;
    }
    th[name='online-description-col'] {
        width: 85%;
    }
    th[name='online-manage-col'] {
        white-space: nowrap;
        width: 10%;
    }
</style>
@endsection

@section('content')
    @include('layout.message')
    <div class="row">
        <h3><b>Distribusi Online</b></h3>
    </div>
    <div class="row">
        <table id="online-distribution-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th name="online-no-col"> No.</th>
                    <th name="online-description-col"> Deskripsi </th>
                    <th name="online-manage-col"> Kelola </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($online_distributions as $distribution)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $distribution->description }}</td>
                    <td>
                        <div class="list-group">
                            <a class="list-group-item list-group-item-info" href="/distribution/view/{{ $distribution->id }}"> Lihat </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row">
        <h3><b>Distribusi Offline</b></h3>
    </div>
    <div class="form-group">
        <a href="/distribution/create/" class="btn btn-primary" role="button">Buat Distribusi Offline Baru</a>
    </div>
    <div class="row">
        <table id="offline-distribution-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th name="offline-no-col"> No.</th>
                    <th name="offline-description-col"> Deskripsi </th>
                    <th name="offline-datetime-col"> Waktu Distribusi </th>
                    <th name="offline-deadline-col"> Batas Waktu (Deadline) </th>
                    <th name="offline-status-col" class="hidden-xs"> Status </th>
                    <th name="offline-media-col"> Jenis Media </th>
                    <th name="offline-manage-col"> Kelola </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($offline_distributions as $distribution)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $distribution->description }}</td>
                    <td>{{ $distribution->date_time }} </td>
                    <td>{{ $distribution->deadline }} </td>
                    <td class="hidden-xs">
                        @if (@$distribution->status === 'FINAL')
                        <span class="label label-danger">
                        @elseif (@$distribution->status === 'MENERIMA PENGUMUMAN')
                        <span class="label label-success">
                        @else
                        <span class="label label-warning">
                        @endif
                            {{ $distribution->status }}
                        </span>
                    </td>
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