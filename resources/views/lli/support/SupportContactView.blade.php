@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Kunden</h4>
@endsection

@section('main-content')
    <div class="row">
        <div class="col col-md-12">
            <b-card class="customer-card" style="width: 100%">
                <p class="h2 text-secondary">Support kontaktieren</p>
                <support-contact-form
                        company-id="{{Auth::user()->company->id}}"
                />
            </b-card>
        </div>
    </div>
@endsection