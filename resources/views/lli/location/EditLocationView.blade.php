@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">{{ $location->name }}</h4>
@endsection

@section('main-content')
    <lli-location-form
            method="put"
            :should-redirect="false"
            submit-url="{{ route('api.companies.locations.update', ['location' => $location->id, 'company' => $company->id]) }}"
            :location="{{$location}}"
            :all-categories="{{$allCategories}}"
            :all-citation-categories="{{$allCitationCategories}}"

            :languages="{{json_encode(LocalheroPortal\Models\LLI\SupportedLanguage::asArray())}}"
            :payment-methods="{{json_encode(LocalheroPortal\Models\LLI\PaymentMethod::asArray())}}"
            :company-id="{{$company->id}}"
            :is-user-customer="{{Auth::user()->hasRole('customer')  ? 'true' : 'false'}}"
            :active-tab="{{ request('activeTab', 0) }}"
            use-case="{{ request('useCase', '') }}">
    </lli-location-form>
@endsection