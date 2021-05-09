@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Leads</h4>
@endsection

@section('main-content')
    <b-card>
        <lead-table></lead-table>
        <back-button></back-button>
    </b-card>
@endsection