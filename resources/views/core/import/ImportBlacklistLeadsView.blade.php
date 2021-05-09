@extends('layouts.BaseView')

@section('header')
<h4 class="navbar-title">Blacklisted Leads importieren</h4>
@endsection

@section('main-content')
<b-card>
    <h3>Blacklist Leads importieren per Excel-Datei</h3>
    <import-blacklist-leads-component></import-blacklist-leads-component>
</b-card>
@endsection