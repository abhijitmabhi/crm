@extends('layouts.BaseView')

@section('content-header')
<h4 class="navbar-title">Leads</h4>
@endsection

@section('main-content')
<div class="card">
    <div class="row">
        <div class="col">
            <expert-lead-list :expert-id="{{$expert->id}}"></expert-lead-list>
        </div>
    </div>
</div>
@endsection