@extends('layout')

@section('content')
    <h3>Estadísticas de usuarios</h3>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <h5 class="text-xs-center">Estadísticas globales</h5>
            <div class="graph" id="usersBarGraph"></div>
        </div>
    </div>

    <hr>

    <br>
    <br>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-9">
                <h5 class="text-xs-center">Ratio de enviadas a Tumblr</h5>
                <div class="graph" id="usersTumblrRatioBarGraph"></div>
            </div>

            <div class="col-md-3">
                <div>
                    <h5 class="text-xs-center">Ratio enviadas a Tumblr</h5>
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Ratio</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($usersBarGraph as $user)
                            <tr>
                                <th scope="row">{{ $user['nickname'] }}</th>
                                <td>{{ $user['publishedRatio'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('jscode')
<script>
    new Morris.Bar({
        element: 'usersBarGraph',
        data: {!! json_encode($usersBarGraph) !!},
        xkey: 'nickname',
        ykeys: ['sent', 'published'],
        labels: ['Enviadas', 'Publicadas'],
        resize: 'true',
        barColors: ['#0b62a4', '#003049'],
        hideHover: true
    });
</script>
<script>
    new Morris.Bar({
        element: 'usersTumblrRatioBarGraph',
        data: {!! json_encode($usersBarGraph) !!},
        xkey: 'nickname',
        ykeys: ['publishedRatio'],
        labels: ['Publicadas en Tumblr'],
        resize: 'true',
        barColors: ['#0b62a4', '#003049'],
        hideHover: true
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@endpush

@push('scripts')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
@endpush