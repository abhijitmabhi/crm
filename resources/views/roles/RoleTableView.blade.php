@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Rollenübersicht</h4>
@endsection

@section('main-content')
    <div class="container-fluid card">
        <div class="my-4">
            <a role="button" class="btn btn-primary" href="roles/create">Neue Rolle</a>
        </div>
        <table class="table-striped table table-sm table-responsive-sm">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Aktionen</th>
            </tr>
            </thead>
            <tbody>
            @foreach($roles as $role)
                <tr>
                    <th scope="row" class="font-weight-bolder">{{$role->display_name}}</th>
                    <td>
                        <a up-target=".content-wrapper" href="/roles/{{$role->id}}" role="button"
                           class="text-info p-2" aria-label="Left Align">
                            <span class="fa fa-edit" aria-hidden="true"></span>
                        </a>
                        <a class="text-danger p-2" href="/roles/{{$role->id}}"
                           onclick="event.preventDefault(); if(confirm('Sind Sie sich sicher?')){document.getElementById('delete-form-{{$role->id}}').submit()} return false">
                            <span class="fas fa-trash" aria-hidden="true"></span>
                        </a>
                        <form id="delete-form-{{$role->id}}" action="/roles/{{$role->id}}" method="POST"
                              style="display: none;">
                            @csrf
                            <input type="hidden" name="_method" value="delete"/>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="d-flex align-items-center justify-content-center">
            {{ $roles->links('partials.pagination') }}
        </div>

    </div>
@endsection