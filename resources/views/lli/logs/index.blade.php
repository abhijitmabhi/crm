@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Locations für {{$company->name}}</h4>
@endsection

@section('main-content')
<div class="row">
    <div class="col">

        <ul class="list-unstyled">
            @foreach ($company->logs as $log)
            <li>
                <a href="{{route('companies.logs.show', ['company' => $company,'log' => $log])}}">{{$log->message}}</a>
            </li>
            @endforeach
        </ul>
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('lli-manager'))
        <b-card header="Neues Dokument hochladen" header-class="simple-card-header">
            <form action="{{route('companies.logs.store', ['company' => $company])}}" method="post">
                @csrf
                <p>
                    <label for="event">Grund</label>
                    <select name="event" id="event" class="form-control">
                        @foreach(LocalheroPortal\Models\LLI\LogEvent::asArray() as $value => $text)
                        <option value="{{$value}}">{{$text}}</option>
                        @endforeach
                    </select>
                </p>
                <p>
                    <label for="message">Beschreibung</label>
                    <input type="text" name="message" id="message" class="form-control">
                </p>
                <p>

                    <label for="file">Datei</label>
                    <input type="file" name="file" id="file" class="form-control">
                </p>
                <input type="submit" value="Hochladen" class="btn btn-secondary border border-secondary">
                <input type="reset" value="Zurücksetzen" class="btn btn-secondary border border-secondary">
            </form>
        </b-card>
        @endif
    </div>
</div>
@endsection