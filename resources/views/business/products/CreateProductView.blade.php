@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Produkt erstellen</h4>
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <b-card header="Neues Produkt" header-class="simple-card-header" header-tag="h4">
                <product-create back-url="{{route('products.index')}}" store-url="{{route('products.store')}}">
                </product-create>
            </b-card>
        </div>
    </div>
</div>
@endsection