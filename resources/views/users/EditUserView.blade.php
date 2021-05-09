@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Nutzer bearbeiten</h4>
@endsection

@section('main-content')
    <div class="card container-fluid">
        <user-form
                :all-roles="{{$all_roles}}"
                :user="{{$user}}"
                :user-roles="{{$user_roles}}"
            >
                {{ csrf_field() }}
            </user-form>
        </div>
    </div>
@endsection
