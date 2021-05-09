@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Callcenter-Agents</h4>
@endsection

@section('main-content')

    <meta charset="utf-8">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>


    <div class="container-fluid card">
        <form method="post" action="{{url('/admin/agents')}}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="form-group col-md-4">
                <strong>Anfangsdatum: </strong>
                <input class="date form-control" id="startDate" name="startDate" value="{{$startDate->toDateString()}}">
            </div>
            <div class="form-group col-md-4">
                <strong>Enddatum: </strong>
                <input class="date form-control" id="endDate" name="endDate" value="{{$endDate->toDateString()}}">
            </div>
            <div class="form-group col-md-4">
                <button type="submit" class="btn btn-primary">Aktualisieren</button>
            </div>
        </form>

        <br>
        <h5>Anzahl Termine: {{ $appointmentsWithLeads }}</h5>
        <h5>Ersttermine: {{ $firstAppointmentCount }}</h5>
        <h5>Verschobene Termine: {{ $movedAppointments }}</h5>
        <h5 hidden>Ohne Lead: {{ $appointmentsWithoutLeads }}</h5>
        <br>

        <table class="table-striped table table-sm table-responsive-sm">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Termine</th>
                <th scope="col">Blacklist</th>
                <th scope="col">Nicht Erreicht</th>
                <th scope="col">Kein Interesse</th>
                <th scope="col">Wiedervorlage</th>
                <th scope="col">Insgesamt</th>
            </tr>
            </thead>
            <tbody>
            @foreach($agentStatistics as $agentStatistic)
                <tr>
                    <th scope="row">{{$agentStatistic->name}}</th>
                    <th scope="row">{{$agentStatistic->appointment}}</th>
                    <th scope="row">{{$agentStatistic->blacklist}}</th>
                    <th scope="row">{{$agentStatistic->notReached}}</th>
                    <th scope="row">{{$agentStatistic->noInterest}}</th>
                    <th scope="row">{{$agentStatistic->recall}}</th>
                    <th scope="row">{{$agentStatistic->total}}</th>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        $('#startDate').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            orientation: 'bottom'
        });
        $('#endDate').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            orientation: 'bottom'
        });
    </script>
@endsection
