<div class="main-sidebar-wrapper main-sidebar-wrapper-top">

    <a href="{{url('/')}}" class="align-items-center brand-link d-flex">
        <img src="{{url('/img/Local-Hero_Logo_RED.png')}}" alt="Localhero Logo" class="sidebar-logo">
    </a>

    @if(!Auth::user()->hasOnlyRole('fix-leads') && !Auth::user()->hasRole('customer') && !Auth::user()->hasOnlyRole('LLI_DATA_SCRAPER'))
        <li class="nav-item mb-2">
            <form id="{{$prefix}}-global-search-form" method="GET" action="{{route('search')}}">
                @csrf
                <search-auto-suggest
                        form-id="{{$prefix}}-global-search-form"
                        id="{{$prefix}}-global-search"
                        placeholder="Suche"
                        name="searchTerm"
                        :search-indexes="['Lead', 'Company']"
                        :search-preview="false"
                ></search-auto-suggest>
            </form>
        </li>
        @if(!Auth::user()->hasRole('lli-manager'))
        <li class="nav-item">
            <a href="{{route('map')}}" class="nav-link {{ Request::routeIs('map') ? 'active' : '' }}">
                <i class="nav-icon fas fa-map"></i>
                <p>Leadmap</p>
            </a>
        </li>
        @endif
        <li class="nav-item">
            <a href="{{route('calendar', ['user' => Auth::id()])}}"
               class="nav-link {{ Request::routeIs('calendar') ? ' active' : '' }}">
                <i class="far fa-calendar-alt"></i>
                <p>Kalender</p>
            </a>
        </li>
    @endif
    @if(Auth::user()->hasRole('customer'))
        <li class="nav-item">
            <a href="{{route('companies.statistics', ['company' => Auth::user()->company->id])}}"
               class="nav-link {{ route('companies.statistics', ['company' => Auth::user()->company->id]) ? ' active' : '' }}">
                <i class="far fa-chart-line"></i>
                <p>Übersicht</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/" class="nav-link">
                <i class="far fa-star"></i>
                <p>Bewertungen</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/" class="nav-link">
                <i class="far fa-newspaper"></i>
                <p>Beiträge</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/" class="nav-link">
                <i class="far fa-link"></i>
                <p>Citations</p>
            </a>
        </li>
    @endif
    @if(Auth::user()->hasRole('admin'))
        <li class="nav-item">
            <a href="{{route('changelog')}}" class="nav-link {{Request::routeIs('changelog')}}">
                <i class="fas fa-info-circle"></i>
                <p>Info</p>
            </a>
        </li>
    @endif
</div>

@if(!Auth::user()->hasRole('customer') && !Auth::user()->hasRole('lli-manager'))
<div class="main-sidebar-wrapper">
    <li class="nav-header font-weight-bolder">Leads</li>
    <li class="nav-item">
        <a href="{{route('experts.leads.create', ['expert' => Auth::user()])}}"
           class="nav-link {{Request::routeIs('experts.leads.create') ? ' active' : '' }}">
            <i class="fas fa-plus-square"></i>
            <p>Anlegen</p>
        </a>
    </li>
    @if(Auth::user()->hasRole('expert') || Auth::user()->hasRole('admin'))
        <li class="nav-item">
            <a href="{{route('experts.leads.index', ['expert' => Auth::user()])}}"
               class="nav-link {{Request::routeIs('experts.leads.index') ? ' active' : '' }}">
                <i class="nav-icon fas fa-search"></i>
                <p>Anzeigen</p>
            </a>
        </li>
        {{--            <li class="nav-item">--}}
        {{--                <a href="{{route('business.sales.appointment')}}"--}}
        {{--                   class="nav-link {{Request::routeIs('business.sales.appointment') ? ' active' : '' }}">--}}
        {{--                    <i class="nav-icon fas fa-handshake"></i>--}}
        {{--                    <p>Termin nachbereiten</p>--}}
        {{--                </a>--}}
        {{--            </li>--}}
    @endif
    @can('index', \LocalheroPortal\Models\Callagent::class)
        <li class="nav-item">
            <a href="{{route('callcenter.index')}}"
               class="nav-link {{ Request::routeIs('callcenter.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-phone"></i>
                <p>Anrufen</p>
            </a>
        </li>
    @endcan
    {{--        @can('generate-leads')--}}
    {{--            @if(!Auth::user()->hasRole('expert'))--}}
    {{--                <li class="nav-item">--}}
    {{--                    <a href="{{route('callcenter.batch-import')}}"--}}
    {{--                       class="nav-link {{Request::routeIs('callcenter.batch-import') ? 'active' : ''}}">--}}
    {{--                        <i class="nav-icon fas fa-funnel-dollar"></i>--}}
    {{--                        <p>Leads generieren</p>--}}
    {{--                    </a>--}}
    {{--                </li>--}}
    {{--            @endif--}}
    {{--        @endcan--}}
    @if(Auth::user()->hasRole('callcenter-agent'))
        <li class="nav-item">
            <a href="{{route('callcenter.recalls')}}" class="nav-link {{Request::routeIs('')}}">
                <i class="fas fa-calendar-day"></i>
                <p>Wiedervorlagen</p>
            </a>
        </li>
    @endif
    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('callcenter-supervisor') || Auth::user()->hasRole('manager'))
        <li class="nav-item">
            <a href="{{route('admin.getImportLeadsView')}}"
               class="nav-link {{Request::routeIs('admin.getImportLeadsView') ? 'active' : ''}}">
                <i class="fas fa-cloud-upload-alt"></i>
                <p>Importieren</p>
            </a>
        </li>
    @endif
</div>
@endif

