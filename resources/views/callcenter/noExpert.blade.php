@extends('layouts.BaseView')

@section('header')
@include('callcenter.partials.nav-links')
@endsection

@section('content-header')
<h1>Sie sind keinem SAM zugewiesen</h1>
@endsection

@section('main-content')
<div class="text-center" style="padding-top:100px;">
    <p>
        <img class="img-fluid mw-150" src="{{asset('img/callcenter/4.png')}}" alt="Ihnen ist kein SAM zugewiesen" />
    </p>
    Bitte wenden Sie sich an Ihren Vorgesetzten, um sich zuteilen zu lassen.
</div>
@endsection