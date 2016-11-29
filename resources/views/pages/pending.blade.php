@extends('layouts.app')

@section('content')
    @if(is_null($picture))
        <div class="col-md-8 col-md-offset-2 text-center">
            <img src="https://media.giphy.com/media/TUJx6ORB9Y0Uw/giphy.gif" alt="" class="img-thumbnail" width="100%">
            <h2>Ya has votado todas las imágenes</h2>
        </div>
    @else
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    {!! Form::open(['route' => 'pages.vote']) !!}
                    {!! Form::hidden('picture_id', $picture->id) !!}
                    {!! Form::hidden('vote', 0) !!}
                    {!! Form::submit('NO', ['id' => 'no', 'class' => 'btn btn-danger btn-lg btn-block']) !!}
                    {!! Form::close() !!}
                </div>
                <div class="col-md-8 text-center">
                    <img src="{{ $picture->url }}" alt="" class="img-responsive img-thumbnail cropan-big">
                </div>
                <div class="col-md-2">
                    {!! Form::open(['url' => route('pages.vote'), 'method' => 'POST']) !!}
                    {!! Form::hidden('picture_id', $picture->id) !!}
                    {!! Form::hidden('vote', 1) !!}
                    {!! Form::submit('YLD', ['id' => 'yld', 'class' => 'btn btn-success btn-lg btn-block']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script type="application/javascript">
    $(document).ready(function () {
        $(window).bind('keydown', function (e) {
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