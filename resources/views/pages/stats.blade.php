@extends('layout')

@section('content')
    <div class="container">
        <h2>Mejor puntuadas</h2>
        <hr>
        @foreach($positiveRanking->chunk(6) as $picture)
            <div class="row">
                @foreach($picture as $pic)
                    <div class="col-md-2">
                    <span class="text-center">
                        <span class="text-success">+{{ $pic->yes }}</span> / <span
                                class="text-danger">-{{ $pic->no }}</span>
                    </span>
                        <a data-toggle="modal" data-target="#modalBest{{ $pic->id }}">
                            <img class="img-thumbnail img-responsive" src="{{ $pic->url }}"/>
                        </a>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="modalBest{{ $pic->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <img src="{{ $pic->url }}"/>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endForeach
            </div>
        @endforeach
    </div>

    <br>
    <hr>
    <br>

    <div class="container">
        <h2>Peor puntuadas</h2>
        <hr>
        @foreach($negativeRanking->chunk(6) as $picture)
            <div class="row">
                @foreach($picture as $pic)
                    <div class="col-md-2">
                    <span class="text-center">
                        <span class="text-success">+{{ $pic->yes }}</span> / <span
                                class="text-danger">-{{ $pic->no }}</span>
                    </span>
                        <a data-toggle="modal" data-target="#modalWorst{{ $pic->id }}">
                            <img class="img-thumbnail img-responsive" src="{{ $pic->url }}"/>
                        </a>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="modalWorst{{ $pic->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <img src="{{ $pic->url }}"/>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endForeach
            </div>
        @endforeach
    </div>

    <br>
    <hr>
    <br>

    <div class="container">
        <h2>Ratio enviadas al grupo/llegan a Tumblr</h2>
        <hr>
        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Ratio</th>
                </tr>
                </thead>
                <tbody>
                @foreach($ratioTumblr as $user)
                    <tr>
                        <th scope="row">{{ $user->nickname }}</th>
                        <td>{{ $user->publishedPercent }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <br>
    <hr>
    <br>

    <div class="container col-md-12">
        <div class="col-md-6">
            <h2>Ratio de YLD</h2>
            <hr>
            <div class="row">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Ratio</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ratioYLD as $user)
                        <tr>
                            <th scope="row">{{ $user->nickname }}</th>
                            <td>{{ $user->yesPercent }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <h2>Ratio de NO</h2>
            <hr>
            <div class="row">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Ratio</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ratioNO as $user)
                        <tr>
                            <th scope="row">{{ $user->nickname }}</th>
                            <td>{{ $user->noPercent }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br>
    <hr>
    <br>

    <div class="container col-md-12">
        <div class="col-md-6">
            <h2><abbr title="Veces que ha sido el único en votar sí, cuando los demás votos han sido no">Gusto peculiar</abbr></h2>
            <hr>
            <div class="row">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Veces</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($uncommonTaste as $user)
                        <tr>
                            <th scope="row">{{ $user->nickname }}</th>
                            <td>{{ $user->times }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <h2><abbr title="Veces que ha sido el único en votar no, cuando los demás votos han sido sí">Puntilloso</abbr></h2>
            <hr>
            <div class="row">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Veces</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($nitpicker as $user)
                        <tr>
                            <th scope="row">{{ $user->nickname }}</th>
                            <td>{{ $user->times }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection