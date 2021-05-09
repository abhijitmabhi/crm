@extends('layouts.BaseView')

@section('header')
@if($expert)
<h4 class="navbar-title">Leads für <span class="text-primary">{{$expert->name}}</span> importieren</h4>
@else
<h4 class="navbar-title">Leads importieren</h4>
@endif
@endsection

@section('main-content')
<lead-import expert-id="{{$expert->id ?? null}}"></lead-import>
@endsection