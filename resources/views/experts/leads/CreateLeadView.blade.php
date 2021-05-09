@extends('layouts.BaseView')

@section('content-header')
    <h4 class="navbar-title">Neuen Lead eingeben</h4>
@endsection

@section('main-content')
    <div class="card card-primary">
        <div class="card-body">
            <lead-create-form
                    :user-id="{{$expert->id}}"
                    :all-categories="{{$allCategories}}"
                    :all-experts="{{$allExperts}}"
                    route-success="{{route('experts.leads.create', $expert->id)}}"
                    @if(Auth::user()->hasRole('expert'))
                    :is-expert="true"
                    @endif
            >
            </lead-create-form>
        </div>
    </div>
@endsection
