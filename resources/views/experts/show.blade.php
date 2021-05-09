@extends('layouts.BaseView')
@section('header')
@if(Auth::user()->hasRole('expert'))
<h4 class="navbar-title">Kalender</h4>
@else
<h4 class="navbar-title">SAM Kalender</h4>
@endif
@endsection

@section('main-content')
<div class="container-fluid">
    <expert-show :is-expert="{{Auth::user()->hasRole('Expert') ? 'true' : 'false'}}" expert-id="{{$expert->id}}"
        lead-id="{{$lead}}" />
</div>
@endsection