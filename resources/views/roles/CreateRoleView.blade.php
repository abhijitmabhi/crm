@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Rolle hinzufügen</h4>
@endsection

@section('main-content')
    <div class="container-fluid card">
        <form action="{{route('roles.store')}}" method="Post" class="form-horizontal">
            @csrf
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Id</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Id">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Anzeigename</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="display_name" id="display_name"
                           placeholder="Anzeigename">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="permissions">Berechtigungen</label>
                <div class="col-sm-10">
                    <select id="permissions" name="permissions[]" class="form-control select-2" multiple>
                        @foreach (\LocalheroPortal\Models\User\Permission::asArray() as $permission =>
                        $display_name)
                            <option value="{{$permission}}">{{ucfirst($display_name)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Erstellen</button>
                </div>
            </div>
        </form>
    </div>
@endsection