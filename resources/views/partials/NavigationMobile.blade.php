<nav class="navbar navbar-expand-lg navbar-light bg-light m-md-none d-flex lh-mobile-navbar">
    <a class="navbar-brand" href="/">
        <img
                src="data:image/jpg;base64,{{base64_encode(file_get_contents(resource_path() . '/img/Local-Hero_Logo_RED.png'))}}"
                alt="" style="max-height: 30px">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            @navlinks(['prefix' => 'mobile'])
            @endnavlinks
            @if(Auth::user()->hasRole('expert'))
                <div class="main-sidebar-wrapper">
                    <li class="nav-item">
                    <span onclick="copyStringToClipboard('{{Auth::user()->calendar_url}}')" class="nav-link"
                          id="cal-link">
                      <i class="far fa-calendar"></i> Kalender URL kopieren
                    </span>
                    </li>
                </div>
            @endif
            <div class="main-sidebar-wrapper">
                @if(Auth::user()->hasRole('customer'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('support')}}">
                            <i class="far fa-headset"></i> Support
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="far fa-cog"></i> Einstellungen
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <form id="nav-logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn nav-link w-100 pl-2 ml-1 text-left">
                            <i class="far fa-sign-out"></i> {{ __('Logout') }}
                        </button>
                    </form>
                </li>
            </div>
        </ul>
    </div>
</nav>