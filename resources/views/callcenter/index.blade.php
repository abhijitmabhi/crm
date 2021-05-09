@extends('layouts.BaseView')

@section('header')
    @include('callcenter.partials.nav-links')

    @if(!empty($recalls) && 0 < $recalls->count())
        <div class="nav-item dropdown">
            <a id="recall-dropdown" class="nav-link dropdown-toggle pl-0" href="#" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <span class="text-primary">Wiedervorlagen: </span>
            </a>
            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="recall-dropdown">
                @foreach ($recalls as $recall)
                    <agent-recall-item
                            class="dropdown-item"
                            :lead-id="{{$recall->id}}"
                            lead-name="{{$recall->company_name}}"></agent-recall-item>
                @endforeach
            </div>
        </div>
    @endif
@endsection

@section('main-content')
    <agent-phone
            expert-id="{{$expert->id}}"
            expert-avatar="{{$expert->avatar ? "/storage/avatars/$expert->avatar" : '/storage/avatars/default.png'}}"
            expert-name="{{$expert->name}}"
            expert-appointments="{{ $appointmentsCount }}"
            expert-leads="{{$leadsCount}}"
            lead-id="{{$lead->id}}"
            :timer="{{json_encode($timer)}}"
            :appointment-id="{{ $appointmentId }}"
            :user-id="{{ Auth::id() }}"
    >
    </agent-phone>
@endsection