@if(Auth::user()->hasRole('customer'))
    <li class="nav-item nav-item-top">
        <a href="{{route('support')}}">
            <i class="far fa-headset"></i>
        </a>
    </li>

    <li class="nav-item nav-item-top">
        <a href="{{route('settings')}}">
            <i class="far fa-cog"></i>
        </a>
    </li>
@else
    <notification-dropdown :user-id="{{Auth::id()}}"></notification-dropdown>

    <li class="nav-item nav-item-top">
        <a id="minmax1" href="javascript:ShuffleMenu(1);">
            <i class="far fa-expand-arrows"></i>
        </a>
        <a id="minmax2" href="javascript:ShuffleMenu(2);" style="display:none;">
            <i class="far fa-compress-arrows"></i>
        </a>
    </li>
@endif

@if(Auth::user()->company)
@endif

@if(Auth::user()->hasRole('expert'))
    <li class="nav-item nav-item-top">
        <a href="{{ Auth::user()->calendar_url }}" onclick="copyStringToClipboard('{{Auth::user()->calendar_url}}')" id="cal-link">
            <i class="far fa-calendar"></i>
        </a>
    </li>
@endif

<li class="nav-item nav-item-top">
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-link p-0">
            <i class="far fa-sign-out"></i>
        </button>
    </form>
</li>
