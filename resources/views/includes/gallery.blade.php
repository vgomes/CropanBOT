@foreach($pictures->chunk(4) as $items)
    <div class="row">
        @foreach($items as $item)
            <div class="col-md-3">
                <div class="card">
                    <a href="{{ $item->url }}" data-lity>
                        <img src="{{ $item->url }}" alt="Imagen" class="img-fluid center-cropped lazyload">
                    </a>
                    <div class="card-block">
                        <h5 class="pull-xs-left">
                            @if($item->score > 0)
                                <span class="label label-info">+{{ $item->score }}</span>
                            @elseif($item->score == 0)
                                <span class="label label-info">0</span>
                            @else
                                <span class="label label-info">{{ $item->score }}</span>
                            @endif
                        </h5>
                        <h5 class="pull-xs-right">
                            @if($item->yes > 0)
                                <span class="label label-success">+{{ $item->yes }}</span>
                            @endif
                            @if($item->no > 0)
                                <span class="label label-danger text-xs-right">-{{ $item->no }}</span>
                            @endif
                        </h5>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach

<div class="text-xs-center">
    <nav>
        {!! $pictures->links() !!}
    </nav>
</div>


<div id="inline" style="background:#fff" class="lity-hide">
    Inline content
</div>