@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Kalender</h4>
@endsection

@section('main-content')
    <div class="container-fluid @if(!$agentMode) calender_expert @endif">
        <user-calendar
                user-id="{{$user->id}}"
                :can-see-all-calendars="{{json_encode($agentMode)}}"
                user-name="{{$user->name}}"
                :user-role="{{Auth::user()->roles}}"
        />
    </div>
@endsection