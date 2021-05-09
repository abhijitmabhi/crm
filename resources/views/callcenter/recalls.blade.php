@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Wiedervorlagen</h4>
@endsection

@section('main-content')
    <div class="card">
        <h1>Wiedervorlagen</h1>
        <agent-recall-list user-id="{{Auth::id()}}" />
    </div>
    <div class="card mt-4">
        <h1>Termin Erforderlich</h1>
        <agent-appointment-needed-list :user-id="{{Auth::id()}}" />
    </div>
@endsection