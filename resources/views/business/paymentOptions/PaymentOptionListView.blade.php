@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Zahlungsoptionen</h4>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <payment-option-index></payment-option-index>
            </div>
        </div>
    </div>
@endsection