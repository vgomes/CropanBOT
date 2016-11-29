@foreach($pictures->chunk(4) as $items)
    <div class="row">
        @foreach($items as $picture)
            <div class="col-md-3">
                <a href="{{ route('pages.picture', ['picture' => $picture]) }}">
                    <div class="panel panel-cropan">
                        <div class="panel-body">
                            <img src="{{ $picture->url }}" alt="" class="img-responsive img-rounded cropan">
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="pull-left">
                                    @if($picture->score > 0)
                                        <a href="#" class="btn btn-success btn-xs">+{{ $picture->score }}</a>
                                    @elseif($picture->score == 0)
                                        <a href="#" class="btn btn-primary btn-xs">{{ $picture->score }}</a>
                                    @else
                                        <a href="#" class="btn btn-danger btn-xs">{{ $picture->score }}</a>
                                    @endif
                                </div>
                                <div class="pull-right">
                                    <div class="btn-group" role="group">
                                        @if($picture->yes > 0)
                                            <a href="#" class="btn btn-success btn-xs">+{{ $picture->yes }}</a>
                                        @endif
                                        @if($picture->no > 0)
                                            <a href="#" class="btn btn-danger btn-xs">-{{ $picture->no }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endforeach