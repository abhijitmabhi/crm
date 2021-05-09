@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Standort erstellen</h4>
@endsection

@section('main-content')
    <lli-location-form
            method="post"
            :should-redirect="true"
            submit-url="{{ route('api.companies.locations.store', $company->id) }}"
            redirect-url="{{ route('companies.show', $company->id) }}"
            :all-categories="{{$allCategories}}"
            :all-citation-categories="{{$allCitationCategories}}"

            :languages="{{json_encode(LocalheroPortal\Models\LLI\SupportedLanguage::asArray())}}"
            :payment-methods="{{json_encode(LocalheroPortal\Models\LLI\PaymentMethod::asArray())}}"
            :company-id="{{$company->id}}"
            :is-user-customer="{{Auth::user()->hasRole('customer')  ? 'true' : 'false'}}"
    >
    </lli-location-form>
@endsection