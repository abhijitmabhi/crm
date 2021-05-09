<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.head')

<body class="hold-transition">

<div id="app" class="wrapper min-vh-100">
@auth
    @include('partials.NavigationBarTop')
    @include('partials.sidebar')
@endauth

<!-- Content Wrapper. Contains page content -->
    <div id="content-wrapper" class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @yield ('content-header')
        </section>

        <section class="pre-content">
            @yield ('pre-content')
        </section>

        <!-- Main content -->
        <section class="content">

            @yield('main-content')

        </section>
        <!-- /.content -->
        <notifications :user-id="{{Auth::id()}}"/>
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        Bei Problemen mail an <a href="mailto:portal@localhero.de">portal@localhero.de</a>
    </footer>

    @include('partials.messages')
    <expert-confirm-appointments :expert-id="{{Auth::user()->id}}"></expert-confirm-appointments>

</div>

@routes
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ mix('js/init_worker.js')}} "></script>
@yield('scripts')
@stack('footer-scripts')
<script>

    function ShuffleMenu(obj) {

        if (obj == 1) {
            //Hide Menues
            document.getElementById("minmax1").style.display = "none";
            document.getElementById("minmax2").style.display = "";

            document.getElementById("main-sidebar").style.display = "none";
            document.getElementById("main-header").style.marginLeft = "20px";
            document.getElementById("content-wrapper").style.marginLeft = "20px";
        } else {
            //Show Menues
            document.getElementById("minmax1").style.display = "";
            document.getElementById("minmax2").style.display = "none";

            document.getElementById("main-sidebar").style.display = "";
            document.getElementById("main-header").style.marginLeft = "250px";
            document.getElementById("content-wrapper").style.marginLeft = "250px";
        }

    }

    @auth
        localStorage.setItem('Permissions', {!! json_encode(Auth::user()->getAllPermissionNames(), true) !!});
        localStorage.setItem('Roles', {!! json_encode(Auth::user()->getAllRoleNames(), true) !!});
    @endauth

</script>
</body>
</html>
