@extends('layout')

@section('content')
    @if(is_null($picture))
        <div class="container-fluid">
            <img class="img-thumbnail" src="https://media.giphy.com/media/TUJx6ORB9Y0Uw/giphy.gif" width="500px" />
            <h2>Ya has votado todas las imágenes</h2>
        </div>
    @else
        <div class="container-fluid">
            <div class="col-md-6">
                <img src="{{ $picture->url }}" class="img-responsive img-thumbnail" data-lity alt="">
            </div>
            <div class="col-md-6">
                @if(! is_null($vote))
                    <h3>Has votado {!! ($vote->vote ? '<span class="text-success">SÍ</span>' : '<span class="text-danger">NO</span>') !!}</h3>
                    <hr>
                @else
                    <h3>No has votado aún</h3>
                    <hr>
                @endif
                <div class="col-md-12">
                    <div class="col-md-6">
                        {!! Form::open(['url' => route('process.votes')]) !!}
                        {!! Form::hidden('picture_id', $picture->id ) !!}
                        {!! Form::hidden('vote', 1) !!}
                        {!! Form::submit('SÍ', ["class" => "btn btn-success btn-block"]) !!}
                        {!! Form::close() !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::open(['url' => route('process.votes')]) !!}
                        {!! Form::hidden('picture_id', $picture->id ) !!}
                        {!! Form::hidden('vote', 0) !!}
                        {!! Form::submit('NO', ["class" => "btn btn-danger btn-block"]) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection