
@guest
      <li class="nav-item">
          <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-out-alt"></i></a>
      </li>
      @if (Route::has('register'))
          <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
          </li>
      @endif
  @else
  <li  class="nav-item nav-special">
        <a id="minmax1" href="javascript:ShuffleMenu(1);"><i class="fas fa-expand-arrows-alt"></i></a>
        <a id="minmax2" href="javascript:ShuffleMenu(2);" style="display:none;"><i class="fas fa-compress-arrows-alt"></i></a>
  </li>
  <li class="nav-item dropdown">
      <a id="navbarDropdown" class="nav-link dropdown-toggle text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          {{ Auth::user()->name }} <span class="caret"></span>
      </a>

      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          {{-- <a class="dropdown-item" up-target=".content-wrapper" href="{{ url("/users/" . Auth::user()->id ) }}">Profil</a> --}}
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
            @if(Auth::user()->hasRole('expert'))
                <span onclick="copyStringToClipboard('{{Auth::user()->calendar_url}}')" class="dropdown-item" id="cal-link">Kalender (URL) kopieren</span>
            @endif
          <a class="dropdown-item" href="{{ route('logout') }}"
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
          </a>
      </div>
  </li>
  @endguest
