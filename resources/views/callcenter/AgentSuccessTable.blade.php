@extends('layouts.BaseView')

@section('nav-links')
@include('callcenter.partials.nav-links')
@endsection

@section('main-content')
<div class="card">
        <h4>Callcenter-Agents</h4>
        <p>
            Zeitraum:
            <strong class="text-primary">{{$startDate}}</strong>
            bis
            <strong class="text-primary">{{$endDate}}</strong>
        </p>

        <agent-success-table :agents="{{json_encode($users)}}" class="mb-4"></agent-success-table>

        <div class="d-flex align-items-center justify-content-center">
            {{ $agents->appends(['user_name' => $userName])->links('partials.pagination') }}
        </div>
</div>
@endsection