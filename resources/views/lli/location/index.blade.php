@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Locations für {{$company->name}}</h4>
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <crud-table :row-data="{{$company->locations}}"
                :col-config="{name: 'Name', city: 'Ort', last_synced: 'Zuletzt aktuallisiert'}"
                read-url="/companies/{{$company->id}}/locations"></crud-table>
        </div>
    </div>
</div>
@endsection