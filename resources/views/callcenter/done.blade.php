@extends('layouts.BaseView')

@section('header')
@include('callcenter.partials.nav-links')
@endsection

@section('content-header')
@endsection

@section('main-content')
<b-row>
    <b-col md="6">
        <b-card>
            <div class="d-flex flex-column align-items-center justify-content-center">
                <h1>Alles Erledigt</h1>
                <img class="img-fluid mw-150" src="{{asset('img/callcenter/1.png')}}" alt="Es gibt nichts zu tun" />
                <p>
                    Keine weiteren Leads für die Ihnen zugewiesenen SAMs.<br>
                    Bitte wenden Sie sich an Ihren Vorgesetzten.
                </p>
            </div>
        </b-card>
    </b-col>
</b-row>
@endsection