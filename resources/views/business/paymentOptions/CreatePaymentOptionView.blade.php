@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Zahlungsoption erstellen</h4>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <b-card header="Neue Zahlungsoption" header-class="simple-card-header" header-tag="h4">
                    <payment-option-create back-url="{{route('payment_options.index')}}" store-url="{{route('payment_options.store')}}">
                    </payment-option-create>
                </b-card>
            </div>
        </div>
    </div>
@endsection