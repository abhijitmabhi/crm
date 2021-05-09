@extends('layouts.BaseView')

@section('header')
<h4 class="navbar-title">Nutzerübersicht</h4>
@endsection

@section('main-content')
<div class="card container-fluid">
    <div>
        <a role="button" class="btn btn-primary" href="users/create">Neuer Nutzer</a>
    </div>
    <br>
    <user-management
        :all-users="{{ $users }}"
    >
        {{ csrf_field() }}
    </user-management>
</div>
@endsection
