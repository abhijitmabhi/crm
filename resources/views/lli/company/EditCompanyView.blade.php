@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Kunde bearbeiten</h4>
@endsection

@section('main-content')
    <b-card header="Kunde bearbeiten" header-class="simple-card-header" header-tag="h4">
        <lli-company-form
                method="put"
                :should-redirect="false"
                submit-url="{{ route('api.companies.update', $company->id) }}">
        </lli-company-form>
    </b-card>
@endsection