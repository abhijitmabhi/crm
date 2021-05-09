@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">{{$company->name}}</h4>
@endsection

@section('main-content')
<div class="row">
    <div class="col-lg-6">
    <b-card class="customer-card" style="width: 100%">
        <p class="h2 text-secondary">Firma</p>
        <lli-company-form
                :company="{{ $company }}"
                method="put"
                :should-redirect="false"
                submit-url="{{ route('api.companies.update', $company->id) }}">
        </lli-company-form>
    </b-card>
</div>
    <div class="col-lg-6 media-1528px-ptb-25">
    <b-card class="company-card" style="width: 100%">
        <p class="h2 text-secondary">Standorte</p>
        <lli-location-table company-id="{{$company->id}}"
            data-url="{{route('api.companies.locations.index', ['company' => $company])}}"
            read-url="{{route('companies.locations.index', ['company'=> $company])}}">
        </lli-location-table>
        <a class="btn btn-primary mt-4" href="{{ route('companies.locations.create', $company->id) }}">Erstellen</a>
    </b-card>
</div>

           {{-- <div class="flex-grow-1">
                <p class="h2 text-secondary">Dokumente</p>
                <lli-document-table company-id="{{$company->id}}"></lli-log-table>
            </div>  --}}

</div>
@endsection