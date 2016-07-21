@extends('layout')

@section('content')
    <h3>Estadísticas globales</h3>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-9">
                <div class="graph" id="globalBarGraph"></div>
            </div>
            <div class="col-md-3">
                <div class="graph" id="votesGlobalTotals"></div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <h4>Distribución horaria de imágenes enviadas </h4>
                <div class="graph" id="picturesPerHour"></div>
            </div>
            <div class="col-md-6">
                <h4>Distribución horaria de votos emitidos</h4>
                <div class="graph" id="votesPerHour"></div>
            </div>
        </div>
    </div>

    <hr>

    @foreach(json_decode($globalImagesAreaGraph, true) as $item)
        <div class="row">
            <div class="col-md-12"><h4 class="text-xs-center"><a href="{{ route('pages.stats.global.year', ['year' => $item['year']]) }}">{{ $item['year'] }}</a></h4></div>
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
                hideHover: true,
                formatter: function (y, data) {
                    return ((y / data.total) * 100).toFixed(2) + '%'
                }
            });
        </script>
        @endpush
    @endforeach

    @push('jscode')
    <script>
        new Morris.Bar({
            element: 'globalBarGraph',
            data: {!! $totalImagesData !!},
            xkey: 'title',
            ykeys: ['value'],
            labels: ['Total'],
            barColors: ['#0b62a4', '#003049', '#f96900', '#61d095', '#d62828'],
            hideHover: true
        });
    </script>
    <script>
        new Morris.Donut({
            element: 'votesGlobalTotals',
            data: {!! $getVotesGlobalTotals !!},
            colors: ['#61d095', '#d62828'],
            hideHover: true,
            formatter: function (y, data) {
                return ((y / data.total) * 100).toFixed(2) + '%'
            }
        })
    </script>
    <script>
        new Morris.Line({
            element: 'picturesPerHour',
            data: {!! json_encode($picturesPerHour) !!},
            xkey: 'hour',
            ykeys: ['value'],
            labels: ['Imágenes'],
            hideHover: true,
            lineColors: ['#003049'],
            parseTime: false
        });

        new Morris.Line({
            element: 'votesPerHour',
            data: {!! json_encode($votesPerHour) !!},
            xkey: 'hour',
            ykeys: ['yes', 'no'],
            labels: ['Sí', 'No'],
            hideHover: true,
            lineColors: ['#61d095', '#d62828'],
            parseTime: false
        })
    </script>
    @endpush
@endsection

@push('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@endpush

@push('scripts')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
@endpush