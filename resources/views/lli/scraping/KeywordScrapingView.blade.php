@extends('layouts.BaseView')

@section('nav-links')
    <h4 class="navbar-title">Search Term Scraping</h4>
@endsection

@section('main-content')
       <lli-keyword-scraping
           :location="{{$location}}"
       >
       </lli-keyword-scraping>
@endsection