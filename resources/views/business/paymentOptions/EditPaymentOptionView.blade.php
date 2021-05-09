@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Zahlungsoption bearbeiten</h4>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <b-card header="{{$option->name}} bearbeiten" header-class="simple-card-header" header-tag="h4">
                    <payment-option-edit :form-data="{{$option}}" update-url="{{route('payment_options.update', $option->id)}}"
                                  update-method="put">
                    </payment-option-edit>
                </b-card>
            </div>
        </div>
    </div>
@endsection