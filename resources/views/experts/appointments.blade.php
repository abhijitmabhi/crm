@extends('layouts.BaseView')

@section('main-content')
<div class="container">
    <div class="row">
        <div class="col">
            <calendar :expert-id="{{$expert->id}}" />
        </div>
    </div>
</div>
@endsection