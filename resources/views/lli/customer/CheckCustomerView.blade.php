@extends('layouts.BaseView')

<script type="text/javascript">

</script>

@section('header')
    <h4 class="navbar-title">Hinzufügen: Kunde</h4>
@endsection

@section('main-content')


        <customer-check
                method="post"
                :should-redirect="false"
                submit-url="{{ route('api.search') }}"
                redirect-url="{{ route('companies.show', '') }}">
        </customer-check>


@endsection