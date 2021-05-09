@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Problematische Kunden</h4>
@endsection

@section('main-content')
    <problematic-locations-spinner
        :problem-locations="{{$problematicLocations}}"
    >

    </problematic-locations-spinner>
@endsection