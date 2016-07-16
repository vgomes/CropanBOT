@extends('layout')

@section('content')
    <div class="jumbotron text-xs-center">
        <h1 class="display-3">Cropan Gourmet</h1>
        <br>
        <p class="lead">El club de caballeros más selecto de todo el internec.</p>
        <br>
        <p><a href="{{ route('login.twitter') }}" class="btn btn-lg btn-primary">Entrar</a></p>
    </div>
@endsection

@push('styles')
<style>
    body {
        background: url('http://17rg073sukbm1lmjk9jrehb643.wpengine.netdna-cdn.com/wp-content/uploads/2014/01/gentlemen.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    .jumbotron {
        margin-top: 8rem;
        padding: 2rem 0rem;
    }
</style>
@endpush