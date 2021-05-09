@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Invalid Leads</h4>
@endsection

@section('main-content')
<b-card header="Fix Leads">
    @if(Auth::user()->hasOnlyRole('expert'))
    <lead-invalid-fix only-expert="{{Auth::id()}}" />
    @else
    <lead-invalid-fix />
    @endif
</b-card>

@endsection