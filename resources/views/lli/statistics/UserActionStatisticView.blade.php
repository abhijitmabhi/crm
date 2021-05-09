@extends('layouts.BaseView')

@section('main-content')
    <detailed-useraction
            :locations="{{ Auth::user()->company->locations }}"
            :company-id="{{$company->id}}"
    >
    </detailed-useraction>
@endsection
