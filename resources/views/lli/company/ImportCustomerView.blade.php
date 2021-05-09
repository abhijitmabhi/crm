@extends('layouts.BaseView')

@section('header')
<h4 class="navbar-title">Kunden importieren</h4>
@endsection

@section('main-content')
<b-card>
    <h3>Kunden importieren per Excel-Datei</h3>
    <lli-company-import></lli-company-import>
</b-card>
@endsection