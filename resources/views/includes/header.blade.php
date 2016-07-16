<!-- Fixed navbar -->
<div class="pos-f-t">
    <div class="collapse" id="navbar-header">
        <div class="container bg-inverse p-a-1">
            <h3>Collapsed content</h3>
            <p>Toggleable via the navbar brand.</p>
        </div>
    </div>
    <nav class="navbar navbar-light navbar-static-top bg-faded">
        <div class="container">
            <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse"
                    data-target="#navbar">
                &#9776;
            </button>
            <div class="collapse navbar-toggleable-xs" id="navbar">
                <a class="navbar-brand" href="{{ route('pages.index') }}">Cropan Gourmet</a>
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pages.history') }}">Historial</a>
                    </li>
                    <li class="nav-item btn-group">
                        <a class="dropdown-toggle nav-link" type="link" id="dropdownMenu1" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            Ranking
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <a class="dropdown-item" href="{{ route("pages.score") }}">Positivo</a>
                            <a class="dropdown-item"
                               href="{{ route("pages.score", ['order' => 'reverse']) }}">Negativo</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pages.stats') }}">Estadísticas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pages.pending') }}">Pendientes</a>
                    </li>
                    @if(Auth::check())
                        <li class="nav-item pull-xs-right btn-group">
                            <a class="dropdown-toggle nav-link" type="link" id="dropdownMenu1" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->nickname }} <span
                                        class="label label-info">Lvl {{ Auth::user()->level }}</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <a class="dropdown-item"
                                   href="{{ route('pages.explog') }}">XP: {{ Auth::user()->current_exp }} / 1000</a>
                                <a class="dropdown-item" href="#">
                                    <progress class="progress progress-striped progress-success"
                                              value="{{ Auth::user()->current_exp }}" max="1000">25%
                                    </progress>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>

                            </div>
                        </li>
                        <li class="nav-item pull-xs-right">
                            <img class="img-circle" style="height: 35px" src="{{ Auth::user()->avatar }}"/>
                        </li>
                    @endif
                </ul>

                {{--@if(Auth::check())--}}
                {{--<ul class="nav navbar-nav navbar-right">--}}
                {{--<li><img class="img-circle" src="{{ Auth::user()->avatar }}"/></li>--}}
                {{--<li class="dropdown">--}}
                {{--<a aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown"--}}
                {{--class="dropdown-toggle" href="#">{{ Auth::user()->nickname }} <span class="caret"></span></a>--}}
                {{--<ul class="dropdown-menu">--}}
                {{--<li><a href="{{ route('pages.explog') }}">Lvl {{ Auth::user()->level }} | XP: {{ Auth::user()->current_exp }} /--}}
                {{--1000</a></li>--}}
                {{--<li>--}}
                {{--<div class="progress" style="margin: auto 20px">--}}
                {{--<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="{{ Auth::user()->current_exp }}"--}}
                {{--aria-valuemin="0" aria-valuemax="1000" style="width: {{ (Auth::user()->current_exp / 1000) * 100 }}%">--}}
                {{--<span class="sr-only">40% Complete (success)</span>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</li>--}}
                {{--<li role="separator" class="divider"></li>--}}
                {{--<li><a href="{{ route('logout') }}">Logout</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--</ul>--}}
                {{--@endif--}}

            </div>
        </div>
    </nav>


</div>