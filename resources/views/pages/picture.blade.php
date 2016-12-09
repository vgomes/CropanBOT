@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 text-center">
                <div class="panel">
                    <div class="panel-body">
                        <img src="{{ $picture->url }}" alt="" class="img-responsive">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                @if(!Route::currentRouteNamed('pages.unnamed'))
                    <div class="row">
                            @if(!is_null($vote))
                                @if($vote->vote)
                                    <div class="alert alert-success text-center" role="alert">Has votado SÍ</div>
                                @else
                                    <div class="alert alert-danger text-center" role="alert">Has votado NO</div>
                                @endif
                                <hr>
                            @endif
                    </div>

                    <div class="row">
                        <a id="no" href="{{ route('pages.picture', ['picture' => $picture, 'choice' => 'no']) }}" class="btn btn-danger btn-lg btn-block">NO</a><a id="yld" href="{{ route('pages.picture', ['picture' => $picture, 'choice' => 'yld']) }}" class="btn btn-success btn-lg btn-block">YLD</a>
                        <hr>
                    </div>

                    @if($picture->people->count() > 0)
                        <div class="row">
                            @foreach($picture->people as $person)
                                <div class="tag">
                                    {!! Form::open(['route' => 'pages.untag', 'class' => 'form-inline']) !!}
                                    {!! Form::hidden('picture_id', $picture->id) !!}
                                    {!! Form::hidden('person_id', $person->id) !!}
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pages.directory.person', ['slug' => $person->slug]) }}" class="btn btn-default">{{ $person->name }}</a>
                                        {{ Form::button('<span class="glyphicon glyphicon-remove"></span>', ['class'=>'btn btn-default', 'type'=>'submit']) }}
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            @endforeach
                        </div>
                        <hr>
                    @endif
                @endif

                @include('includes.add_people')
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script type="application/javascript">
    $(document).ready(function () {
        $(window).bind('keydown', function(e) {
            switch (e.which) {
                case 39 :
                case 68 :
                case 89 :
                    $('#yld')[0].click();
                    break;

                case 37 :
                case 65 :
                case 78 :
                    $('#no')[0].click();
                    break;
            }
        });
    });
</script>
@endpush