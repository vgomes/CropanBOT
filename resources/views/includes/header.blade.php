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
                @if(Auth::check())
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
                        <li class="nav-item btn-group">
                            <a class="dropdown-toggle nav-link" type="link" data-toggle="dropdown">Estadísticas</a>
                            <div class="dropdown-menu">
                                <a href="#" class="dropdown-item">Globales</a>
                                <a href="#" class="dropdown-item">Usuarios</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pages.pending') }}">Pendientes</a>
                        </li>
                        <li class="nav-item pull-xs-right btn-group">
                            <a class="dropdown-toggle nav-link" type="link" id="dropdownMenu1" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->nickname }} <span
                                        class="label label-info">Lvl {{ Auth::user()->level }}</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <a class="dropdown-item"
                                   href="{{ route('pages.explog') }}">XP: {{ Auth::user()->current_exp }} / 1000</a>
                                <progress class="progress progress-striped progress-success dropdown-item"
                                          value="{{ Auth::user()->current_exp }}" max="1000">25%
                                </progress>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>

                            </div>
                        </li>
                        <li class="nav-item pull-xs-right">
                            <img class="img-circle" style="height: 35px" src="{{ Auth::user()->avatar }}"/>
                        </li>
                        @endif
                    </ul>
            </div>
        </div>
    </nav>


</div>