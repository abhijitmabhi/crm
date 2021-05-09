@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Statistiken für {{$company->name}}</h4>
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <lli-customer-dashboard company-id="{{$company->id}}" :locations="{{$locations}}"
                {{Auth::user()->hasRole('lli_manager') || Auth::user()->hasRole('admin') ? "can-edit-keywords" : ''}} />
        </div>
    </div>
</div>
@endsection