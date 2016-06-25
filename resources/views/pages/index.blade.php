@extends('layout')

@section('content')
    <h1>Últimas enviadas a Tumblr</h1>
    <div class="container-fluid">
        @foreach($pictures->chunk(3) as $items)
            <div class="row">
                @foreach($items as $item)
                    <div class="col-md-4">
                        <a href="{{ $item->url }}">
                            <img class="img-thumbnail img-responsive" src="{{ $item->url }}" />
                        </a>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    {!! $pictures->links() !!}
@endsection