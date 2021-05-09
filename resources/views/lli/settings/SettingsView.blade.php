@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Einstellungen</h4>
@endsection

@section('main-content')
    <div class="row">
        <div class="col col-md-12">
            <b-card class="customer-card" style="width: 100%">
                <p class="h2 text-secondary">Benutzerkonto</p>
                <user-form
                        :user="{{Auth::User()}}"
                        :is-show-roles="false"
                >
                    {{ csrf_field() }}
                </user-form>
            </b-card>
            <br>
            <b-card class="customer-card" style="width: 100%">
                <p class="h2 text-secondary">Rechnungsinformation</p>
                <lli-company-form
                        :company="{{Auth::User()->company}}"
                        method="put"
                        :should-redirect="false"
                        submit-url="{{ route('api.companies.update', Auth::user()->company->id) }}">
                </lli-company-form>
            </b-card>
        </div>
    </div>
@endsection