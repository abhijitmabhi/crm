@extends('layouts.BaseView')

@section('header')
<h4 class="navbar-title">Leadmap</h4>
@endsection

@section('main-content')
<div class="card">
    <div class="pb-4">
        <div class="d-flex flex-column flex-md-row">
            <!--TODO: refactor into vue component-->
            <div class="col p-0 mr-md-2 mb-2 mb-md-0">
                <select-2 onchange="vueInstance.$refs['lh-map'].filter.status = this.value" name="" id="">
                    <option value="">Filter auswählen</option>
                    @foreach ($states as $status => $name)
                    <option @if($status==$filter) selected @endif value="{{$status}}">{{__("states.$name")}}</option>
                    @endforeach
                </select-2>
            </div>
            <div class="col p-0 ml-md-2">
                <select-2 onchange="vueInstance.$refs['lh-map'].filter.expert = this.value">
                    <option value="">SAM auswählen</option>
                    @foreach ($experts as $expert)
                    <option value="{{$expert->id}}">{{$expert->name}}</option>
                    @endforeach
                </select-2>
            </div>
            <div class="col p-0 ml-md-2">
                <select-2 onchange="vueInstance.$refs['lh-map'].filter.type = this.value">
                    <option value="">Nach Kunden/Leads filtern</option>
                    <option value="lead">Leads</option>
                    <option value="company">Kunden</option>
                </select-2>
            </div>
        </div>
    </div>
    <div class="map-wrap position-relative">
        <gmap
            ref="lh-map"
            id="map"
            class="relative"
            style="height: 1000px"
            pin-data="/api/map?status={{$filter}}"
            @if(Auth::user()->hasRole('expert'))
            :is-expert="true"
            @endif
            :user-id="{{Auth::id()}}"
        >

        </gmap>

        <div class="legend position-absolute">
            @foreach ($icons as $icon)
            <div class="p-2 d-flex justify-content-between">
                <span>{{$icon['status']}}: </span>
                <img height="32px" src="{{$icon['file']}}" alt="">
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection