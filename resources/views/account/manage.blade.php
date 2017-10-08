@extends('layout.base')

@section('title', 'Kelola Akun')

@section('extra_css')
<style>
    th[name='no-col'] {
        width: 5%;
    }
    th[name='name-col'] {
        width: 25%;
    }
    th[name='organization-col'] {
        width: 25%;
    }
    th[name='distributor-col'] {
        white-space: nowrap;
        width: 15%;
    }
    th[name='manager-col'] {
        white-space: nowrap;
        width: 15%;
    }
    th[name='admin-col'] {
        white-space: nowrap;
        width: 15%;
    }
</style>
@endsection

@section('content')
    @include('layout.message')
    <div class="row">
        <div>
            <h3><b> Kelola Akun </b></h3>
        </div>
        <table id="account-management-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th name="no-col"> No.</th>
                    <th name="name-col"> Nama </th>
                    <th name="organization-col" class="hidden-xs"> Nama Ranting/Unit </th>
                    <th name="distributor-col"> Distributor </th>
                    <th name="manager-col"> Manager </th>
                    <th name="admin-col"> Admin </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $account->name }}</td>
                    <td class="hidden-xs" name="organization-col">{{ $account->organization_name }}</td>
                    <td>
                        @if ($account->is_distributor)
                        <div class="text-center">&#x2714;</div> 
                        @if ($account->id !== $user->id)
                        <a class="btn btn-danger" href="/accountmanagement/set/distributor/{{ $account->id }}" onclick="return confirm('Apakah Anda yakin menurunkan user ini dari distributor?');"> Turunkan dari Distributor </a>
                        @endif
                        @else 
                        <div class="text-center">&#x2718;</div>
                        @if ($account->id !== $user->id)
                        <a class="btn btn-warning" href="/accountmanagement/set/distributor/{{ $account->id }}" onclick="return confirm('Apakah Anda yakin menaikkan user ini sebagai distributor?');"> Naikkan sebagai Distributor </a>
                        @endif
                        @endif
                    </td>
                    <td>
                        @if ($account->is_manager)
                        <div class="text-center">&#x2714;</div> 
                        @if ($account->id !== $user->id)
                        <a class="btn btn-danger" href="/accountmanagement/set/manager/{{ $account->id }}" onclick="return confirm('Apakah Anda yakin menurunkan user ini dari manajer?');"> Turunkan dari Manajer </a>
                        @endif
                        @else 
                        <div class="text-center">&#x2718;</div>
                        @if ($account->id !== $user->id)
                        <a class="btn btn-warning" href="/accountmanagement/set/manager/{{ $account->id }}" onclick="return confirm('Apakah Anda yakin menaikkan user ini sebagai manajer?');"> Naikkan sebagai Manajer </a>
                        @endif
                        @endif
                    </td>
                    <td>
                        @if ($account->is_admin)
                        <div class="text-center">&#x2714;</div> 
                        @if ($account->id !== $user->id)
                        <a class="btn btn-danger" href="/accountmanagement/set/admin/{{ $account->id }}" onclick="return confirm('Apakah Anda yakin menurunkan user ini dari admin?');"> Turunkan dari Admin </a>
                        @endif
                        @else 
                        <div class="text-center">&#x2718;</div>
                        @if ($account->id !== $user->id)
                        <a class="btn btn-warning" href="/accountmanagement/set/admin/{{ $account->id }}" onclick="return confirm('Apakah Anda yakin menaikkan user ini sebagai admin?');"> Naikkan sebagai Admin </a>
                        @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection