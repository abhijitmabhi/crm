@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Kunde erstellen</h4>
@endsection

@section('main-content')
    <b-card header="Neuer Kunde" header-class="simple-card-header" header-tag="h4">
        <lli-company-form
                method="post"
                :should-redirect="true"
                submit-url="{{ route('api.companies.store') }}"
                redirect-url="{{ route('companies.show', '') }}">
        </lli-company-form>
    </b-card>
@endsection