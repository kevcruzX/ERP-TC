@php
    $users=\Auth::user();
    $profile=asset(Storage::url('uploads/avatar/'));
    $currantLang = $users->currentLanguage();
    $languages=Utility::languages();
@endphp
<nav class="navbar navbar-main navbar-expand-lg navbar-border n-top-header" id="navbar-main">
    <div class="container-fluid">
        <button class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbar-main-collapse"
                aria-controls="navbar-main-collapse"
                aria-expanded="false"
                aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- User's navbar -->
        <div class="navbar-user d-lg-none ml-auto">
            <ul class="navbar-nav flex-row align-items-center">
                <li class="nav-item">
                    <a
                        href="#"
                        class="nav-link nav-link-icon sidenav-toggler"
                        data-action="sidenav-pin"
                        data-target="#sidenav-main"
                    ><i class="fas fa-bars"></i
                        ></a>
                </li>
                <li class="nav-item dropdown dropdown-animate">
                    <a
                        class="nav-link pr-lg-0"
                        href="#"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                    <span class="avatar avatar-sm rounded-circle">
                      <img src="{{(!empty($users->avatar)? $profile.'/'.$users->avatar : $profile.'/avatar.png')}}" class="hweb"/>
                    </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right dropdown-menu-arrow">
                        <h6 class="dropdown-header px-0">{{__('Hi')}}, {{\Auth::user()->name}}</h6>
                        @if(\Auth::guard('customer')->check())
                            <a href="{{route('customer.profile')}}" class="dropdown-item">
                                <i class="fas fa-user"></i> <span>{{__('My Profile')}}</span>
                            </a>
                        @elseif(\Auth::guard('vender')->check())
                            <a href="{{route('vender.profile')}}" class="dropdown-item">
                                <i class="fa fa-user"></i> <span>{{__('My Profile')}}</span>
                            </a>
                        @else
                            <a href="{{route('profile')}}" class="dropdown-item has-icon">
                                <i class="fa fa-user"></i> <span>{{__('My Profile')}}</span>
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>{{__('Logout')}}</span>
                        </a>
                        @if(\Auth::guard('customer')->check())
                            <form id="frm-logout" action="{{ route('customer.logout') }}" method="POST" class="d-none">
                                {{ csrf_field() }}
                            </form>
                        @else
                            <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                                {{ csrf_field() }}
                            </form>
                        @endif
                    </div>
                </li>
            </ul>
        </div>

        <div class="collapse navbar-collapse navbar-collapse-fade" id="navbar-main-collapse">
            <ul class="navbar-nav align-items-center d-none d-lg-flex">
                <li class="nav-item">
                    <a
                        href="#"
                        class="nav-link nav-link-icon sidenav-toggler"
                        data-action="sidenav-pin"
                        data-target="#sidenav-main"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item dropdown dropdown-animate">
                    <a
                        class="nav-link pr-lg-0"
                        href="#"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <div class="media media-pill align-items-center">
                      <span class="avatar rounded-circle">
                        <img src="{{(!empty($users->avatar)? $profile.'/'.$users->avatar : $profile.'/avatar.png')}}" class="hweb" />
                      </span>
                            <div class="ml-2 d-none d-lg-block">
                                <span class="mb-0 text-sm font-weight-bold">{{\Auth::user()->name}}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right dropdown-menu-arrow">
                        <h6 class="dropdown-header px-0">{{__('Hi')}}, {{\Auth::user()->name}}</h6>
                        @if(\Auth::guard('customer')->check())
                            <a href="{{route('customer.profile')}}" class="dropdown-item">
                                <i class="fas fa-user"></i> <span>{{__('My Profile')}}</span>
                            </a>
                        @elseif(\Auth::guard('vender')->check())
                            <a href="{{route('vender.profile')}}" class="dropdown-item">
                                <i class="fa fa-user"></i> <span>{{__('My Profile')}}</span>
                            </a>
                        @else
                            <a href="{{route('profile')}}" class="dropdown-item has-icon">
                                <i class="fa fa-user"></i> <span>{{__('My Profile')}}</span>
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>{{__('Logout')}}</span>
                            @if(\Auth::guard('customer')->check())
                                <form id="frm-logout" action="{{ route('customer.logout') }}" method="POST" class="d-none">
                                    {{ csrf_field() }}
                                </form>
                            @else
                                <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                                    {{ csrf_field() }}
                                </form>
                            @endif
                        </a>
                    </div>
                </li>
                @if( Gate::check('create product & service') ||  Gate::check('create customer') ||  Gate::check('create vender')||  Gate::check('create proposal')||  Gate::check('create invoice')||  Gate::check('create bill') ||  Gate::check('create goal') ||  Gate::check('create bank account'))
                    <li class="nav-item">
                        <div class="dropdown notification-icon">
                            <button class="dropdown-toggle" type="button" id="dropdownBookmark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bookmark text-primary"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownBookmark">
                                @if(Gate::check('create product & service'))
                                    <a class="dropdown-item" href="#" data-url="{{ route('productservice.create') }}" data-ajax-popup="true" data-title="{{__('Create New Product')}}"><i class="fas fa-shopping-cart"></i>{{__('Create New Product')}}</a>
                                @endif
                                @if(Gate::check('create customer'))
                                    <a class="dropdown-item" href="#" data-url="{{ route('customer.create') }}" data-ajax-popup="true" data-title="{{__('Create New Customer')}}"><i class="fas fa-user"></i>{{__('Create New Customer')}}</a>
                                @endif
                                @if(Gate::check('create vender'))
                                    <a class="dropdown-item" href="#" data-url="{{ route('vender.create') }}" data-ajax-popup="true" data-title="{{__('Create New Vendor')}}"><i class="fas fa-sticky-note"></i>{{__('Create New Vendor')}}</a>
                                @endif
                                @if(Gate::check('create proposal'))
                                    <a class="dropdown-item" href="{{ route('proposal.create',0) }}"><i class="fas fa-file"></i>{{__('Create New Proposal')}}</a>
                                @endif
                                @if(Gate::check('create invoice'))
                                    <a class="dropdown-item" href="{{ route('invoice.create',0) }}"><i class="fas fa-money-bill-alt"></i>{{__('Create New Invoice')}}</a>
                                @endif
                                @if(Gate::check('create bill'))
                                    <a class="dropdown-item" href="{{ route('bill.create',0) }}"><i class="fas fa-money-bill-wave-alt"></i>{{__('Create New Bill')}}</a>
                                @endif
                                @if(Gate::check('create bank account'))
                                    <a class="dropdown-item" href="#" data-url="{{ route('bank-account.create') }}" data-ajax-popup="true" data-title="{{__('Create New Account')}}"><i class="fas fa-university"></i>{{__('Create New Account')}}</a>
                                @endif
                                @if(Gate::check('create goal'))
                                    <a class="dropdown-item" href="#" data-url="{{ route('goal.create') }}" data-ajax-popup="true" data-title="{{__('Create New Goal')}}"><i class="fas fa-bullseye"></i>{{__('Create New Goal')}}</a>
                                @endif
                            </div>
                        </div>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav ml-lg-auto align-items-lg-center">
                <li class="nav-item">
                    <div class="dropdown global-icon" data-toggle="tooltip" data-original-titla="{{__('Choose Language')}}">
                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-globe-europe"></i>
                        </button>
                        <div class="dropdown-menu  dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            @if(\Auth::user()->type=='company')
                                <a class="dropdown-item" href="{{route('manage.language',[$currantLang])}}">{{ __('Create & Customize') }}</a>
                            @endcan
                            @foreach($languages as $language)
                                <a class="dropdown-item @if($language == $currantLang) text-danger @endif" href="{{route('change.language',$language)}}">{{Str::upper($language)}}</a>
                            @endforeach
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
