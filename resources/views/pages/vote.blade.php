@extends('layout')

@section('content')
    <div class="container-fluid">
        <div class="col-md-6">
            <img src="{{ $picture->url }}" class="img-responsive img-thumbnail" alt="">
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
                    {!! Form::hidden('vote', true) !!}
                    {!! Form::submit('SÍ', ["class" => "btn btn-success btn-block"]) !!}
                    {!! Form::close() !!}
                </div>
                <div class="col-md-6">
                    {!! Form::open(['url' => route('process.votes')]) !!}
                    {!! Form::hidden('picture_id', $picture->id ) !!}
                    {!! Form::hidden('vote', false) !!}
                    {!! Form::submit('NO', ["class" => "btn btn-danger btn-block"]) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection