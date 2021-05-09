@extends('layouts.BaseView')

@section('header')
<h4 class="navbar-title">{{$lead->company_name}}</h4>
@endsection

@section('main-content')
<div class="container">
    <lead-single lead-id="{{$lead->id}}"></lead-single>
</div>
@endsection