@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Standort einstellen</h4>
@endsection

@section('main-content')
    <div class="card card-primary">
        <div class="col-md-12">
            <br />
            <h3 aling="center">Standort von {{ $expertName }}</h3>
            <br />
            <div>
                <h5><i class="fas fa-info-circle"></i> Nützliche Hilfsmittel:</h5>
                <div><a href="https://www.calcmaps.com/de/map-coordinates/">Standort Koordinaten bestimmen</a></div>
                <div><a href="https://www.calcmaps.com/de/map-radius/">Radius Visualisierung</a></div>
            </div>
            <br />

            <form method="post" action="{{ route('expert.location.save') . "?expertId=$expertId"}}">
                @csrf
                <div class="form-group">
                    <label for="lat">Latitude (erster Wert)</label>
                    <input type="number" step="any" pattern="[0-9]+([,\.][0-9]+)?" name="lat" class="form-control" placeholder="Latitude" value="{{ $lat }}" />
                </div>
                <div class="form-group">
                    <label for="long">Longitude (zweiter Wert)</label>
                    <input type="number" step="any" pattern="[0-9]+([,\.][0-9]+)?" name="long" class="form-control" placeholder="Longitude" value="{{ $long }}" />
                </div>
                <div class="form-group">
                    <label for="radius">Radius (in km)</label>
                    <input type="number" step="1" name="radius" class="form-control" placeholder="Radius" value="{{ $radius }}" />
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </form>

            <form method="post" action="{{ route('expert.location.save') . "?expertId=$expertId"}}">
                @csrf
                <input type="hidden" name="lat" value="0">
                <input type="hidden" name="long" value="0">
                <input type="hidden" name="radius" value="0">
                <div class="form-group">
                    <button type="submit" class="btn btn-white">Zurücksetzen</button>
                </div>
            </form>
        </div>
    </div>
@endsection
