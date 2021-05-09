@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Standorte in Bearbeitung</h4>
@endsection

@section('main-content')
    <div>
        <unfinished-locations-table
                class="card"
                :all-unfinished-locations = "{{ $unfinishedLocations }}"
                initial-search-string="{{ request('searchTerm', '') }}"
        ></unfinished-locations-table>

        <unfinished-customers-table
                class="card mt-3"
                :companies-no-locations = "{{ $companiesNoLocations }}"
        ></unfinished-customers-table>
    </div>
@endsection