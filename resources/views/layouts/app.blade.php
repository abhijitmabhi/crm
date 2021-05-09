<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.head')

<body>
    <div id="app">
        <main style="min-height: calc(100vh - 92px)" class="d-flex flex-column justify-content-center">
            @yield('content')
        </main>
    <script src="{{mix('js/init_worker.js')}}"></script>
    </div>
</body>
</html>
