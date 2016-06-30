@extends('layout')

@section('content')
    <div class="container-fluid">
        <div class="col-md-6">
            <img src="{{ $picture->url }}" class="img-responsive img-thumbnail" alt="">
        </div>
        <div class="col-md-6">
            <h3>Has votado {!! ($vote->vote ? '<span class="text-success">SÍ</span>' : '<span class="text-danger">NO</span>') !!}</h3>
            <hr>
            <div class="col-md-12">
                <div class="col-md-6">
                    <a href="{{ route('pages.vote', ['iamge' => $picture, 'vote' => 'yld']) }}" class="btn btn-success btn-block">Vota SÍ</a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('pages.vote', ['iamge' => $picture, 'vote' => 'no']) }}" class="btn btn-danger btn-block">Vota NO</a>
                </div>
            </div>
        </div>
    </div>
@endsection