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

    <div class="row">
        <h4 class="text-xs-center">Desglose por año</h4>
        <div class="col-md-6">
            <h5 class="text-xs-center">Imágenes</h5>
            <div class="graph" id="globalStatsForPicturesYears"></div>
        </div>
        <div class="col-md-6">
            <h5 class="text-xs-center">Votos</h5>
            <div class="graph" id="globalStatsForVotesYears"></div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@endpush

@push('scripts')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
@endpush

@push('jscode')
<script>
    new Morris.Bar({
        element: 'globalBarGraph',
        data: JSON.parse('{!! $globalImagesBarGraph !!}'),
        xkey: 'title',
        ykeys: ['value'],
        labels: ['Value'],
        hideHover: true
    });

    new Morris.Donut({
        element: 'globalImagesYesNoDonut',
        data: JSON.parse('{!! $globalImagesYesNoDonut !!}'),
        colors: ['darkblue', 'red']
    });

    new Morris.Bar({
        element: 'globalStatsForPicturesYears',
        data: JSON.parse('{!! $globalStatsForYears !!}'),
        xkey: 'year',
        ykeys: ['sent', 'published', 'images_positive', 'images_negative'],
        labels: ['Enviadas a CropanBOT', 'Publicadas en Tumblr', 'Valoración positiva', 'Valoración negativa'],
        barColors: ['blue', 'darkblue', 'green', 'red'],
        hideHover: true
    });

    new Morris.Bar({
        element: 'globalStatsForVotesYears',
        data: JSON.parse('{!! $globalStatsForYears !!}'),
        xkey: 'year',
        ykeys: ['votes_yes', 'votes_no'],
        labels: ['Sí', 'No'],
        barColors: ['green', 'red'],
        hideHover: true
    });
</script>
@endpush