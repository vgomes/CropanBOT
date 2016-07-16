@extends('layout')

@section('content')
    @foreach($pictures->chunk(4) as $items)
        <div class="row">
            @foreach($items as $item)
                <div class="col-md-3">
                    <div class="card">
                        <a href="{{ $item->url }}" data-lity>
                            <img src="{{ $item->url }}" alt="Card image" class="img-fluid center-cropped lazyload">
                        </a>
                        <div class="card-block">
                            <h5>
                                @if($item->yes > 0)
                                    <span class="label label-success">+{{ $item->yes }}</span>
                                @endif
                                @if($item->no > 0)
                                    <span class="label label-danger text-xs-right">-{{ $item->no }}</span>
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    <nav class="center-block">
        {!! $pictures->links() !!}
    </nav>
    <div id="inline" style="background:#fff" class="lity-hide">
        Inline content
    </div>
@endsection