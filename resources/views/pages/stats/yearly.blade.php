@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Estadísticas globales</h3>

        <hr>

        @foreach(json_decode($data, true) as $key => $item)
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-center">{{ $key }}</h4>
                </div>
                <div class="col-md-9">
                    <div class="graph" id="globalStatsForMonth_{{ $key }}"></div>
                </div>
                <div class="col-md-3">
                    <div class="graph" id="globalStatsForVotesMonth_{{ $key }}"></div>
                </div>
            </div>

            @push('jscode')
            <script>
                new Morris.Line({
                    element: 'globalStatsForMonth_{{ $key }}',
                    data: {!! json_encode($item) !!},
                    xkey: 'date',
                    ykeys: ['sent', 'published', 'images_positive', 'images_negative'],
                    labels: ['Enviadas', 'Publicadas', 'Puntuación positiva', 'Puntuación negativa'],
                    hideHover: true,
                    lineColors: ['#003049', '#f96900', '#61d095', '#d62828'],
                    parseTime: false,
                    behaveLikeLine: true
                });

                var data = {!! json_encode($item) !!};

                var votes = {yes: 0, no: 0};

                for (var k in data) {
                    votes.yes += data[k].votes_yes;
                    votes.no += data[k].votes_no;
                }

                var result = [{'label': 'Sí', 'value': votes.yes, 'total': votes.yes + votes.no}, {
                    'label': 'No',
                    'value': votes.no,
                    'total': votes.yes + votes.no
                }];

                new Morris.Donut({
                    element: 'globalStatsForVotesMonth_{{ $key }}',
                    data: result,
                    colors: ['#61d095', '#d62828'],
                    hideHover: true,
                    formatter: function (y, data) {
                        return ((y / data.total) * 100).toFixed(2) + '%'
                    }
                })
            </script>

            @endpush
        @endforeach
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