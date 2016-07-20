@extends('layout')

@section('content')
    <h3>Estadísticas globales</h3>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <h5 class="text-xs-center">Totales</h5>
                <div class="graph" id="globalBarGraph"></div>
            </div>
            <div class="col-md-6">
                <h5 class="text-xs-center">Aprobadas / Rechazadas</h5>
                <div class="graph" id="globalImagesYesNoDonut"></div>
            </div>
        </div>
    </div>

    <hr>

    @foreach(json_decode($globalImagesAreaGraph, true) as $item)
        <div class="row">
            <div class="col-md-12"><h4 class="text-xs-center">{{ $item['year'] }}</h4></div>
            <div class="col-md-9">
                <div class="graph" id="globalStatsForPicturesYears_{{ $item['year'] }}"></div>
            </div>
            <div class="col-md-3">
                <div class="graph" id="globalStatsForVotesYears_{{ $item['year'] }}"></div>
            </div>
        </div>

        @push('jscode')
        <script>
            new Morris.Area({
                element: 'globalStatsForPicturesYears_{{ $item['year'] }}',
                data: {!! json_encode($item['areaGraph']) !!},
                xkey: 'month',
                ykeys: ['sent', 'published', 'images_positive', 'images_negative'],
                labels: ['Enviadas', 'Publicadas', 'Puntuación positiva', 'Puntuación negativa'],
                hideHover: true,
                lineColors: ['#003049', '#f96900', '#61d095', '#d62828'],
                parseTime: false,
                behaveLikeLine: true
            });
        </script>
        <script>
            new Morris.Donut({
                element: 'globalStatsForVotesYears_{{ $item['year'] }}',
                data: {!! json_encode($item['donutGraph']) !!},
                colors: ['#61d095', '#d62828'],
                formatter: function (y, data) { return ((y / data.total) * 100).toFixed(2) + '%' }
            });
        </script>
        @endpush
    @endforeach
@endsection

@push('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@endpush

@push('scripts')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
@endpush