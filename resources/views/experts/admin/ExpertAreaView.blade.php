@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Gebiet einstellen</h4>
@endsection

@section('main-content')
    <div class="card card-primary">
        <div class="col-md-12">
            <br />
            <h3 aling="center">Gebiet von {{ $expertName }}</h3>
            @if($conflicts)
            <br />
            <div>
                <h5 style="color: red"><i class="fas fa-exclamation-circle"></i> Konflikt mit bestehenden SAM Gebieten:</h5>
                <ul>
                    @foreach($conflicts as $name => $conflict)
                        <li>{{ $name }}: {{ $conflict }}</li>
                    @endforeach
                </ul>
            </div>
            @else
            <br />
            <div>
                <h5><i class="fas fa-info-circle"></i> Nützliche Hilfsmittel:</h5>
                <div><a href="https://www.suche-postleitzahl.org/plz-umkreis">Gebiet Visualisierung</a></div>
            </div>
            @endif
            <br />

            <form method="post" action="{{ route('SaveExpertArea') . "?expertId=$expertId"}}">
                @csrf
                <div class="form-group">
                    <label for="area">Postleitzahlen</label>
                    <textarea rows="5" type="text" name="area" class="form-control" placeholder="z.B. 76133, 77*">{{ $area }}</textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </form>
        </div>
    </div>
@endsection
