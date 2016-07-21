@extends('layout')

@section('content')
    <h3>Estadísticas de usuarios</h3>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="graph" id="usersBarGraph"></div>
        </div>
        <div class="col-md-12">
            <div class="graph" id="usersTumblrRatioBarGraph"></div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-9">

            </div>

            <div class="col-md-3">

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