@if(Auth::user()->hasRole('admin'))
    @can('review-sales')
        <div class="main-sidebar-wrapper">
            <li class="nav-header font-weight-bolder">Finanzen</li>
            <li class="nav-item">
                <a href="{{route('products.index')}}"
                   class="nav-link {{Request::routeIs('products.index') ? 'active' : ''}}">
                    <i class="nav-icon fas fa-box-open"></i>
                    <p>Produkte</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('payment_options.index')}}"
                   class="nav-link {{Request::routeIs('payment_options.index') ? 'active' : ''}}">
                    <i class="nav-icon fas fa-file-invoice-dollar"></i>
                    <p>Zahlungen</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('sales.reviews.index')}}"
                   class="nav-link {{Request::routeIs('sales.index') ? 'active' : ''}}">
                    <i class="nav-icon fas fa-file-contract"></i>
                    <p>Verträge</p>
                </a>
            </li>
        </div>
    @endcan
@endif

@if(!Auth::user()->hasRole('customer') && !Auth::user()->hasOnlyRole('LLI_DATA_SCRAPER'))
    @can('manage-company')
        <div class="main-sidebar-wrapper">
            <li class="nav-header font-weight-bolder">Kunden</li>
            <li class="nav-item">
                <a href="{{route('companies.index')}}"
                   class="nav-link {{Request::routeIs('companies.index') ? 'active' : ''}}">
                    <i class="nav-icon fas fa-search"></i>
                    <p>Anzeigen</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('location.unfinished')}}"
                   class="nav-link {{Request::routeIs('location.unfinished') ? 'active' : ''}}">
                    <i class="far fa-clock"></i>
                    <p>In Bearbeitung</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.check')}}"
                   class="nav-link {{Request::routeIs('customer.check') ? 'active' : ''}}">
                    <i class="fas fa-plus-square"></i>
                    <p>Anlegen</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('companies.import')}}"
                   class="nav-link {{Request::routeIs('companies.import') ? 'active' : ''}}">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Importieren</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('companies.problems')}}"
                   class="nav-link {{Request::routeIs('companies.problems') ? 'active' : ''}}">
                    <i class="nav-icon fas fa-exclamation-triangle"></i>
                    <p>Probleme</p>
                </a>
            </li>
        </div>
    @endcan
@endif

@if(Auth::user()->hasRole('customer'))
    <div class="main-sidebar-wrapper">
        <li class="nav-header font-weight-bolder">Statistik</li>
        <li class="nav-item">
            <a href="/"
               class="nav-link">
                <i class="far fa-trophy"></i>
                <p>Wettbewerb</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/"
               class="nav-link">
                <i class="far fa-search-location"></i>
                <p>Suchbegriffe</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/"
               class="nav-link">
                <i class="far fa-chart-network"></i>
                <p>Suchanfragen</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/"
               class="nav-link">
                <i class="far fa-hand-point-up"></i>
                <p>Nutzeraktionen</p>
            </a>
        </li>
    </div>

    <div class="main-sidebar-wrapper">
        <li class="nav-header font-weight-bolder">Module</li>
        <li class="nav-item">
            <a href="{{route('companies.show', Auth::user()->company->id)}}"
               class="nav-link {{Request::routeIs('companies.show') ? 'active' : ''}}">
                <i class="far fa-arrows-h"></i>
                <p>Link Interface</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/"
               class="nav-link">
                <i class="far fa-rocket"></i>
                <p>Lead Boost</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/"
               class="nav-link">
                <i class="fas fa-globe"></i>
                <p>Performance Page</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/"
               class="nav-link">
                <i class="far fa-space-shuttle"></i>
                <p>Sales Boost</p>
            </a>
        </li>
    </div>
@endif

@if (Auth::user()->hasRole('admin') || Auth::user()->hasPermission('ASSIGN_EXPERTS'))
    <div class="main-sidebar-wrapper">
        <li class="nav-header font-weight-bolder">Administration</li>
        @can('administer', \LocalheroPortal\Models\Expert::class)
            <li class="nav-item">
                <a href="{{route('admin.experts.index')}}"
                   class="nav-link {{ Request::routeIs('admin.experts.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <p>SAMs</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('admin.agents')}}"
                   class="nav-link {{ Request::routeIs('admin.agents') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-headset"></i>
                    <p>Agents</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/leads" class="nav-link {{ Request::routeIs('leads.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-address-book"></i>
                    <p>Leads</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{route('lead.category')}}"
                   class="nav-link {{ Request::routeIs('lead.category') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-building"></i>
                    <p>Branchen</p>
                </a>
            </li>
        @endcan

        @if (Auth::user()->hasRole('admin'))
            <li class="nav-item">
                <a href="/users" class="nav-link {{ Request::routeIs('users.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Nutzer</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="/roles" class="nav-link {{ Request::routeIs('roles.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-tag"></i>
                    <p>Rollen</p>
                </a>
            </li>
        @endif
    </div>
@endif

@if(Auth::user()->hasRole('LLI_DATA_SCRAPER') || Auth::user()->hasRole('admin'))
    <div class="main-sidebar-wrapper">
        <li class="nav-header font-weight-bolder">Scraping</li>
        <li class="nav-item">
            <a href="{{route('lli-data-scraping')}}" class="nav-link {{Request::routeIs('')}}">
                <i class="fas fa-calendar-day"></i>
                <p>Keyword Usage</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('lead.no-contact')}}"
               class="nav-link {{ Request::routeIs('lead.no-contact') ? 'active' : '' }}">
                <i class="nav-icon fas fa-fire"></i>
                <p>Invalid Leads</p>
            </a>
        </li>
    </div>
@endif
