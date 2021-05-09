@extends('layouts.BaseView')

@section('header')
<h4 class="navbar-title">Leads Übersicht</h4>
@endsection

@section('main-content')
<div class="card">
    <b-row>
        <b-col>
            <category-stats
                    :category-stats="{{ $data }}"
                    :shown-categories="{{ $data->map(function ($d) { return $d->category; }) }}"
            ></category-stats>
        </b-col>
    </b-row>
</div>
@endsection