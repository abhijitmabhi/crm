@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Produkt bearbeiten</h4>
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <b-card header="{{$product->name}} bearbeiten" header-class="simple-card-header" header-tag="h4">
                <product-edit :form-data="{{$product}}" update-url="{{route('products.update', $product)}}"
                    update-method="put" >
                </product-edit>
            </b-card>
        </div>
    </div>
</div>
@endsection