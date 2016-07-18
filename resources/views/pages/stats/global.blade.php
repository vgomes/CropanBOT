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
        // ID of the element in which to draw the chart.
        element: 'globalBarGraph',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: JSON.parse('{!! $globalImagesBarGraph !!}'),
        // The name of the data record attribute that contains x-values.
        xkey: 'title',
        // A list of names of data record attributes that contain y-values.
        ykeys: ['value'],
        // Labels for the ykeys -- will be displayed when you hover over the
        // chart.
        labels: ['Value'],
        hideHover: true
    });

    new Morris.Donut({
        element: 'globalImagesYesNoDonut',
        data: JSON.parse('{!! $globalImagesYesNoDonut !!}'),
        colors: ['darkblue', 'red']
    })
</script>
@endpush