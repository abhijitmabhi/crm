<nav id="main-header" class="main-header navbar navbar-expand bg-white navbar-light py-3">
  <div class="d-flex flex-column">
    @yield('header')
  </div>
  <ul class="navbar-nav ml-auto">
    @include('partials.NavigationTop')
  </ul>
</nav>

@include('partials.NavigationMobile')