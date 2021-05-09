{{--TODO: rename file--}}

@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Termin Nachbereitung</h4>
@endsection

@section('main-content')
<b-card>
    <appointments-past :items="{{$leads->toJson()}}" />
</b-card>
@endsection