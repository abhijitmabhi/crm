@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Citations bearbeiten</h4>
@endsection

@section('main-content')
    <location-citations-spinner
            :location = "{{$location}}"
    >
    </location-citations-spinner>
@endsection