@if(Auth::check())
<ul class="nav navbar-nav">
    <li><a href="{{ route('pages.history') }}">Historial</a></li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Ranking<span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="{{ route('pages.ranking', ['order' => 'desc']) }}">Mejor puntuadas</a></li>
            <li><a href="{{ route('pages.ranking', ['order' => 'asc']) }}">Peor puntuadas</a></li>
        </ul>
    </li>
    <li><a href="#" data-toggle="dropdown">Directorio <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="{{ route('pages.directory', ['criteria' => 'alphabet']) }}">Alfabético</a></li>
            <li><a href="{{ route('pages.directory', ['criteria' => 'rating']) }}">Puntuación</a></li>
        </ul>
    </li>
    <li><a href="#" class="dropdown-toggle" data-toggle="dropdown">Estadísticas <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="{{ route("pages.stats.global") }}">Globales</a></li>
            <li><a href="{{ route("pages.stats.users") }}">Usuarios</a></li>
        </ul>
    </li>
    <li><a href="{{ route('pages.pending') }}" title="{{ $pictures_count - \Auth::user()->votes->count() }}">Pendientes</a></li>
    <li><a href="{{ route('pages.unnamed') }}">cropAnon</a></li>
</ul>
@endif