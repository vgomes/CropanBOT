@extends('layout')

@section('content')
    <div class="row">
        <h1>CropanBOT</h1>
        <br>
        <a href="{{ route('login.twitter') }}" class="btn btn-lg btn-primary">Login with Twitter</a>
    </div>
@endsection