@extends('layout.base')

@section('title', 'Daftar Pengumuman')

@section('extra_css')
<style>
    th[name='admin-col'] {
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
        <table id="announcements-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th name="no-col"> No.</th>
                    <th name="title-col"> Judul </th>
                    <th name="description-col"class="hidden-xs"> Deskripsi </th>
                    <th name="admin-col"> Khusus Manajer dan Admin </th>
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
                            @if (!$announcement->is_approved) 
                            <a class="list-group-item list-group-item-info" href="/announcement/approve/view/{{ $announcement->id }}"> Lihat Sebelum Persetujuan </a>
                            @else
                            <a class="list-group-item list-group-item-success" href="#" disabled> Telah Disetujui </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection