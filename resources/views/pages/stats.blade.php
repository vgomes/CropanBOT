@extends('layout')

@section('content')

    <div class="row">
        <div class="container">
            <h2>Ratio de publicadas en Tumblr</h2>
            <br>
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
    </div>

    <br>
    <hr>
    <br>

    <div class="row">
        <div class="container col-md-12">
            <div class="col-md-5">
                <h2>Ratio de YLD</h2>
                <br>
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

            <div class="col-md-5 col-md-offset-2">
                <h2>Ratio de NO</h2>
                <br>
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
    </div>

    <br>
    <hr>
    <br>

    <div class="row">
        <div class="container col-md-12">
            <div class="col-md-5">
                <h2><abbr title="Veces que ha sido el único en votar sí, cuando los demás votos han sido no">Gusto
                        peculiar</abbr></h2>
                <br>
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

            <div class="col-md-5 col-md-offset-2">
                <h2>
                    <abbr title="Veces que ha sido el único en votar no, cuando los demás votos han sido sí">Puntilloso</abbr>
                </h2>
                <br>
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
    </div>

@endsection