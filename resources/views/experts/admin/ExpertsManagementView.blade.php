@extends('layouts.BaseView')

@section('header')
<h4 class="navbar-title">SAMs verwalten</h4>
@endsection

@section('main-content')
<div class="card">
    <expert-lead-stats
            route-pipeline-config="{{ route('expert.category.view') }}"
            route-location-config="{{ route('expert.location') }}"
            route-area-config="{{ route('GetExpertArea') }}"
            route-stats="{{ route('api.ExpertPipelineStats') }}"
            :stats="{{ $experts }}"
    ></expert-lead-stats>
</div>
@endsection