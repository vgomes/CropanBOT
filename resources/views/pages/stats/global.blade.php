@extends('layout')

@section('content')
    <h3>Estadísticas globales</h3>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
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

    @foreach(json_decode($globalImagesBarGraph, true) as $item)
        <div class="row">
            <h4 class="text-xs-center">{{ $item['year'] }}</h4>
            <div class="col-md-6">
                <h5 class="text-xs-center">Imágenes</h5>
                <div class="graph" id="globalStatsForPicturesYears"></div>
            </div>
            <div class="col-md-6">
                <h5 class="text-xs-center">Votos</h5>
                <div class="graph" id="globalStatsForVotesYears"></div>
            </div>
        </div>

        @push('jscode')
        <script>
            new Morris.Bar({
                element: 'globalStatsForPicturesYears',
                data: {!! json_encode($item['data']) !!},
                xkey: 'month',
                ykeys: ['sent', 'published', 'images_positive', 'images_negative'],
                labels: ['Enviadas', 'Publicadas', 'Puntuación postiva', 'Puntuación negativa'],
                hideHover: true
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