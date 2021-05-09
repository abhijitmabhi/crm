@extends('layouts.BaseView')

@section('header')
<h4 class="navbar-title">Suchergebnisse</h4>
@endsection

@section('main-content')
@if($searchTerm)
<p class="font-weight-bold text-dark">Suchergebnis für: {{$searchTerm}}</p>
@else
<p class="font-weight-bold text-dark">Kein Suchbegriff eingegben</p>
@endif
@if($leads->isNotEmpty() || $companies->isNotEmpty())
<b-card no-body>
    <b-tabs cards content-class="mt-3">
        @if($leads->isNotEmpty())
        <b-tab title="Leads ({{ $leads->total() }})" {{$active == "lead" ? "active" : ""}}>
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Firmenname</th>
                        <th scope="col">Tel</th>
                        <th scope="col">SAM</th>
                        <th scope="col">Ansprechpartner</th>
                        <th scope="col">Email</th>
                    </tr>
                </thead>
                @foreach ($leads as $lead)
                <tr>
                    <td>
                        @if(Auth::user()->hasRole('callcenter-agent'))
                        <a href="{{route("callcenter.show", ['lead' => $lead->id])}}">{{$lead->company_name}}</a>
                        @else
                        <a
                            href="{{route("callcenter.show", ['lead' => $lead->id, 'timer' => false])}}">{{$lead->company_name}}</a>
                        @endif
                    </td>
                    <td>
                        {{$lead->phone1}}
                    </td>
                    <td>
                        @if($lead->expert)
                        {{$lead->expert->name}}
                        @endif
                    </td>
                    <td>

                        {{$lead->contact_name}}
                    </td>
                    <td>
                        {{$lead->email}}
                    </td>
                </tr>
                @endforeach
            </table>
            {{$leads->links()}}
        </b-tab>
        @endif
        @if($companies->isNotEmpty())
        <b-tab title="Kunden ({{ $companies->total() }})" {{$active == "company" ? "active" : ""}}>
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Firmenname</th>
                        <th scope="col">Tel</th>
                        <th scope="col">Website</th>
                        <th scope="col">Adresse</th>
                        <th scope="col">Email</th>
                    </tr>
                </thead>
                @foreach ($companies as $company)
                <tr>
                    <td>
                        {{$company->name}}
                    </td>
                    <td>
                        {{$company->phone}}
                    </td>
                    <td>
                        {{$company->url}}
                    </td>
                    <td>
                        @if(count($company->locations) > 0)
                        {{$company->locations[0]->address}}, {{$company->locations[0]->city}}
                        @else
                        Keine Adresse vorhanden
                        @endif
                    </td>
                    <td>
                        {{$company->email}}
                    </td>
                </tr>
                @endforeach
            </table>
            {{$companies->links()}}
        </b-tab>
        @endif
    </b-tabs>
</b-card>
@else
<p>Es wurden keine Ergbnisse gefunden</p>
@endif
@endsection