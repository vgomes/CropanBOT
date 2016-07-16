@extends('layout')

@section('content')
    @foreach($pictures->chunk(4) as $items)
        <div class="row">
            @foreach($items as $item)
                <div class="col-md-3">
                    <div class="card">
                        <img src="{{ $item->url }}" alt="Card image" class="img-fluid center-cropped">
                        <div class="card-block">
                            <a href="#" class="card-link">Card link</a>
                            <a href="#" class="card-link">Another link</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
    {{--<h1>Últimas enviadas a Tumblr</h1>--}}
    {{--<div class="container-fluid">--}}
    {{--@foreach($pictures->chunk(3) as $items)--}}
    {{--<div class="row">--}}
    {{--@foreach($items as $item)--}}
    {{--<div class="col-md-4">--}}
    {{--<a href="{{ $item->url }}">--}}
    {{--<img class="img-thumbnail img-responsive" src="{{ $item->url }}" />--}}
    {{--</a>--}}
    {{--</div>--}}
    {{--@endforeach--}}
    {{--</div>--}}
    {{--@endforeach--}}
    {{--</div>--}}

    {{--{!! $pictures->links() !!}--}}
@endsection