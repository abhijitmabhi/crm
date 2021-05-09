@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Willkommen {{Auth::user()->name}}</h4>
@endsection

@section('main-content')
    <div class="d-block">
        @if(Auth::user()->hasRole('expert'))
            <expert-dashboard :expert-id="{{Auth::id()}}"></expert-dashboard>
        @endif
        @if(Auth::user()->hasRole('customer') && Auth::user()->company && Auth::user()->company->locations)
            <lli-customer-dashboard company-id="{{ Auth::user()->company->id }}"
                              :locations="{{ Auth::user()->company->locations }}"/>
        @endif
        @if(Auth::user()->hasRole('callcenter-agent'))
            <agent-dashboard :callagent-id="{{Auth::id()}}"/>
        @endif
        @can('index', \LocalheroPortal\Models\Callagent::class)
            {{-- Stuff exclusive for Callagents here --}}
        @endcan
        @can('index', \LocalheroPortal\Models\Expert::class)
            {{-- Stuff exclusive for Experts here --}}
        @endcan
    </div>
@endsection